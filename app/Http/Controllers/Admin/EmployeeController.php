<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\Models\EmployeeBankDetail;
use App\Models\EmployeeEducation;
use App\Models\EmployeeOfficialDetail;
use App\Models\EmployeePreviousEmployer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');

        $query = Employee::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        $employees = $query->latest()->paginate(10)->appends(['search' => $search, 'status' => $status]);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        try {
            DB::beginTransaction();

            $employeeData = $request->only([
                'full_name', 'email', 'contact_number', 'address_line1',
                'state', 'city', 'pincode', 'date_of_birth',
                'marital_status', 'blood_group', 'status',
            ]);

            if ($request->hasFile('photo')) {
                $employeeData['photo'] = $request->file('photo')->store('employees/photos', 'public');
            }

            $employeeData['created_by'] = Auth::id();

            $employee = Employee::create($employeeData);

            // Save educations
            if ($request->has('educations')) {
                foreach ($request->input('educations') as $education) {
                    $employee->educations()->create([
                        'degree' => $education['degree'],
                        'institution_name' => $education['institution_name'],
                        'field_of_study' => $education['field_of_study'],
                        'start_date' => $education['start_date'],
                        'end_date' => $education['end_date'] ?? null,
                    ]);
                }
            }

            // Save previous employers
            if ($request->has('employers')) {
                foreach ($request->input('employers') as $index => $employer) {
                    $employerData = [
                        'company_name' => $employer['company_name'],
                        'hr_name' => $employer['hr_name'],
                        'hr_phone' => $employer['hr_phone'],
                        'address_line1' => $employer['address_line1'] ?? '',
                        'state' => $employer['state'] ?? '',
                        'city' => $employer['city'] ?? '',
                        'pincode' => $employer['pincode'] ?? '',
                        'monthly_salary' => $employer['monthly_salary'],
                        'designation' => $employer['designation'],
                        'duration_for_working' => $employer['duration_for_working'],
                    ];

                    if ($request->hasFile("employers.{$index}.salary_slip")) {
                        $employerData['salary_slip'] = $request->file("employers.{$index}.salary_slip")
                            ->store('employees/salary_slips', 'public');
                    }

                    $employee->previousEmployers()->create($employerData);
                }
            }

            // Save bank details
            if ($request->has('banks')) {
                foreach ($request->input('banks') as $index => $bank) {
                    $bankData = [
                        'account_holder_name' => $bank['account_holder_name'],
                        'bank_name' => $bank['bank_name'],
                        'account_number' => $bank['account_number'],
                        'ifsc_code' => $bank['ifsc_code'],
                    ];

                    if ($request->hasFile("banks.{$index}.photo")) {
                        $bankData['photo'] = $request->file("banks.{$index}.photo")
                            ->store('employees/bank_photos', 'public');
                    }

                    $employee->bankDetails()->create($bankData);
                }
            }

            // Save official details
            if ($request->has('official')) {
                $officialData = $request->input('official');
                $official = new EmployeeOfficialDetail([
                    'date_of_joining' => $officialData['date_of_joining'],
                    'designation' => $officialData['designation'],
                    'salary' => $officialData['salary'],
                    'branch' => $officialData['branch'],
                    'permission' => $officialData['permission'] ?? null,
                ]);
                $official->password = $officialData['password'];
                $official->employee_id = $employee->id;
                $official->save();
            }

            DB::commit();

            return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $employee = Employee::with(['educations', 'previousEmployers', 'bankDetails', 'officialDetail'])
            ->findOrFail($id);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::with(['educations', 'previousEmployers', 'bankDetails', 'officialDetail'])
            ->findOrFail($id);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(StoreEmployeeRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $employee = Employee::findOrFail($id);

            $employeeData = $request->only([
                'full_name', 'email', 'contact_number', 'address_line1',
                'state', 'city', 'pincode', 'date_of_birth',
                'marital_status', 'blood_group', 'status',
            ]);

            if ($request->hasFile('photo')) {
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $employeeData['photo'] = $request->file('photo')->store('employees/photos', 'public');
            }

            $employee->update($employeeData);

            // Update educations
            $employee->educations()->delete();
            if ($request->has('educations')) {
                foreach ($request->input('educations') as $education) {
                    $employee->educations()->create([
                        'degree' => $education['degree'],
                        'institution_name' => $education['institution_name'],
                        'field_of_study' => $education['field_of_study'],
                        'start_date' => $education['start_date'],
                        'end_date' => $education['end_date'] ?? null,
                    ]);
                }
            }

            // Update previous employers
            foreach ($employee->previousEmployers as $prev) {
                if ($prev->salary_slip) {
                    Storage::disk('public')->delete($prev->salary_slip);
                }
            }
            $employee->previousEmployers()->delete();
            if ($request->has('employers')) {
                foreach ($request->input('employers') as $index => $employer) {
                    $employerData = [
                        'company_name' => $employer['company_name'],
                        'hr_name' => $employer['hr_name'],
                        'hr_phone' => $employer['hr_phone'],
                        'address_line1' => $employer['address_line1'] ?? '',
                        'state' => $employer['state'] ?? '',
                        'city' => $employer['city'] ?? '',
                        'pincode' => $employer['pincode'] ?? '',
                        'monthly_salary' => $employer['monthly_salary'],
                        'designation' => $employer['designation'],
                        'duration_for_working' => $employer['duration_for_working'],
                    ];

                    if ($request->hasFile("employers.{$index}.salary_slip")) {
                        $employerData['salary_slip'] = $request->file("employers.{$index}.salary_slip")
                            ->store('employees/salary_slips', 'public');
                    }

                    $employee->previousEmployers()->create($employerData);
                }
            }

            // Update bank details
            foreach ($employee->bankDetails as $bankOld) {
                if ($bankOld->photo) {
                    Storage::disk('public')->delete($bankOld->photo);
                }
            }
            $employee->bankDetails()->delete();
            if ($request->has('banks')) {
                foreach ($request->input('banks') as $index => $bank) {
                    $bankData = [
                        'account_holder_name' => $bank['account_holder_name'],
                        'bank_name' => $bank['bank_name'],
                        'account_number' => $bank['account_number'],
                        'ifsc_code' => $bank['ifsc_code'],
                    ];

                    if ($request->hasFile("banks.{$index}.photo")) {
                        $bankData['photo'] = $request->file("banks.{$index}.photo")
                            ->store('employees/bank_photos', 'public');
                    }

                    $employee->bankDetails()->create($bankData);
                }
            }

            // Update official details
            if ($request->has('official')) {
                $officialData = $request->input('official');
                $official = $employee->officialDetail ?? new EmployeeOfficialDetail(['employee_id' => $employee->id]);
                $official->fill([
                    'date_of_joining' => $officialData['date_of_joining'],
                    'designation' => $officialData['designation'],
                    'salary' => $officialData['salary'],
                    'branch' => $officialData['branch'],
                    'permission' => $officialData['permission'] ?? null,
                ]);
                if (!empty($officialData['password'])) {
                    $official->password = $officialData['password'];
                }
                $official->employee_id = $employee->id;
                $official->save();
            }

            DB::commit();

            return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return back()->with('success', 'Employee deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->save();

        return response()->json([
            'status' => $employee->status,
            'message' => 'Status updated to ' . ucfirst($employee->status),
        ]);
    }
}
