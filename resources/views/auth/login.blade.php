<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management</title>
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
        .auth-panel { max-width: 1080px; }
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
    <div class="auth-panel mx-auto row g-4 align-items-stretch">
        <section class="col-lg-6 d-none d-lg-block">
            <div class="card h-100 overflow-hidden">
                <div class="card-body p-5 d-flex flex-column justify-content-between">
                    <div>
                        <span class="brand-mark mb-4"><i class="bi bi-check2-square fs-3"></i></span>
                        <h1 class="display-6 fw-bold mb-3">Task Management System</h1>
                        <p class="lead text-muted mb-4">A clean workspace for assigning, tracking, and completing team work with confidence.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="rounded-4 border p-3">
                                <i class="bi bi-kanban text-primary fs-4"></i>
                                <div class="fw-bold mt-2">Task boards</div>
                                <div class="text-muted small">Organized work views</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-4 border p-3">
                                <i class="bi bi-shield-check text-success fs-4"></i>
                                <div class="fw-bold mt-2">Secure access</div>
                                <div class="text-muted small">Role based controls</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="col-lg-6">
            <div class="card h-100">
                <div class="card-body p-4 p-sm-5 d-flex flex-column justify-content-center">
                    <div class="text-center mb-4">
                        <span class="brand-mark d-lg-none mb-3"><i class="bi bi-check2-square fs-3"></i></span>
                        <h2 class="h3 fw-bold mb-1">Welcome back</h2>
                        <p class="text-muted mb-0">Sign in to continue to your workspace.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success border-0 d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>{{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input id="email" type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="you@example.com"
                                   required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input id="password" type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter your password"
                                   required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </div>
                    </form>

                    <p class="text-center mt-4 mb-0 text-muted">
                        Do not have an account?
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Register here</a>
                    </p>
                </div>
            </div>
        </section>
    </div>
</main>
</body>
</html>
