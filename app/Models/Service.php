<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'promotion_price',
        'promotion_start',
        'promotion_end',
        'promotion_label',
        'duration',
        'category',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promotion_price' => 'decimal:2',
        'promotion_start' => 'date',
        'promotion_end' => 'date',
        'active' => 'boolean',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function hasActivePromotion(): bool
    {
        if (!$this->promotion_price) {
            return false;
        }

        $today = Carbon::today();
        
        if ($this->promotion_start && $today->lt($this->promotion_start)) {
            return false;
        }
        
        if ($this->promotion_end && $today->gt($this->promotion_end)) {
            return false;
        }

        return true;
    }

    public function getCurrentPrice(): float
    {
        return $this->hasActivePromotion() ? $this->promotion_price : $this->price;
    }

    public function getDiscountPercentage(): ?int
    {
        if (!$this->hasActivePromotion()) {
            return null;
        }

        return round((($this->price - $this->promotion_price) / $this->price) * 100);
    }

    public function scopeWithPromotion($query)
    {
        return $query->whereNotNull('promotion_price')
            ->where(function ($q) {
                $q->whereNull('promotion_start')
                    ->orWhere('promotion_start', '<=', Carbon::today());
            })
            ->where(function ($q) {
                $q->whereNull('promotion_end')
                    ->orWhere('promotion_end', '>=', Carbon::today());
            });
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
