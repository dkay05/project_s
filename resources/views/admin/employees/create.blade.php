@extends('layouts.app')
@section('page-title', 'Add Employee')

@section('content')
<div x-data="employeeForm()" x-cloak>
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
                    style="font-size:13px; font-weight:600; display:none;"
                    :class="{ 'sm:inline': true }" x-text="s"></span>
                <div x-show="i < steps.length - 1" style="width:40px; height:2px; background:#2a2f42;"></div>
            </div>
        </template>
    </div>

    <!-- Validation Errors -->
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

    <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- STEP 1: Basic Info -->
        <div x-show="step === 1" class="ems-card">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:24px; color:#f5a623;">Basic Information</h3>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
                <div>
                    <label class="ems-label">Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" class="ems-input" placeholder="Enter full name" required>
                </div>
                <div>
                    <label class="ems-label">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="ems-input" placeholder="employee@email.com" required>
                </div>
                <div>
                    <label class="ems-label">Contact Number *</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="ems-input" placeholder="10-digit number" required>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label class="ems-label">Address Line 1 *</label>
                <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="ems-input" placeholder="Street address" required>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-top:20px;">
                <div>
                    <label class="ems-label">State *</label>
                    <select name="state" class="ems-input" x-model="selectedState" @change="updateCities()" required>
                        <option value="">Select State</option>
                        <template x-for="state in indianStates" :key="state">
                            <option :value="state" x-text="state" :selected="state === '{{ old('state') }}'"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="ems-label">City *</label>
                    <select name="city" class="ems-input" required>
                        <option value="">Select City</option>
                        <template x-for="city in availableCities" :key="city">
                            <option :value="city" x-text="city" :selected="city === '{{ old('city') }}'"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="ems-label">Pincode *</label>
                    <input type="text" name="pincode" value="{{ old('pincode') }}" class="ems-input" placeholder="6-digit pincode" maxlength="6" required>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-top:20px;">
                <div>
                    <label class="ems-label">Date of Birth *</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="ems-input" required>
                </div>
                <div>
                    <label class="ems-label">Marital Status *</label>
                    <select name="marital_status" class="ems-input" required>
                        <option value="">Select</option>
                        <option value="single" {{ old('marital_status') === 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('marital_status') === 'married' ? 'selected' : '' }}>Married</option>
                        <option value="divorced" {{ old('marital_status') === 'divorced' ? 'selected' : '' }}>Divorced</option>
                    </select>
                </div>
                <div>
                    <label class="ems-label">Blood Group *</label>
                    <select name="blood_group" class="ems-input" required>
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label class="ems-label">Photo</label>
                <div style="border:2px dashed #2a2f42; border-radius:10px; padding:32px; text-align:center; cursor:pointer; transition:border-color 0.2s;"
                    onclick="document.getElementById('photo-input').click()"
                    onmouseover="this.style.borderColor='#f5a623'"
                    onmouseout="this.style.borderColor='#2a2f42'">
                    <svg width="32" height="32" fill="#7a8099" viewBox="0 0 24 24" style="margin:0 auto 8px;"><path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V8h-3V5H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h-3zM5 19l3-4 2 3 3-4 4 5H5z"/></svg>
                    <div style="color:#7a8099; font-size:13px;">Upload a file</div>
                    <div style="color:#7a8099; font-size:11px; margin-top:2px;">Max: 2MB (JPG, JPEG, PNG)</div>
                </div>
                <input type="file" name="photo" id="photo-input" accept="image/jpg,image/jpeg,image/png" style="display:none;">
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
                <div style="border:1px solid #2a2f42; border-radius:12px; padding:20px; margin-bottom:16px; position:relative;">
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
                            <select :name="'educations['+index+'][degree]'" class="ems-input" required>
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
                            <input type="text" :name="'educations['+index+'][institution_name]'" class="ems-input" placeholder="Institution name" required>
                        </div>
                        <div>
                            <label class="ems-label">Field of Study *</label>
                            <input type="text" :name="'educations['+index+'][field_of_study]'" class="ems-input" placeholder="Field of study" required>
                        </div>
                        <div>
                            <label class="ems-label">Start Date *</label>
                            <input type="date" :name="'educations['+index+'][start_date]'" class="ems-input" required>
                        </div>
                        <div>
                            <label class="ems-label">End Date</label>
                            <input type="date" :name="'educations['+index+'][end_date]'" class="ems-input">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="educations.push({})"
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
                <div style="border:1px solid #2a2f42; border-radius:12px; padding:20px; margin-bottom:16px; position:relative;">
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
                            <input type="text" :name="'employers['+index+'][company_name]'" class="ems-input" placeholder="Company name">
                        </div>
                        <div>
                            <label class="ems-label">HR Name *</label>
                            <input type="text" :name="'employers['+index+'][hr_name]'" class="ems-input" placeholder="HR name">
                        </div>
                        <div>
                            <label class="ems-label">HR Phone *</label>
                            <input type="text" :name="'employers['+index+'][hr_phone]'" class="ems-input" placeholder="HR phone number">
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <label class="ems-label">Address</label>
                        <input type="text" :name="'employers['+index+'][address_line1]'" class="ems-input" placeholder="Company address">
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-top:16px;">
                        <div>
                            <label class="ems-label">State</label>
                            <select :name="'employers['+index+'][state]'" class="ems-input">
                                <option value="">Select State</option>
                                <template x-for="state in indianStates" :key="'emp-state-'+index+'-'+state">
                                    <option :value="state" x-text="state"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="ems-label">City</label>
                            <input type="text" :name="'employers['+index+'][city]'" class="ems-input" placeholder="City">
                        </div>
                        <div>
                            <label class="ems-label">Pincode</label>
                            <input type="text" :name="'employers['+index+'][pincode]'" class="ems-input" placeholder="Pincode" maxlength="6">
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-top:16px;">
                        <div>
                            <label class="ems-label">Monthly Salary *</label>
                            <input type="number" :name="'employers['+index+'][monthly_salary]'" class="ems-input" placeholder="0.00" step="0.01">
                        </div>
                        <div>
                            <label class="ems-label">Designation *</label>
                            <select :name="'employers['+index+'][designation]'" class="ems-input">
                                <option value="">Select Designation</option>
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
                            <label class="ems-label">Duration of Working *</label>
                            <input type="text" :name="'employers['+index+'][duration_for_working]'" class="ems-input" placeholder="e.g. 2 years 3 months">
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <label class="ems-label">Salary Slip</label>
                        <input type="file" :name="'employers['+index+'][salary_slip]'" class="ems-input" accept=".jpg,.jpeg,.png,.pdf" style="padding:10px;">
                    </div>
                </div>
            </template>

            <button type="button" @click="employers.push({})"
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
                <div style="border:1px solid #2a2f42; border-radius:12px; padding:20px; margin-bottom:16px; position:relative;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                        <span style="font-size:14px; font-weight:600; color:#f5a623;" x-text="'Bank #' + (index+1)"></span>
                        <button type="button" x-show="banks.length > 1" @click="banks.splice(index, 1)"
                            style="background:rgba(224,82,82,0.12); color:#e05252; border:none; border-radius:6px; padding:4px 12px; font-size:12px; cursor:pointer; font-family:'DM Sans',sans-serif;">
                            Remove
                        </button>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="ems-label">Account Holder Name *</label>
                        <input type="text" :name="'banks['+index+'][account_holder_name]'" class="ems-input" placeholder="Account holder name" required>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
                        <div>
                            <label class="ems-label">Bank Name *</label>
                            <input type="text" :name="'banks['+index+'][bank_name]'" class="ems-input" placeholder="Bank name" required>
                        </div>
                        <div>
                            <label class="ems-label">Account Number *</label>
                            <input type="text" :name="'banks['+index+'][account_number]'" class="ems-input" placeholder="Account number" required>
                        </div>
                        <div>
                            <label class="ems-label">IFSC Code *</label>
                            <input type="text" :name="'banks['+index+'][ifsc_code]'" class="ems-input" placeholder="e.g. SBIN0001234" style="text-transform:uppercase;" required>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <label class="ems-label">Passbook / Cheque Photo</label>
                        <input type="file" :name="'banks['+index+'][photo]'" class="ems-input" accept="image/jpg,image/jpeg,image/png" style="padding:10px;">
                    </div>
                </div>
            </template>

            <button type="button" @click="banks.push({})"
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
                    <input type="date" name="official[date_of_joining]" value="{{ old('official.date_of_joining') }}" class="ems-input" required>
                </div>
                <div>
                    <label class="ems-label">Designation *</label>
                    <select name="official[designation]" class="ems-input" required>
                        <option value="">Select Designation</option>
                        <option value="Software Developer" {{ old('official.designation') === 'Software Developer' ? 'selected' : '' }}>Software Developer</option>
                        <option value="Senior Developer" {{ old('official.designation') === 'Senior Developer' ? 'selected' : '' }}>Senior Developer</option>
                        <option value="Team Lead" {{ old('official.designation') === 'Team Lead' ? 'selected' : '' }}>Team Lead</option>
                        <option value="Project Manager" {{ old('official.designation') === 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                        <option value="Designer" {{ old('official.designation') === 'Designer' ? 'selected' : '' }}>Designer</option>
                        <option value="QA Engineer" {{ old('official.designation') === 'QA Engineer' ? 'selected' : '' }}>QA Engineer</option>
                        <option value="DevOps Engineer" {{ old('official.designation') === 'DevOps Engineer' ? 'selected' : '' }}>DevOps Engineer</option>
                        <option value="HR Executive" {{ old('official.designation') === 'HR Executive' ? 'selected' : '' }}>HR Executive</option>
                        <option value="Intern" {{ old('official.designation') === 'Intern' ? 'selected' : '' }}>Intern</option>
                    </select>
                </div>
                <div>
                    <label class="ems-label">Salary *</label>
                    <input type="number" name="official[salary]" value="{{ old('official.salary') }}" class="ems-input" placeholder="0.00" step="0.01" required>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:20px; margin-top:20px;">
                <div>
                    <label class="ems-label">Branch *</label>
                    <select name="official[branch]" class="ems-input" required>
                        <option value="">Select Branch</option>
                        <option value="Raipur" {{ old('official.branch') === 'Raipur' ? 'selected' : '' }}>Raipur</option>
                        <option value="Bilaspur" {{ old('official.branch') === 'Bilaspur' ? 'selected' : '' }}>Bilaspur</option>
                        <option value="Mumbai" {{ old('official.branch') === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                    </select>
                </div>
                <div>
                    <label class="ems-label">Password *</label>
                    <div style="position:relative;">
                        <input :type="showPassword ? 'text' : 'password'" name="official[password]" class="ems-input" placeholder="Minimum 8 characters" style="padding-right:44px;" required>
                        <button type="button" @click="showPassword = !showPassword"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#7a8099; cursor:pointer;">
                            <svg x-show="!showPassword" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            <svg x-show="showPassword" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label class="ems-label">Permission</label>
                <textarea name="official[permission]" class="ems-input" rows="3" placeholder="Enter permissions (optional)" style="resize:vertical;">{{ old('official.permission') }}</textarea>
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:28px;">
                <button type="button" @click="step = 4" class="ems-btn-outline" style="display:flex; align-items:center; gap:8px;">
                    <span>&larr;</span> Back
                </button>
                <button type="submit" class="ems-btn" style="display:flex; align-items:center; gap:8px;">
                    Submit &#10003;
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function employeeForm() {
    return {
        step: 1,
        steps: ['Basic Info', 'Education', 'Employer', 'Bank', 'Official'],
        educations: [{}],
        employers: [],
        banks: [{}],
        selectedState: '{{ old("state", "") }}',
        availableCities: [],

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
        },

        init() {
            if (this.selectedState) {
                this.updateCities();
            }
        }
    }
}
</script>
@endpush
@endsection
