@extends('layouts.app')
@section('page-title', 'Edit Employee')

@section('content')
<div x-data="editEmployeeForm()" x-cloak>
    <!-- Step Indicator -->
    <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:32px;">
        <template x-for="(s, i) in steps" :key="i">
            <div style="display:flex; align-items:center; gap:8px;">
                <div @click="goToStep(i+1)"
                    :style="step === i+1 ? 'background:#f5a623; color:#0f1117; border-color:#f5a623;' : (step > i+1 ? 'background:#3ecf8e; color:#0f1117; border-color:#3ecf8e;' : 'background:transparent; color:#7a8099; border-color:#2a2f42;')"
                    style="width:36px; height:36px; border-radius:50%; border:2px solid; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; cursor:pointer; transition:all 0.2s;">
                    <span x-show="step <= i+1" x-text="i+1"></span>
                    <span x-show="step > i+1">&#10003;</span>
                </div>
                <span :style="step === i+1 ? 'color:#f5a623;' : (step > i+1 ? 'color:#3ecf8e;' : 'color:#7a8099;')"
                    style="font-size:13px; font-weight:600;" x-text="s"></span>
                <div x-show="i < steps.length - 1" style="width:40px; height:2px; background:#2a2f42;"></div>
            </div>
        </template>
    </div>

    @if($errors->any())
    <div style="background:rgba(224,82,82,0.1); border:1px solid #e05252; border-radius:10px; padding:16px; margin-bottom:24px;">
        <div style="font-weight:600; color:#e05252; margin-bottom:8px;">Please fix the following errors:</div>
        <ul style="list-style:disc; padding-left:20px; color:#e05252; font-size:13px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- STEP 1: Basic Info -->
        <div x-show="step === 1" class="ems-card">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:24px; color:#f5a623;">Basic Information</h3>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
                <div>
                    <label class="ems-label">Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $employee->full_name) }}" class="ems-input" required>
                </div>
                <div>
                    <label class="ems-label">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="ems-input" required>
                </div>
                <div>
                    <label class="ems-label">Contact Number *</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $employee->contact_number) }}" class="ems-input" required>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label class="ems-label">Address Line 1 *</label>
                <input type="text" name="address_line1" value="{{ old('address_line1', $employee->address_line1) }}" class="ems-input" required>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-top:20px;">
                <div>
                    <label class="ems-label">State *</label>
                    <select name="state" class="ems-input" x-model="selectedState" @change="updateCities()" required>
                        <option value="">Select State</option>
                        <template x-for="state in indianStates" :key="state">
                            <option :value="state" x-text="state"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="ems-label">City *</label>
                    <select name="city" class="ems-input" x-model="selectedCity" required>
                        <option value="">Select City</option>
                        <template x-for="city in availableCities" :key="city">
                            <option :value="city" x-text="city"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="ems-label">Pincode *</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $employee->pincode) }}" class="ems-input" maxlength="6" required>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-top:20px;">
                <div>
                    <label class="ems-label">Date of Birth *</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth->format('Y-m-d')) }}" class="ems-input" required>
                </div>
                <div>
                    <label class="ems-label">Marital Status *</label>
                    <select name="marital_status" class="ems-input" required>
                        <option value="">Select</option>
                        @foreach(['single','married','divorced'] as $ms)
                            <option value="{{ $ms }}" {{ old('marital_status', $employee->marital_status) === $ms ? 'selected' : '' }}>{{ ucfirst($ms) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="ems-label">Blood Group *</label>
                    <select name="blood_group" class="ems-input" required>
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', $employee->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label class="ems-label">Photo</label>
                @if($employee->photo)
                <div style="margin-bottom:12px; display:flex; align-items:center; gap:12px;">
                    <img src="{{ $employee->photo_url }}" alt="" style="width:60px; height:60px; border-radius:8px; object-fit:cover;">
                    <span style="font-size:13px; color:#7a8099;">Current photo — upload new to replace</span>
                </div>
                @endif
                <input type="file" name="photo" class="ems-input" accept="image/jpg,image/jpeg,image/png" style="padding:10px;">
            </div>

            <div style="display:flex; justify-content:flex-end; margin-top:28px;">
                <button type="button" @click="step = 2" class="ems-btn" style="display:flex; align-items:center; gap:8px;">
                    Next <span>&rarr;</span>
                </button>
            </div>
        </div>

        <!-- STEP 2: Education -->
        <div x-show="step === 2" class="ems-card">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:24px; color:#f5a623;">Educational Qualification</h3>

            <template x-for="(edu, index) in educations" :key="index">
                <div style="border:1px solid #2a2f42; border-radius:12px; padding:20px; margin-bottom:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                        <span style="font-size:14px; font-weight:600; color:#f5a623;" x-text="'Education #' + (index+1)"></span>
                        <button type="button" x-show="educations.length > 1" @click="educations.splice(index, 1)"
                            style="background:rgba(224,82,82,0.12); color:#e05252; border:none; border-radius:6px; padding:4px 12px; font-size:12px; cursor:pointer; font-family:'DM Sans',sans-serif;">
                            Remove
                        </button>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
                        <div>
                            <label class="ems-label">Degree *</label>
                            <select :name="'educations['+index+'][degree]'" class="ems-input" x-model="edu.degree" required>
                                <option value="">Select Degree</option>
                                <option value="10th">10th</option>
                                <option value="12th">12th</option>
                                <option value="Diploma">Diploma</option>
                                <option value="B.Tech">B.Tech</option>
                                <option value="BCA">BCA</option>
                                <option value="MCA">MCA</option>
                                <option value="MBA">MBA</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="ems-label">Institution Name *</label>
                            <input type="text" :name="'educations['+index+'][institution_name]'" class="ems-input" x-model="edu.institution_name" required>
                        </div>
                        <div>
                            <label class="ems-label">Field of Study *</label>
                            <input type="text" :name="'educations['+index+'][field_of_study]'" class="ems-input" x-model="edu.field_of_study" required>
                        </div>
                        <div>
                            <label class="ems-label">Start Date *</label>
                            <input type="date" :name="'educations['+index+'][start_date]'" class="ems-input" x-model="edu.start_date" required>
                        </div>
                        <div>
                            <label class="ems-label">End Date</label>
                            <input type="date" :name="'educations['+index+'][end_date]'" class="ems-input" x-model="edu.end_date">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="educations.push({degree:'',institution_name:'',field_of_study:'',start_date:'',end_date:''})"
                style="background:rgba(245,166,35,0.08); color:#f5a623; border:1px dashed #f5a623; border-radius:10px; padding:12px 20px; font-size:13px; font-weight:600; cursor:pointer; width:100%; font-family:'DM Sans',sans-serif;">
                + Add More Item
            </button>

            <div style="display:flex; justify-content:space-between; margin-top:28px;">
                <button type="button" @click="step = 1" class="ems-btn-outline" style="display:flex; align-items:center; gap:8px;">
                    <span>&larr;</span> Back
                </button>
                <button type="button" @click="step = 3" class="ems-btn" style="display:flex; align-items:center; gap:8px;">
                    Next <span>&rarr;</span>
                </button>
            </div>
        </div>

        <!-- STEP 3: Previous Employer -->
        <div x-show="step === 3" class="ems-card">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:24px; color:#f5a623;">Previous Employer</h3>

            <template x-for="(emp, index) in employers" :key="index">
                <div style="border:1px solid #2a2f42; border-radius:12px; padding:20px; margin-bottom:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                        <span style="font-size:14px; font-weight:600; color:#f5a623;" x-text="'Employer #' + (index+1)"></span>
                        <button type="button" @click="employers.splice(index, 1)"
                            style="background:rgba(224,82,82,0.12); color:#e05252; border:none; border-radius:6px; padding:4px 12px; font-size:12px; cursor:pointer; font-family:'DM Sans',sans-serif;">
                            Remove
                        </button>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
                        <div>
                            <label class="ems-label">Company Name *</label>
                            <input type="text" :name="'employers['+index+'][company_name]'" class="ems-input" x-model="emp.company_name">
                        </div>
                        <div>
                            <label class="ems-label">HR Name *</label>
                            <input type="text" :name="'employers['+index+'][hr_name]'" class="ems-input" x-model="emp.hr_name">
                        </div>
                        <div>
                            <label class="ems-label">HR Phone *</label>
                            <input type="text" :name="'employers['+index+'][hr_phone]'" class="ems-input" x-model="emp.hr_phone">
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <label class="ems-label">Address</label>
                        <input type="text" :name="'employers['+index+'][address_line1]'" class="ems-input" x-model="emp.address_line1">
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-top:16px;">
                        <div>
                            <label class="ems-label">State</label>
                            <input type="text" :name="'employers['+index+'][state]'" class="ems-input" x-model="emp.state">
                        </div>
                        <div>
                            <label class="ems-label">City</label>
                            <input type="text" :name="'employers['+index+'][city]'" class="ems-input" x-model="emp.city">
                        </div>
                        <div>
                            <label class="ems-label">Pincode</label>
                            <input type="text" :name="'employers['+index+'][pincode]'" class="ems-input" x-model="emp.pincode" maxlength="6">
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-top:16px;">
                        <div>
                            <label class="ems-label">Monthly Salary *</label>
                            <input type="number" :name="'employers['+index+'][monthly_salary]'" class="ems-input" x-model="emp.monthly_salary" step="0.01">
                        </div>
                        <div>
                            <label class="ems-label">Designation *</label>
                            <select :name="'employers['+index+'][designation]'" class="ems-input" x-model="emp.designation">
                                <option value="">Select</option>
                                <option value="Software Developer">Software Developer</option>
                                <option value="Senior Developer">Senior Developer</option>
                                <option value="Team Lead">Team Lead</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="Designer">Designer</option>
                                <option value="QA Engineer">QA Engineer</option>
                                <option value="DevOps Engineer">DevOps Engineer</option>
                                <option value="HR Executive">HR Executive</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="ems-label">Duration *</label>
                            <input type="text" :name="'employers['+index+'][duration_for_working]'" class="ems-input" x-model="emp.duration_for_working">
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <label class="ems-label">Salary Slip</label>
                        <input type="file" :name="'employers['+index+'][salary_slip]'" class="ems-input" accept=".jpg,.jpeg,.png,.pdf" style="padding:10px;">
                    </div>
                </div>
            </template>

            <button type="button" @click="employers.push({company_name:'',hr_name:'',hr_phone:'',address_line1:'',state:'',city:'',pincode:'',monthly_salary:'',designation:'',duration_for_working:''})"
                style="background:rgba(245,166,35,0.08); color:#f5a623; border:1px dashed #f5a623; border-radius:10px; padding:12px 20px; font-size:13px; font-weight:600; cursor:pointer; width:100%; font-family:'DM Sans',sans-serif;">
                + Add More Item
            </button>

            <div style="display:flex; justify-content:space-between; margin-top:28px;">
                <button type="button" @click="step = 2" class="ems-btn-outline" style="display:flex; align-items:center; gap:8px;">
                    <span>&larr;</span> Back
                </button>
                <button type="button" @click="step = 4" class="ems-btn" style="display:flex; align-items:center; gap:8px;">
                    Next <span>&rarr;</span>
                </button>
            </div>
        </div>

        <!-- STEP 4: Bank Details -->
        <div x-show="step === 4" class="ems-card">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:24px; color:#f5a623;">Bank Details</h3>

            <template x-for="(bank, index) in banks" :key="index">
                <div style="border:1px solid #2a2f42; border-radius:12px; padding:20px; margin-bottom:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                        <span style="font-size:14px; font-weight:600; color:#f5a623;" x-text="'Bank #' + (index+1)"></span>
                        <button type="button" x-show="banks.length > 1" @click="banks.splice(index, 1)"
                            style="background:rgba(224,82,82,0.12); color:#e05252; border:none; border-radius:6px; padding:4px 12px; font-size:12px; cursor:pointer; font-family:'DM Sans',sans-serif;">
                            Remove
                        </button>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="ems-label">Account Holder Name *</label>
                        <input type="text" :name="'banks['+index+'][account_holder_name]'" class="ems-input" x-model="bank.account_holder_name" required>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
                        <div>
                            <label class="ems-label">Bank Name *</label>
                            <input type="text" :name="'banks['+index+'][bank_name]'" class="ems-input" x-model="bank.bank_name" required>
                        </div>
                        <div>
                            <label class="ems-label">Account Number *</label>
                            <input type="text" :name="'banks['+index+'][account_number]'" class="ems-input" x-model="bank.account_number" required>
                        </div>
                        <div>
                            <label class="ems-label">IFSC Code *</label>
                            <input type="text" :name="'banks['+index+'][ifsc_code]'" class="ems-input" x-model="bank.ifsc_code" style="text-transform:uppercase;" required>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <label class="ems-label">Passbook / Cheque Photo</label>
                        <input type="file" :name="'banks['+index+'][photo]'" class="ems-input" accept="image/jpg,image/jpeg,image/png" style="padding:10px;">
                    </div>
                </div>
            </template>

            <button type="button" @click="banks.push({account_holder_name:'',bank_name:'',account_number:'',ifsc_code:''})"
                style="background:rgba(245,166,35,0.08); color:#f5a623; border:1px dashed #f5a623; border-radius:10px; padding:12px 20px; font-size:13px; font-weight:600; cursor:pointer; width:100%; font-family:'DM Sans',sans-serif;">
                + Add More Item
            </button>

            <div style="display:flex; justify-content:space-between; margin-top:28px;">
                <button type="button" @click="step = 3" class="ems-btn-outline" style="display:flex; align-items:center; gap:8px;">
                    <span>&larr;</span> Back
                </button>
                <button type="button" @click="step = 5" class="ems-btn" style="display:flex; align-items:center; gap:8px;">
                    Next <span>&rarr;</span>
                </button>
            </div>
        </div>

        <!-- STEP 5: Official Details -->
        <div x-show="step === 5" class="ems-card" x-data="{ showPassword: false }">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:24px; color:#f5a623;">Official Details</h3>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
                <div>
                    <label class="ems-label">Date of Joining *</label>
                    <input type="date" name="official[date_of_joining]" value="{{ old('official.date_of_joining', $employee->officialDetail?->date_of_joining?->format('Y-m-d')) }}" class="ems-input" required>
                </div>
                <div>
                    <label class="ems-label">Designation *</label>
                    <select name="official[designation]" class="ems-input" required>
                        <option value="">Select</option>
                        @foreach(['Software Developer','Senior Developer','Team Lead','Project Manager','Designer','QA Engineer','DevOps Engineer','HR Executive','Intern'] as $d)
                            <option value="{{ $d }}" {{ old('official.designation', $employee->officialDetail?->designation) === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="ems-label">Salary *</label>
                    <input type="number" name="official[salary]" value="{{ old('official.salary', $employee->officialDetail?->salary) }}" class="ems-input" step="0.01" required>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:20px; margin-top:20px;">
                <div>
                    <label class="ems-label">Branch *</label>
                    <select name="official[branch]" class="ems-input" required>
                        <option value="">Select</option>
                        @foreach(['Raipur','Bilaspur','Mumbai'] as $b)
                            <option value="{{ $b }}" {{ old('official.branch', $employee->officialDetail?->branch) === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="ems-label">Password</label>
                    <div style="position:relative;">
                        <input :type="showPassword ? 'text' : 'password'" name="official[password]" class="ems-input" placeholder="Leave blank to keep current" style="padding-right:44px;">
                        <button type="button" @click="showPassword = !showPassword"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#7a8099; cursor:pointer;">
                            <svg x-show="!showPassword" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            <svg x-show="showPassword" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
                        </button>
                    </div>
                    <p style="font-size:11px; color:#7a8099; margin-top:4px;">Leave blank to keep current password</p>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label class="ems-label">Permission</label>
                <textarea name="official[permission]" class="ems-input" rows="3" style="resize:vertical;">{{ old('official.permission', $employee->officialDetail?->permission) }}</textarea>
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:28px;">
                <button type="button" @click="step = 4" class="ems-btn-outline" style="display:flex; align-items:center; gap:8px;">
                    <span>&larr;</span> Back
                </button>
                <button type="submit" class="ems-btn" style="display:flex; align-items:center; gap:8px;">
                    Update &#10003;
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function editEmployeeForm() {
    return {
        step: 1,
        steps: ['Basic Info', 'Education', 'Employer', 'Bank', 'Official'],
        selectedState: '{{ $employee->state }}',
        selectedCity: '{{ $employee->city }}',
        availableCities: [],

        educations: @json($employee->educations->map(fn($e) => [
            'degree' => $e->degree,
            'institution_name' => $e->institution_name,
            'field_of_study' => $e->field_of_study,
            'start_date' => $e->start_date->format('Y-m-d'),
            'end_date' => $e->end_date?->format('Y-m-d') ?? '',
        ])->values()->toArray() ?: [{'degree':'','institution_name':'','field_of_study':'','start_date':'','end_date':''}]),

        employers: @json($employee->previousEmployers->map(fn($e) => [
            'company_name' => $e->company_name,
            'hr_name' => $e->hr_name,
            'hr_phone' => $e->hr_phone,
            'address_line1' => $e->address_line1,
            'state' => $e->state,
            'city' => $e->city,
            'pincode' => $e->pincode,
            'monthly_salary' => $e->monthly_salary,
            'designation' => $e->designation,
            'duration_for_working' => $e->duration_for_working,
        ])->values()->toArray()),

        banks: @json($employee->bankDetails->map(fn($b) => [
            'account_holder_name' => $b->account_holder_name,
            'bank_name' => $b->bank_name,
            'account_number' => $b->account_number,
            'ifsc_code' => $b->ifsc_code,
        ])->values()->toArray() ?: [{'account_holder_name':'','bank_name':'','account_number':'','ifsc_code':''}]),

        indianStates: [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar',
            'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh',
            'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra',
            'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
            'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
            'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
            'Delhi', 'Chandigarh', 'Puducherry'
        ],

        stateCities: {
            'Chhattisgarh': ['Raipur', 'Bilaspur', 'Durg', 'Bhilai', 'Korba', 'Rajnandgaon', 'Jagdalpur', 'Ambikapur'],
            'Maharashtra': ['Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik', 'Aurangabad', 'Solapur', 'Kolhapur'],
            'Delhi': ['New Delhi', 'Delhi'],
            'Karnataka': ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum', 'Gulbarga'],
            'Tamil Nadu': ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Erode'],
            'Uttar Pradesh': ['Lucknow', 'Kanpur', 'Agra', 'Varanasi', 'Noida', 'Ghaziabad', 'Allahabad'],
            'Rajasthan': ['Jaipur', 'Jodhpur', 'Udaipur', 'Kota', 'Ajmer', 'Bikaner'],
            'Gujarat': ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Gandhinagar', 'Bhavnagar'],
            'Madhya Pradesh': ['Bhopal', 'Indore', 'Jabalpur', 'Gwalior', 'Ujjain', 'Sagar'],
            'West Bengal': ['Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri'],
            'Telangana': ['Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar'],
            'Andhra Pradesh': ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Tirupati'],
            'Kerala': ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kannur'],
            'Punjab': ['Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda'],
            'Haryana': ['Gurugram', 'Faridabad', 'Panipat', 'Ambala', 'Karnal'],
            'Bihar': ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Darbhanga'],
            'Jharkhand': ['Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro', 'Hazaribagh'],
            'Odisha': ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur'],
            'Assam': ['Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat'],
            'Chandigarh': ['Chandigarh'],
            'Goa': ['Panaji', 'Margao', 'Vasco da Gama'],
            'Himachal Pradesh': ['Shimla', 'Manali', 'Dharamshala', 'Solan'],
            'Uttarakhand': ['Dehradun', 'Haridwar', 'Rishikesh', 'Nainital'],
            'Sikkim': ['Gangtok', 'Namchi'],
            'Puducherry': ['Puducherry'],
        },

        goToStep(s) {
            this.step = s;
        },

        updateCities() {
            this.availableCities = this.stateCities[this.selectedState] || [];
            if (!this.availableCities.includes(this.selectedCity)) {
                this.selectedCity = '';
            }
        },

        init() {
            this.updateCities();
            if (this.educations.length === 0) {
                this.educations = [{degree:'',institution_name:'',field_of_study:'',start_date:'',end_date:''}];
            }
            if (this.banks.length === 0) {
                this.banks = [{account_holder_name:'',bank_name:'',account_number:'',ifsc_code:''}];
            }
        }
    }
}
</script>
@endpush
@endsection
