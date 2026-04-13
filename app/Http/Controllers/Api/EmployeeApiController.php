<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeDetailResource;
use App\Models\Employee;
use App\Models\EmployeeOfficialDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeApiController extends Controller
{
    /**
     * GET /api/employees
     * Query params: ?search=john&status=active&page=1&per_page=10
     *
     * Response:
     * {
     *   "success": true,
     *   "data": [ {...}, {...} ],
     *   "meta": { "current_page": 1, "last_page": 3, "total": 25, "per_page": 10 }
     * }
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $employees = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => EmployeeResource::collection($employees),
            'meta' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    /**
     * POST /api/employees
     * Body: multipart/form-data (same fields as web form)
     *
     * Response: { "success": true, "message": "...", "data": {...} }
     */
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

            $employee->load(['educations', 'previousEmployers', 'bankDetails', 'officialDetail']);

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully.',
                'data' => new EmployeeDetailResource($employee),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/employees/{id}
     *
     * Response: { "success": true, "data": { ...full employee with relations } }
     */
    public function show($id)
    {
        $employee = Employee::with(['educations', 'previousEmployers', 'bankDetails', 'officialDetail'])
            ->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new EmployeeDetailResource($employee),
        ]);
    }

    /**
     * PUT /api/employees/{id}
     * Body: multipart/form-data (same fields as store)
     */
    public function update(StoreEmployeeRequest $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        try {
            DB::beginTransaction();

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

            $employee->load(['educations', 'previousEmployers', 'bankDetails', 'officialDetail']);

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully.',
                'data' => new EmployeeDetailResource($employee),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/employees/{id}
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.',
        ]);
    }

    /**
     * POST /api/employees/{id}/toggle-status
     */
    public function toggleStatus($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        $employee->status = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->save();

        return response()->json([
            'success' => true,
            'status' => $employee->status,
            'message' => 'Status updated to ' . ucfirst($employee->status),
        ]);
    }

    /**
     * GET /api/employees/{id}/educations
     */
    public function educations($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employee->educations,
        ]);
    }

    /**
     * GET /api/employees/{id}/employers
     */
    public function previousEmployers($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employee->previousEmployers,
        ]);
    }

    /**
     * GET /api/employees/{id}/bank-details
     */
    public function bankDetails($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employee->bankDetails,
        ]);
    }

    /**
     * GET /api/employees/{id}/official
     */
    public function officialDetail($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employee->officialDetail,
        ]);
    }
}
