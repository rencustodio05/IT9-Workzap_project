<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workzap - Sign In</title>
    @vite(['resources/css/login_register.css', 'resources/js/app.js'])
</head>

<body>
    <div class="auth-container">

        <div class="left-panel">
            <img src="/images/workzap.webp" alt="Workzap">
        </div>

        <div class="right-panel">
            <div class="form-box">
                <div class="brand">
                    <img src="/images/workzap-logo.png" style="width: 32px; height: 40px;">
                    <h1>Sign in to <span class="brand-work">Work</span><span class="brand-zap">zap</span></h1>
                </div>

                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" name="email" required value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-primary">Sign in</button>
                    <p class="bottom-text">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </form>
            </div>
        </div>

    </div>
</body>

</html>