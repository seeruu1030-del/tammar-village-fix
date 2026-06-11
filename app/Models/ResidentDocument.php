<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id', 'name', 'file_path', 'file_type'
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
