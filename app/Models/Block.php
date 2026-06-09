<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'total_units', 'color'];

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}
