<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentCompletion extends Model
{
    use HasFactory;
    protected $fillable = ['appointment_id', 'employee_id', 'remarks', 'reminder_date', 'reminder_description', 'status'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}
