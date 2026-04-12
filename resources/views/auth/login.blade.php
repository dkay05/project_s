@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div style="width:100%; max-width:420px; padding:20px;">
    <div style="background:#181c27; border:1px solid #2a2f42; border-radius:16px; padding:40px;">
        <!-- Logo -->
        <div style="text-align:center; margin-bottom:32px;">
            <div style="display:inline-flex; align-items:center; gap:10px; margin-bottom:16px;">
                <div style="width:42px; height:42px; background:#f5a623; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <svg width="24" height="24" fill="#0f1117" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <span style="font-size:22px; font-weight:700; color:#e8eaf0;">AdminPanel</span>
            </div>
            <h2 style="font-size:24px; font-weight:700; color:#e8eaf0; margin-bottom:4px;">Welcome back</h2>
            <p style="font-size:14px; color:#7a8099;">Sign in to your admin account</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <!-- Email -->
            <div style="margin-bottom:20px;">
                <label style="font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600; margin-bottom:6px; display:block;">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    style="background:#1e2333; border:1px solid {{ $errors->has('email') ? '#e05252' : '#2a2f42' }}; border-radius:10px; padding:12px 14px; color:#e8eaf0; font-size:14px; width:100%; font-family:'DM Sans',sans-serif; transition:border-color 0.2s;"
                    onfocus="this.style.borderColor='#f5a623'; this.style.boxShadow='0 0 0 3px rgba(245,166,35,0.1)'"
                    onblur="this.style.borderColor='#2a2f42'; this.style.boxShadow='none'"
                    placeholder="admin@gmail.com">
                @error('email')
                    <p style="color:#e05252; font-size:12px; margin-top:6px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div style="margin-bottom:24px;">
                <label style="font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:#7a8099; font-weight:600; margin-bottom:6px; display:block;">Password</label>
                <input type="password" name="password" required
                    style="background:#1e2333; border:1px solid {{ $errors->has('password') ? '#e05252' : '#2a2f42' }}; border-radius:10px; padding:12px 14px; color:#e8eaf0; font-size:14px; width:100%; font-family:'DM Sans',sans-serif; transition:border-color 0.2s;"
                    onfocus="this.style.borderColor='#f5a623'; this.style.boxShadow='0 0 0 3px rgba(245,166,35,0.1)'"
                    onblur="this.style.borderColor='#2a2f42'; this.style.boxShadow='none'"
                    placeholder="Enter your password">
                @error('password')
                    <p style="color:#e05252; font-size:12px; margin-top:6px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember + Forgot -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; color:#7a8099;">
                    <input type="checkbox" name="remember" style="accent-color:#f5a623;">
                    Remember me
                </label>
            </div>

            <!-- Submit -->
            <button type="submit"
                style="width:100%; background:#f5a623; color:#0f1117; border:none; border-radius:10px; padding:14px; font-weight:700; font-size:15px; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background 0.2s;"
                onmouseover="this.style.background='#c47d0e'"
                onmouseout="this.style.background='#f5a623'">
                Login to Dashboard
            </button>
        </form>
    </div>
</div>
@endsection
