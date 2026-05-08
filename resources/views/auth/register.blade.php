<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f6f8fb;
            color: #172033;
        }
        .auth-panel { max-width: 980px; }
        .brand-mark {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #2563eb;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 16px 36px rgba(37, 99, 235, .28);
        }
        .card { border: 1px solid #e6eaf0; border-radius: 1.25rem; box-shadow: 0 20px 60px rgba(15, 23, 42, .08); }
        .form-control { min-height: 2.85rem; border-radius: .8rem; }
        .btn { min-height: 2.85rem; border-radius: .8rem; font-weight: 700; }
        .btn-primary { --bs-btn-bg: #2563eb; --bs-btn-border-color: #2563eb; --bs-btn-hover-bg: #1d4ed8; --bs-btn-hover-border-color: #1d4ed8; }
    </style>
</head>
<body>
<main class="container py-5">
    <div class="auth-panel mx-auto">
        <div class="card">
            <div class="card-body p-4 p-sm-5">
                <div class="text-center mb-4">
                    <span class="brand-mark mb-3"><i class="bi bi-check2-square fs-3"></i></span>
                    <h1 class="h3 fw-bold mb-1">Create your account</h1>
                    <p class="text-muted mb-0">Join the task workspace and start tracking assignments.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0">
                        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please review the form</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input id="name" type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Your name"
                                   required autofocus>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input id="email" type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="you@example.com"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input id="password" type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimum 6 characters"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input id="password_confirmation" type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repeat your password"
                                   required>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>Create Account
                        </button>
                    </div>
                </form>

                <p class="text-center mt-4 mb-0 text-muted">
                    Already have an account?
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Login here</a>
                </p>
            </div>
        </div>
    </div>
</main>
</body>
</html>
