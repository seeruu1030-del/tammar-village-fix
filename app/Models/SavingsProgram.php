<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'name', 'description', 'target_amount', 'max_participants', 'collected_amount', 'status', 'end_date'
    ];

    protected $appends = ['progress_percentage', 'is_full', 'is_unlimited'];

    public function transactions()
    {
        return $this->hasMany(SavingsTransaction::class, 'savings_program_id');
    }

    public function getParticipantsCountAttribute()
    {
        return $this->transactions()->distinct('resident_id')->count('resident_id');
    }

    public function getIsFullAttribute()
    {
        if ($this->max_participants <= 0) return false;
        return $this->participants_count >= $this->max_participants;
    }

    public function getIsUnlimitedAttribute()
    {
        return $this->max_participants <= 0;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        $percentage = ($this->collected_amount / $this->target_amount) * 100;
        return round(min($percentage, 100), 1);
    }
}
