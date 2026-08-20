<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<main class="wb-auth-shell">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <a href="/" class="text-decoration-none d-inline-block mb-4">
                    <img src="/images/smart-mental-health-logo.svg"
                         alt="Smart Mental Health Chatbot"
                         class="wb-logo-wordmark">
                </a>

                <h1 class="display-5 fw-bold mb-3">
                    Create your safe space.
                </h1>

                <p class="lead wb-muted mb-0">
                    Start using supportive chat and mood tracking whenever you need a quiet place to reflect.
                </p>
            </div>

            <div class="col-lg-5 ms-lg-auto">
                <div class="wb-card p-4 p-md-5">
                    <div class="mb-4">
                        <div class="wb-icon mb-3">
                            <i class="bi bi-stars"></i>
                        </div>

                        <h2 class="fw-bold mb-2">
                            Create Account
                        </h2>

                        <p class="wb-muted mb-0">
                            Your account helps keep your chat and mood history together.
                        </p>
                    </div>

                    @if(session('google_error'))
                        <div class="alert alert-warning">
                            {{ session('google_error') }}
                        </div>
                    @endif

                    <a href="{{ route('google.redirect') }}"
                       class="btn btn-wb-outline btn-lg w-100 mb-4">
                        <i class="bi bi-google me-2"></i>
                        Continue with Google
                    </a>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <hr class="flex-grow-1">
                        <span class="small wb-muted">or create with email</span>
                        <hr class="flex-grow-1">
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   required
                                   autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="matric_no">Matric No</label>
                                <input type="text"
                                       id="matric_no"
                                       name="matric_no"
                                       class="form-control @error('matric_no') is-invalid @enderror"
                                       value="{{ old('matric_no') }}"
                                       maxlength="10"
                                       pattern="[A-Z][0-9]{9}"
                                       placeholder="B032310177">
                                <div class="form-text">Use 1 capital letter followed by 9 digits.</div>
                                @error('matric_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="program">Program</label>
                                <input type="text"
                                       id="program"
                                       name="program"
                                       class="form-control @error('program') is-invalid @enderror"
                                       value="{{ old('program') }}">
                                @error('program')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="faculty">Faculty</label>
                                <input type="text"
                                       id="faculty"
                                       name="faculty"
                                       class="form-control @error('faculty') is-invalid @enderror"
                                       value="{{ old('faculty') }}">
                                @error('faculty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="year_of_study">Year of Study</label>
                                <input type="number"
                                       min="1"
                                       max="10"
                                       id="year_of_study"
                                       name="year_of_study"
                                       class="form-control @error('year_of_study') is-invalid @enderror"
                                       value="{{ old('year_of_study') }}">
                                @error('year_of_study')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="phone_number">Phone Number</label>
                                <input type="text"
                                       id="phone_number"
                                       name="phone_number"
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       value="{{ old('phone_number') }}"
                                       inputmode="numeric"
                                       maxlength="11"
                                       pattern="[0-9]{10,11}"
                                       placeholder="0115925326">
                                <div class="form-text">Use 10 to 11 digits only.</div>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="gender">Gender</label>
                                <select id="gender"
                                        name="gender"
                                        class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select gender</option>
                                    <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                                    <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="date_of_birth">Date of Birth</label>
                                <input type="date"
                                       id="date_of_birth"
                                       name="date_of_birth"
                                       class="form-control @error('date_of_birth') is-invalid @enderror"
                                       value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required
                                   autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   required
                                   autocomplete="new-password">
                        </div>

                        <button type="submit"
                                class="btn btn-wb-primary btn-lg w-100">
                            Register
                        </button>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}"
                               class="text-decoration-none">
                                Already have an account?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
