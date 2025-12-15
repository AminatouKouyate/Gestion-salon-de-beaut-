<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
        'specialties',
        'work_start_time',
        'work_end_time',
        'work_days',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'work_days' => 'array',
    ];

    /**
     * Get the appointments for the employee.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the leave requests for the employee.
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the notifications for the employee.
     */
    public function notifications()
    {
        return $this->hasMany(EmployeeNotification::class);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get upcoming appointments.
     */
    public function upcomingAppointments()
    {
        return $this->appointments()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time');
    }

    /**
     * Get today's appointments.
     */
    public function todayAppointments()
    {
        return $this->appointments()
            ->whereDate('date', now()->toDateString())
            ->orderBy('time');
    }
}
