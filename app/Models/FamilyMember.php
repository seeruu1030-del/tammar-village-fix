<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;
    protected $fillable = ['resident_id', 'name', 'nik', 'birth_place', 'birth_date', 'relationship', 'status'];

    protected $appends = ['age', 'completeness'];

    public function getAgeAttribute()
    {
        if (!$this->birth_date) return null;
        return \Carbon\Carbon::parse($this->birth_date)->age;
    }

    public function getCompletenessAttribute()
    {
        $fields = ['name', 'nik', 'birth_place', 'birth_date', 'relationship'];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) $filled++;
        }
        return ($filled / count($fields)) * 100;
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
