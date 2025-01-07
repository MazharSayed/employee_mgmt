<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentHistory extends Model
{
    protected $fillable = [
        'employee_id', 'employer_name', 'position', 'occupation',
        'manager_name', 'manager_email', 'manager_phone', 'start_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

