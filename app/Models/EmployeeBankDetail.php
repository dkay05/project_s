<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeeBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'account_holder_name',
        'bank_name',
        'account_number',
        'ifsc_code',
        'photo',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::disk('public')->url($this->photo);
        }

        return null;
    }
}
