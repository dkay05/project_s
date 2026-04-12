<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') — EMS</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: radial-gradient(ellipse at center, #181c27 0%, #0f1117 70%);
            color: #e8eaf0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    @yield('content')

    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: '{{ session("success") }}', timer: 2500, showConfirmButton: false, background: '#181c27', color: '#e8eaf0' });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: '{{ session("error") }}', background: '#181c27', color: '#e8eaf0' });
    </script>
    @endif
</body>
</html>
