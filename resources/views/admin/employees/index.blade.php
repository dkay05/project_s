@extends('layouts.app')
@section('page-title', 'Employees')

@section('content')
<!-- Top Bar -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <form method="GET" action="{{ route('admin.employees.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
            class="ems-input" style="width:280px;">
        <select name="status" class="ems-input" style="width:160px;" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="ems-btn" style="padding:12px 20px;">Search</button>
    </form>
    <a href="{{ route('admin.employees.create') }}" class="ems-btn" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Add Employee
    </a>
</div>

<!-- Table -->
<div class="ems-card" style="padding:0; overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid #2a2f42;">
                    <th style="padding:14px 16px; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600;">S.No</th>
                    <th style="padding:14px 16px; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600;">Employee</th>
                    <th style="padding:14px 16px; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600;">Contact</th>
                    <th style="padding:14px 16px; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600;">Address</th>
                    <th style="padding:14px 16px; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600;">Status</th>
                    <th style="padding:14px 16px; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600;">Created At</th>
                    <th style="padding:14px 16px; text-align:right; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $employee)
                <tr style="border-bottom:1px solid #2a2f42; transition:background 0.15s;" onmouseover="this.style.background='#1e2333'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 16px; font-size:14px;">{{ $employees->firstItem() + $index }}</td>
                    <td style="padding:14px 16px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img src="{{ $employee->photo_url }}" alt="" style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                            <div>
                                <div style="font-weight:600; font-size:14px;">{{ $employee->full_name }}</div>
                                <div style="font-size:12px; color:#7a8099;">{{ $employee->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:14px 16px; font-size:14px;">{{ $employee->contact_number }}</td>
                    <td style="padding:14px 16px; font-size:13px; color:#7a8099; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $employee->city }}, {{ $employee->state }}
                    </td>
                    <td style="padding:14px 16px;">
                        <span class="status-badge {{ $employee->status === 'active' ? 'active-badge' : 'inactive-badge' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </td>
                    <td style="padding:14px 16px; font-size:13px; color:#7a8099;">{{ $employee->created_at->format('d M Y') }}</td>
                    <td style="padding:14px 16px; text-align:right;">
                        <div style="display:flex; gap:8px; justify-content:flex-end;">
                            <button onclick="toggleStatus({{ $employee->id }}, this)"
                                style="background:{{ $employee->status === 'active' ? 'rgba(224,82,82,0.12)' : 'rgba(62,207,142,0.12)' }}; color:{{ $employee->status === 'active' ? '#e05252' : '#3ecf8e' }}; border:none; border-radius:6px; padding:6px 12px; font-size:12px; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif;">
                                {{ $employee->status === 'active' ? 'Block' : 'Unblock' }}
                            </button>
                            <a href="{{ route('admin.employees.show', $employee->id) }}"
                                style="background:rgba(91,156,246,0.12); color:#5b9cf6; border:none; border-radius:6px; padding:6px 12px; font-size:12px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center;">
                                View
                            </a>
                            <a href="{{ route('admin.employees.edit', $employee->id) }}"
                                style="background:rgba(245,166,35,0.12); color:#f5a623; border:none; border-radius:6px; padding:6px 12px; font-size:12px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center;">
                                Edit
                            </a>
                            <form id="delete-form-{{ $employee->id }}" action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-form-{{ $employee->id }}')"
                                    style="background:rgba(224,82,82,0.12); color:#e05252; border:none; border-radius:6px; padding:6px 12px; font-size:12px; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:48px; text-align:center; color:#7a8099;">
                        <svg width="48" height="48" fill="#2a2f42" viewBox="0 0 24 24" style="margin:0 auto 12px;"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        <div style="font-size:16px; font-weight:600; margin-bottom:4px;">No employees found</div>
                        <div style="font-size:13px;">Try adjusting your search or add a new employee.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($employees->hasPages())
<div style="margin-top:20px; display:flex; justify-content:center;">
    <div style="display:flex; gap:4px; align-items:center;">
        @if($employees->onFirstPage())
            <span style="padding:8px 14px; border-radius:8px; background:#181c27; color:#7a8099; font-size:13px;">Previous</span>
        @else
            <a href="{{ $employees->previousPageUrl() }}" style="padding:8px 14px; border-radius:8px; background:#181c27; border:1px solid #2a2f42; color:#e8eaf0; text-decoration:none; font-size:13px; transition:border-color 0.2s;" onmouseover="this.style.borderColor='#f5a623'" onmouseout="this.style.borderColor='#2a2f42'">Previous</a>
        @endif

        @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
            @if($page == $employees->currentPage())
                <span style="padding:8px 14px; border-radius:8px; background:#f5a623; color:#0f1117; font-size:13px; font-weight:600;">{{ $page }}</span>
            @else
                <a href="{{ $url }}" style="padding:8px 14px; border-radius:8px; background:#181c27; border:1px solid #2a2f42; color:#e8eaf0; text-decoration:none; font-size:13px; transition:border-color 0.2s;" onmouseover="this.style.borderColor='#f5a623'" onmouseout="this.style.borderColor='#2a2f42'">{{ $page }}</a>
            @endif
        @endforeach

        @if($employees->hasMorePages())
            <a href="{{ $employees->nextPageUrl() }}" style="padding:8px 14px; border-radius:8px; background:#181c27; border:1px solid #2a2f42; color:#e8eaf0; text-decoration:none; font-size:13px; transition:border-color 0.2s;" onmouseover="this.style.borderColor='#f5a623'" onmouseout="this.style.borderColor='#2a2f42'">Next</a>
        @else
            <span style="padding:8px 14px; border-radius:8px; background:#181c27; color:#7a8099; font-size:13px;">Next</span>
        @endif
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function toggleStatus(employeeId, btn) {
    fetch(`/admin/employees/${employeeId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const badge = btn.closest('tr').querySelector('.status-badge');
        if (data.status === 'active') {
            badge.textContent = 'Active';
            badge.className = 'status-badge active-badge';
            btn.textContent = 'Block';
            btn.style.background = 'rgba(224,82,82,0.12)';
            btn.style.color = '#e05252';
        } else {
            badge.textContent = 'Inactive';
            badge.className = 'status-badge inactive-badge';
            btn.textContent = 'Unblock';
            btn.style.background = 'rgba(62,207,142,0.12)';
            btn.style.color = '#3ecf8e';
        }
        Swal.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false, background: '#181c27', color: '#e8eaf0' });
    });
}

function confirmDelete(formId) {
    Swal.fire({
        title: 'Delete Employee?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e05252',
        cancelButtonColor: '#2a2f42',
        confirmButtonText: 'Yes, delete',
        background: '#181c27',
        color: '#e8eaf0'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>
@endpush
