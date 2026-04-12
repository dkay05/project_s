<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empId = $this->route('employee');

        return [
            // Step 1 — Basic Info
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $empId,
            'contact_number' => 'required|digits_between:10,13',
            'address_line1' => 'required|string|max:500',
            'state' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|digits:6',
            'date_of_birth' => 'required|date|before:today',
            'marital_status' => 'required|in:single,married,divorced',
            'blood_group' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Step 2 — Education (array)
            'educations' => 'required|array|min:1',
            'educations.*.degree' => 'required|string',
            'educations.*.institution_name' => 'required|string',
            'educations.*.field_of_study' => 'required|string',
            'educations.*.start_date' => 'required|date',
            'educations.*.end_date' => 'nullable|date|after:educations.*.start_date',

            // Step 3 — Previous Employer (array)
            'employers' => 'nullable|array',
            'employers.*.company_name' => 'required_with:employers|string',
            'employers.*.hr_name' => 'required_with:employers|string',
            'employers.*.hr_phone' => 'required_with:employers|digits_between:10,13',
            'employers.*.monthly_salary' => 'required_with:employers|numeric|min:0',
            'employers.*.designation' => 'required_with:employers|string',
            'employers.*.duration_for_working' => 'required_with:employers|string',
            'employers.*.salary_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // Step 4 — Bank Details (array)
            'banks' => 'required|array|min:1',
            'banks.*.account_holder_name' => 'required|string',
            'banks.*.bank_name' => 'required|string',
            'banks.*.account_number' => 'required|string',
            'banks.*.ifsc_code' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'banks.*.photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Step 5 — Official Details
            'official.date_of_joining' => 'required|date',
            'official.designation' => 'required|string',
            'official.salary' => 'required|numeric|min:0',
            'official.branch' => 'required|string',
            'official.permission' => 'nullable|string',
            'official.password' => $empId ? 'nullable|min:8' : 'required|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'educations.required' => 'At least one education entry is required.',
            'educations.min' => 'At least one education entry is required.',
            'banks.required' => 'At least one bank detail entry is required.',
            'banks.min' => 'At least one bank detail entry is required.',
            'banks.*.ifsc_code.regex' => 'IFSC code must be in valid format (e.g., SBIN0001234).',
            'official.password.required' => 'Password is required for new employees.',
        ];
    }
}
