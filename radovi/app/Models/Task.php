<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'nastavnik_id',
        'naziv_rada',
        'naziv_rada_en',
        'zadatak_rada',
        'zadatak_rada_en',
        'tip_studija',
        'accepted_student_id',
    ];

    public function nastavnik()
    {
        return $this->belongsTo(User::class, 'nastavnik_id');
    }

    public function acceptedStudent()
    {
        return $this->belongsTo(User::class, 'accepted_student_id');
    }

    public function applications()
    {
        return $this->hasMany(TaskApplication::class);
    }

    public function isAvailable()
    {
        return $this->accepted_student_id === null;
    }
}