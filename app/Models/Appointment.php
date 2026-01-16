<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'employee_id',
        'date',
        'time',
        'status',
        'notes',
        'reminder_sent',
        'reminder_sent_at',
    ];

    protected $casts = [
        'date'   => 'date',
        'status' => AppointmentStatus::class,
        'reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    /**
     * Get the HTML badge for the appointment status.
     *
     * @return string
     */
    public function getStatusBadge(): string
    {
        $statusInfo = match ($this->status) {
            AppointmentStatus::Pending   => ['class' => 'warning', 'text' => 'En attente'],
            AppointmentStatus::Confirmed => ['class' => 'info',    'text' => 'Confirmé'],
            AppointmentStatus::Completed => ['class' => 'success', 'text' => 'Terminé'],
            AppointmentStatus::Canceled  => ['class' => 'danger',  'text' => 'Annulé'],
            AppointmentStatus::NoShow    => ['class' => 'dark',    'text' => 'Absent'],
        };

        return '<span class="badge badge-' . $statusInfo['class'] . '">' . $statusInfo['text'] . '</span>';
    }
}
