<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeePreviousEmployer extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'company_name',
        'hr_name',
        'hr_phone',
        'address_line1',
        'state',
        'city',
        'pincode',
        'monthly_salary',
        'designation',
        'duration_for_working',
        'salary_slip',
    ];

    protected $casts = [
        'monthly_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getSalarySlipUrlAttribute()
    {
        if ($this->salary_slip && Storage::disk('public')->exists($this->salary_slip)) {
            return Storage::disk('public')->url($this->salary_slip);
        }

        return null;
    }
}
