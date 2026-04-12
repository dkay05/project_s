<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EmployeeOfficialDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date_of_joining',
        'designation',
        'salary',
        'branch',
        'permission',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'date_of_joining' => 'date',
        'salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
