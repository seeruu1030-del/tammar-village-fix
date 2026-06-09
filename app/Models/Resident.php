<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'nik', 'family_status', 'age', 'birth_place', 'birth_date', 
        'contact', 'email', 'telegram_id', 'document', 'block_id', 'unit_no', 'housing_status', 'status',
        'exit_date', 'exit_status', 'exit_reason'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'exit_date' => 'date',
    ];

    public function getAgeAttribute()
    {
        if ($this->birth_date) {
            return Carbon::parse($this->birth_date)->age;
        }
        return null;
    }

    public function getCompletenessAttribute()
    {
        $fields = ['name', 'nik', 'birth_place', 'birth_date', 'contact', 'email', 'telegram_id', 'document'];
        $filled = 0;
        foreach ($fields as $field) {
            if ($this->$field) $filled++;
        }
        return round(($filled / count($fields)) * 100);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
