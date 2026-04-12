@extends('layouts.app')
@section('page-title', 'Employee Detail')

@section('content')
<!-- Profile Header -->
<div class="ems-card" style="display:flex; align-items:center; gap:20px; margin-bottom:24px;">
    <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}"
        style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid #f5a623;">
    <div style="flex:1;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:4px;">
            <h2 style="font-size:22px; font-weight:700;">{{ $employee->full_name }}</h2>
            <span class="status-badge {{ $employee->status === 'active' ? 'active-badge' : 'inactive-badge' }}">
                {{ ucfirst($employee->status) }}
            </span>
        </div>
        <div style="color:#7a8099; font-size:14px;">{{ $employee->email }}</div>
    </div>
    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="ems-btn" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
        Edit
    </a>
</div>

<!-- Basic Info -->
<div class="ems-card" style="margin-bottom:20px;">
    <h3 style="font-size:16px; font-weight:700; color:#f5a623; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #2a2f42;">Basic Information</h3>
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
        <div>
            <div class="ems-label">Full Name</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->full_name }}</div>
        </div>
        <div>
            <div class="ems-label">Email</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->email }}</div>
        </div>
        <div>
            <div class="ems-label">Contact Number</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->contact_number }}</div>
        </div>
        <div>
            <div class="ems-label">Address</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->address_line1 }}</div>
        </div>
        <div>
            <div class="ems-label">City / State</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->city }}, {{ $employee->state }} - {{ $employee->pincode }}</div>
        </div>
        <div>
            <div class="ems-label">Date of Birth</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->date_of_birth->format('d M Y') }}</div>
        </div>
        <div>
            <div class="ems-label">Marital Status</div>
            <div style="font-size:14px; font-weight:500;">{{ ucfirst($employee->marital_status) }}</div>
        </div>
        <div>
            <div class="ems-label">Blood Group</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->blood_group }}</div>
        </div>
        <div>
            <div class="ems-label">Status</div>
            <div style="font-size:14px; font-weight:500;">
                <span class="status-badge {{ $employee->status === 'active' ? 'active-badge' : 'inactive-badge' }}">{{ ucfirst($employee->status) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Education -->
<div class="ems-card" style="margin-bottom:20px;">
    <h3 style="font-size:16px; font-weight:700; color:#f5a623; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #2a2f42;">Educational Qualification</h3>
    @forelse($employee->educations as $edu)
    <div style="border:1px solid #2a2f42; border-radius:10px; padding:16px; margin-bottom:12px;">
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
            <div>
                <div class="ems-label">Degree</div>
                <div style="font-size:14px; font-weight:500;">{{ $edu->degree }}</div>
            </div>
            <div>
                <div class="ems-label">Institution</div>
                <div style="font-size:14px; font-weight:500;">{{ $edu->institution_name }}</div>
            </div>
            <div>
                <div class="ems-label">Field of Study</div>
                <div style="font-size:14px; font-weight:500;">{{ $edu->field_of_study }}</div>
            </div>
            <div>
                <div class="ems-label">Start Date</div>
                <div style="font-size:14px; font-weight:500;">{{ $edu->start_date->format('d M Y') }}</div>
            </div>
            <div>
                <div class="ems-label">End Date</div>
                <div style="font-size:14px; font-weight:500;">{{ $edu->end_date ? $edu->end_date->format('d M Y') : 'Present' }}</div>
            </div>
        </div>
    </div>
    @empty
    <p style="color:#7a8099; font-size:14px;">No education records found.</p>
    @endforelse
</div>

<!-- Previous Employers -->
<div class="ems-card" style="margin-bottom:20px;">
    <h3 style="font-size:16px; font-weight:700; color:#f5a623; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #2a2f42;">Previous Employers</h3>
    @forelse($employee->previousEmployers as $emp)
    <div style="border:1px solid #2a2f42; border-radius:10px; padding:16px; margin-bottom:12px;">
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
            <div>
                <div class="ems-label">Company Name</div>
                <div style="font-size:14px; font-weight:500;">{{ $emp->company_name }}</div>
            </div>
            <div>
                <div class="ems-label">HR Name</div>
                <div style="font-size:14px; font-weight:500;">{{ $emp->hr_name }}</div>
            </div>
            <div>
                <div class="ems-label">HR Phone</div>
                <div style="font-size:14px; font-weight:500;">{{ $emp->hr_phone }}</div>
            </div>
            <div>
                <div class="ems-label">Address</div>
                <div style="font-size:14px; font-weight:500;">{{ $emp->address_line1 }}, {{ $emp->city }}, {{ $emp->state }} - {{ $emp->pincode }}</div>
            </div>
            <div>
                <div class="ems-label">Designation</div>
                <div style="font-size:14px; font-weight:500;">{{ $emp->designation }}</div>
            </div>
            <div>
                <div class="ems-label">Monthly Salary</div>
                <div style="font-size:14px; font-weight:500;">&#8377; {{ number_format($emp->monthly_salary, 2) }}</div>
            </div>
            <div>
                <div class="ems-label">Duration</div>
                <div style="font-size:14px; font-weight:500;">{{ $emp->duration_for_working }}</div>
            </div>
            @if($emp->salary_slip_url)
            <div>
                <div class="ems-label">Salary Slip</div>
                <a href="{{ $emp->salary_slip_url }}" target="_blank" style="color:#5b9cf6; font-size:13px;">View Document</a>
            </div>
            @endif
        </div>
    </div>
    @empty
    <p style="color:#7a8099; font-size:14px;">No previous employer records found.</p>
    @endforelse
</div>

<!-- Bank Details -->
<div class="ems-card" style="margin-bottom:20px;">
    <h3 style="font-size:16px; font-weight:700; color:#f5a623; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #2a2f42;">Bank Details</h3>
    @forelse($employee->bankDetails as $bank)
    <div style="border:1px solid #2a2f42; border-radius:10px; padding:16px; margin-bottom:12px;">
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
            <div>
                <div class="ems-label">Account Holder</div>
                <div style="font-size:14px; font-weight:500;">{{ $bank->account_holder_name }}</div>
            </div>
            <div>
                <div class="ems-label">Bank Name</div>
                <div style="font-size:14px; font-weight:500;">{{ $bank->bank_name }}</div>
            </div>
            <div>
                <div class="ems-label">Account Number</div>
                <div style="font-size:14px; font-weight:500;">{{ $bank->account_number }}</div>
            </div>
            <div>
                <div class="ems-label">IFSC Code</div>
                <div style="font-size:14px; font-weight:500;">{{ $bank->ifsc_code }}</div>
            </div>
            @if($bank->photo_url)
            <div>
                <div class="ems-label">Passbook Photo</div>
                <a href="{{ $bank->photo_url }}" target="_blank" style="color:#5b9cf6; font-size:13px;">View Image</a>
            </div>
            @endif
        </div>
    </div>
    @empty
    <p style="color:#7a8099; font-size:14px;">No bank details found.</p>
    @endforelse
</div>

<!-- Official Details -->
<div class="ems-card">
    <h3 style="font-size:16px; font-weight:700; color:#f5a623; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #2a2f42;">Official Details</h3>
    @if($employee->officialDetail)
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
        <div>
            <div class="ems-label">Date of Joining</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->officialDetail->date_of_joining->format('d M Y') }}</div>
        </div>
        <div>
            <div class="ems-label">Designation</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->officialDetail->designation }}</div>
        </div>
        <div>
            <div class="ems-label">Salary</div>
            <div style="font-size:14px; font-weight:500;">&#8377; {{ number_format($employee->officialDetail->salary, 2) }}</div>
        </div>
        <div>
            <div class="ems-label">Branch</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->officialDetail->branch }}</div>
        </div>
        @if($employee->officialDetail->permission)
        <div>
            <div class="ems-label">Permission</div>
            <div style="font-size:14px; font-weight:500;">{{ $employee->officialDetail->permission }}</div>
        </div>
        @endif
    </div>
    @else
    <p style="color:#7a8099; font-size:14px;">No official details found.</p>
    @endif
</div>

<!-- Back Button -->
<div style="margin-top:24px;">
    <a href="{{ route('admin.employees.index') }}" class="ems-btn-outline" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
        <span>&larr;</span> Back to Employees
    </a>
</div>
@endsection
