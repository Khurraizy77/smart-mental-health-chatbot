<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Student - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<nav class="navbar navbar-expand-lg wb-nav sticky-top">
    <div class="container">
        <a class="navbar-brand wb-brand" href="/admin/dashboard">
            Admin Dashboard
        </a>

        <div class="ms-auto">
            <a href="{{ route('admin.students.index') }}"
               class="btn btn-wb-outline">
                Back to Students
            </a>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="mb-4">
                    <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-pencil-square"></i>
                        Student account
                    </span>

                    <h1 class="fw-bold mb-2">
                        Edit Student
                    </h1>

                    <p class="lead wb-muted mb-0">
                        Update account details for {{ $student->name }}.
                    </p>
                </div>

                <div class="wb-card p-4 p-md-5">
                    <form method="POST"
                          action="{{ route('admin.students.update', $student) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name"
                                   class="form-label">
                                Name
                            </label>

                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $student->name) }}"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email"
                                   class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $student->email) }}"
                                   required>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password"
                                   class="form-label">
                                New Password
                            </label>

                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   autocomplete="new-password">

                            <div class="form-text">
                                Leave blank to keep the current password.
                            </div>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="matric_no" class="form-label">Matric No</label>
                                <input type="text"
                                       id="matric_no"
                                       name="matric_no"
                                       class="form-control @error('matric_no') is-invalid @enderror"
                                       value="{{ old('matric_no', $student->studentProfile?->matric_no) }}"
                                       maxlength="10"
                                       pattern="[A-Z][0-9]{9}"
                                       placeholder="B032310177">
                                <div class="form-text">Use 1 capital letter followed by 9 digits.</div>
                                @error('matric_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="program" class="form-label">Program</label>
                                <input type="text"
                                       id="program"
                                       name="program"
                                       class="form-control @error('program') is-invalid @enderror"
                                       value="{{ old('program', $student->studentProfile?->program) }}">
                                @error('program')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="faculty" class="form-label">Faculty</label>
                                <input type="text"
                                       id="faculty"
                                       name="faculty"
                                       class="form-control @error('faculty') is-invalid @enderror"
                                       value="{{ old('faculty', $student->studentProfile?->faculty) }}">
                                @error('faculty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="year_of_study" class="form-label">Year of Study</label>
                                <input type="number"
                                       min="1"
                                       max="10"
                                       id="year_of_study"
                                       name="year_of_study"
                                       class="form-control @error('year_of_study') is-invalid @enderror"
                                       value="{{ old('year_of_study', $student->studentProfile?->year_of_study) }}">
                                @error('year_of_study')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text"
                                       id="phone_number"
                                       name="phone_number"
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       value="{{ old('phone_number', $student->studentProfile?->phone_number) }}"
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
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender"
                                        name="gender"
                                        class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select gender</option>
                                    <option value="Male" @selected(old('gender', $student->studentProfile?->gender) === 'Male')>Male</option>
                                    <option value="Female" @selected(old('gender', $student->studentProfile?->gender) === 'Female')>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date"
                                       id="date_of_birth"
                                       name="date_of_birth"
                                       class="form-control @error('date_of_birth') is-invalid @enderror"
                                       value="{{ old('date_of_birth', $student->studentProfile?->date_of_birth?->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation"
                                   class="form-label">
                                Confirm New Password
                            </label>

                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   autocomplete="new-password">
                        </div>

                        <button type="submit"
                                class="btn btn-wb-primary">
                            Update Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
