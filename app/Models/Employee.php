<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'contact_number',
        'address_line1',
        'state',
        'city',
        'pincode',
        'date_of_birth',
        'marital_status',
        'blood_group',
        'photo',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'status' => 'string',
    ];

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function previousEmployers()
    {
        return $this->hasMany(EmployeePreviousEmployer::class);
    }

    public function bankDetails()
    {
        return $this->hasMany(EmployeeBankDetail::class);
    }

    public function officialDetail()
    {
        return $this->hasOne(EmployeeOfficialDetail::class);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::disk('public')->url($this->photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=f5a623&color=0f1117&size=128';
    }
}
