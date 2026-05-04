<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 420px;">
        <h4 class="text-center mb-4 fw-bold">Task Management System</h4>
        <h6 class="text-center text-muted mb-4">Sign in to your account</h6>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <p class="text-center mt-3 mb-0">
            Don't have an account?
            <a href="{{ route('register') }}">Register here</a>
        </p>
    </div>
</div>

</body>
</html>
