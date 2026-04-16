<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workzap - Register</title>
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
                    <h1 class="register-title">Create your account</h1>
                </div>

                @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="role-toggle">
                        <button type="button" onclick="setRole('jobseeker')" id="btn-jobseeker" class="role-btn active">👤 Applicant</button>
                        <span>or</span>
                        <button type="button" onclick="setRole('employer')" id="btn-employer" class="role-btn">🏢 Employer</button>
                        <input type="hidden" name="role" id="role-input" value="jobseeker">
                    </div>

                    <div class="name-row">
                        <div class="form-group">
                            <label>First name</label>
                            <input type="text" name="first_name" required value="{{ old('first_name') }}">
                        </div>
                        <div class="form-group">
                            <label>Last name</label>
                            <input type="text" name="last_name" required value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" name="email" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn-register">Create account</button>

                    <p class="bottom-text">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
                </form>
            </div>
        </div>

    </div>

    <script>
        function setRole(role) {
            document.getElementById('role-input').value = role;
            document.getElementById('btn-jobseeker').classList.toggle('active', role === 'jobseeker');
            document.getElementById('btn-employer').classList.toggle('active', role === 'employer');
        }
    </script>
</body>

</html>