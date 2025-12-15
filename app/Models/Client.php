<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'loyalty_points',
        'total_appointments',
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(ClientNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function addLoyaltyPoints(int $points): void
    {
        $this->increment('loyalty_points', $points);
    }

    public function useLoyaltyPoints(int $points): bool
    {
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            return true;
        }
        return false;
    }

    public function getLoyaltyLevel(): string
    {
        $points = $this->loyalty_points ?? 0;

        return match(true) {
            $points >= 500 => 'Platine',
            $points >= 200 => 'Or',
            $points >= 100 => 'Argent',
            default => 'Bronze',
        };
    }

    public function getLoyaltyDiscount(): int
    {
        return match($this->getLoyaltyLevel()) {
            'Platine' => 20,
            'Or' => 15,
            'Argent' => 10,
            default => 0,
        };
    }

    public function getUpcomingAppointments()
    {
        return $this->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('date', '>=', now()->toDateString())
            ->with(['service', 'employee'])
            ->orderBy('date')
            ->orderBy('time')
            ->get();
    }

    public function getCompletedAppointments()
    {
        return $this->appointments()
            ->where('status', 'completed')
            ->with(['service', 'employee', 'payment'])
            ->orderBy('date', 'desc')
            ->get();
    }
}
