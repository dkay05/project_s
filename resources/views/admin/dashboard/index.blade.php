@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<div style="margin-bottom:24px;">
    <p style="color:#7a8099; font-size:14px;">Welcome back! Here's your overview.</p>
</div>

<div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
    <!-- Total Employees -->
    <div class="ems-card" style="display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; background:rgba(245,166,35,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" fill="#f5a623" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </div>
        <div>
            <div style="font-size:28px; font-weight:700; color:#e8eaf0;">{{ $stats['total_employees'] }}</div>
            <div style="font-size:13px; color:#7a8099; font-weight:500;">Total Employees</div>
            <div style="font-size:11px; color:#3ecf8e; margin-top:2px;">Active members</div>
        </div>
    </div>

    <!-- Total Leads -->
    <div class="ems-card" style="display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; background:rgba(91,156,246,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" fill="#5b9cf6" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        </div>
        <div>
            <div style="font-size:28px; font-weight:700; color:#e8eaf0;">{{ $stats['total_leads'] }}</div>
            <div style="font-size:13px; color:#7a8099; font-weight:500;">Total Leads</div>
            <div style="font-size:11px; color:#5b9cf6; margin-top:2px;">All lead entries</div>
        </div>
    </div>

    <!-- Total Qualified -->
    <div class="ems-card" style="display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; background:rgba(62,207,142,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" fill="#3ecf8e" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>
        <div>
            <div style="font-size:28px; font-weight:700; color:#e8eaf0;">{{ $stats['total_qualified'] }}</div>
            <div style="font-size:13px; color:#7a8099; font-weight:500;">Total Qualified</div>
            <div style="font-size:11px; color:#3ecf8e; margin-top:2px;">Qualified leads</div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="ems-card" style="display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; background:rgba(245,166,35,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" fill="#f5a623" viewBox="0 0 24 24"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
        </div>
        <div>
            <div style="font-size:28px; font-weight:700; color:#e8eaf0;">{{ $stats['total_products'] }}</div>
            <div style="font-size:13px; color:#7a8099; font-weight:500;">Total Products</div>
            <div style="font-size:11px; color:#f5a623; margin-top:2px;">Product catalog</div>
        </div>
    </div>

    <!-- Total Lost -->
    <div class="ems-card" style="display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; background:rgba(224,82,82,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" fill="#e05252" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
        </div>
        <div>
            <div style="font-size:28px; font-weight:700; color:#e8eaf0;">{{ $stats['total_lost'] }}</div>
            <div style="font-size:13px; color:#7a8099; font-weight:500;">Total Lost</div>
            <div style="font-size:11px; color:#e05252; margin-top:2px;">Lost opportunities</div>
        </div>
    </div>

    <!-- Total Won -->
    <div class="ems-card" style="display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; background:rgba(62,207,142,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" fill="#3ecf8e" viewBox="0 0 24 24"><path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/></svg>
        </div>
        <div>
            <div style="font-size:28px; font-weight:700; color:#e8eaf0;">{{ $stats['total_won'] }}</div>
            <div style="font-size:13px; color:#7a8099; font-weight:500;">Total Won</div>
            <div style="font-size:11px; color:#3ecf8e; margin-top:2px;">Closed deals</div>
        </div>
    </div>
</div>
@endsection
