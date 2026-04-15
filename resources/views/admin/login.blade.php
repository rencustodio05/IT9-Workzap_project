<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workzap - Admin Login</title>
    @vite(['resources/css/login_register.css', 'resources/js/app.js'])
</head>

<body class="admin-body">
    <div class="admin-wrapper">
        <div class="admin-box">

            <div class="brand">
                <img src="/images/workzap-logo.png" style="width: 32px; height: 40px;">
                <h1><span class="brand-work">Work</span><span class="brand-zap">zap</span> Admin</h1>
            </div>

            @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" required value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Sign in as Admin</button>
            </form>

        </div>
    </div>
</body>

</html>