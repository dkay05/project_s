<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address_line1' => $this->address_line1,
            'state' => $this->state,
            'city' => $this->city,
            'pincode' => $this->pincode,
            'date_of_birth' => $this->date_of_birth->format('Y-m-d'),
            'marital_status' => $this->marital_status,
            'blood_group' => $this->blood_group,
            'photo_url' => $this->photo_url,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),

            'educations' => $this->whenLoaded('educations', function () {
                return $this->educations->map(fn ($edu) => [
                    'id' => $edu->id,
                    'degree' => $edu->degree,
                    'institution_name' => $edu->institution_name,
                    'field_of_study' => $edu->field_of_study,
                    'start_date' => $edu->start_date->format('Y-m-d'),
                    'end_date' => $edu->end_date?->format('Y-m-d'),
                ]);
            }),

            'previous_employers' => $this->whenLoaded('previousEmployers', function () {
                return $this->previousEmployers->map(fn ($emp) => [
                    'id' => $emp->id,
                    'company_name' => $emp->company_name,
                    'hr_name' => $emp->hr_name,
                    'hr_phone' => $emp->hr_phone,
                    'address_line1' => $emp->address_line1,
                    'state' => $emp->state,
                    'city' => $emp->city,
                    'pincode' => $emp->pincode,
                    'monthly_salary' => $emp->monthly_salary,
                    'designation' => $emp->designation,
                    'duration_for_working' => $emp->duration_for_working,
                    'salary_slip_url' => $emp->salary_slip_url,
                ]);
            }),

            'bank_details' => $this->whenLoaded('bankDetails', function () {
                return $this->bankDetails->map(fn ($bank) => [
                    'id' => $bank->id,
                    'account_holder_name' => $bank->account_holder_name,
                    'bank_name' => $bank->bank_name,
                    'account_number' => $bank->account_number,
                    'ifsc_code' => $bank->ifsc_code,
                    'photo_url' => $bank->photo_url,
                ]);
            }),

            'official_detail' => $this->whenLoaded('officialDetail', function () {
                if (!$this->officialDetail) return null;
                return [
                    'id' => $this->officialDetail->id,
                    'date_of_joining' => $this->officialDetail->date_of_joining->format('Y-m-d'),
                    'designation' => $this->officialDetail->designation,
                    'salary' => $this->officialDetail->salary,
                    'branch' => $this->officialDetail->branch,
                    'permission' => $this->officialDetail->permission,
                ];
            }),
        ];
    }
}
