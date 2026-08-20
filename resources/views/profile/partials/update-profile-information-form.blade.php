<section class="wb-card p-4 p-md-5">
    <div class="d-flex align-items-start gap-3 mb-4">
        <div class="wb-icon">
            <i class="bi bi-person-lines-fill"></i>
        </div>

        <div>
            <h2 class="h4 fw-bold mb-1">{{ __('Profile Information') }}</h2>
            <p class="wb-muted mb-0">
                {{ __("Update your account information. Your name is locked and can only be changed by an administrator.") }}
            </p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold" for="name">{{ __('Name') }}</label>
                <input id="name"
                       type="text"
                       class="form-control bg-light text-muted"
                       value="{{ $user->name }}"
                       disabled>
                <div class="form-text">
                    {{ __('Please contact admin if your name needs correction.') }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold" for="email">{{ __('Email') }}</label>
                <input id="email"
                       name="email"
                       type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}"
                       required
                       autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning mt-3 mb-0">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="btn btn-link p-0 align-baseline">
                            {{ __('Resend verification email') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-3 mb-0">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        @if($user->role === 'student')
            <div class="border-top pt-4 mt-4">
                <h3 class="h5 fw-bold mb-1">{{ __('Student Details') }}</h3>
                <p class="wb-muted mb-4">
                    {{ __('Keep your student information up to date so your report and counselling request details stay accurate.') }}
                </p>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="matric_no">{{ __('Matric No') }}</label>
                    <input id="matric_no"
                           name="matric_no"
                           type="text"
                           class="form-control @error('matric_no') is-invalid @enderror"
                           value="{{ old('matric_no', $user->studentProfile?->matric_no) }}"
                           maxlength="10"
                           pattern="[A-Z][0-9]{9}"
                           placeholder="B032310177">
                    <div class="form-text">Use 1 capital letter followed by 9 digits.</div>
                    @error('matric_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="program">{{ __('Program') }}</label>
                    <input id="program"
                           name="program"
                           type="text"
                           class="form-control @error('program') is-invalid @enderror"
                           value="{{ old('program', $user->studentProfile?->program) }}">
                    @error('program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="faculty">{{ __('Faculty') }}</label>
                    <input id="faculty"
                           name="faculty"
                           type="text"
                           class="form-control @error('faculty') is-invalid @enderror"
                           value="{{ old('faculty', $user->studentProfile?->faculty) }}">
                    @error('faculty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="year_of_study">{{ __('Year of Study') }}</label>
                    <input id="year_of_study"
                           name="year_of_study"
                           type="number"
                           min="1"
                           max="10"
                           class="form-control @error('year_of_study') is-invalid @enderror"
                           value="{{ old('year_of_study', $user->studentProfile?->year_of_study) }}">
                    @error('year_of_study')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="phone_number">{{ __('Phone Number') }}</label>
                    <input id="phone_number"
                           name="phone_number"
                           type="text"
                           class="form-control @error('phone_number') is-invalid @enderror"
                           value="{{ old('phone_number', $user->studentProfile?->phone_number) }}"
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
                    <label class="form-label fw-semibold" for="gender">{{ __('Gender') }}</label>
                    <select id="gender"
                            name="gender"
                            class="form-select @error('gender') is-invalid @enderror">
                        <option value="">{{ __('Select gender') }}</option>
                        <option value="Male" @selected(old('gender', $user->studentProfile?->gender) === 'Male')>{{ __('Male') }}</option>
                        <option value="Female" @selected(old('gender', $user->studentProfile?->gender) === 'Female')>{{ __('Female') }}</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="date_of_birth">{{ __('Date of Birth') }}</label>
                    <input id="date_of_birth"
                           name="date_of_birth"
                           type="date"
                           class="form-control @error('date_of_birth') is-invalid @enderror"
                           value="{{ old('date_of_birth', $user->studentProfile?->date_of_birth?->format('Y-m-d')) }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        @if($user->role === 'admin')
            <div class="border-top pt-4 mt-4">
                <h3 class="h5 fw-bold mb-1">{{ __('Admin Details') }}</h3>
                <p class="wb-muted mb-4">
                    {{ __('Update your staff information for administration and reporting records.') }}
                </p>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="staff_no">{{ __('Staff No') }}</label>
                    <input id="staff_no"
                           name="staff_no"
                           type="text"
                           class="form-control @error('staff_no') is-invalid @enderror"
                           value="{{ old('staff_no', $user->adminProfile?->staff_no) }}">
                    @error('staff_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="department">{{ __('Department') }}</label>
                    <input id="department"
                           name="department"
                           type="text"
                           class="form-control @error('department') is-invalid @enderror"
                           value="{{ old('department', $user->adminProfile?->department) }}">
                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="position">{{ __('Position') }}</label>
                    <input id="position"
                           name="position"
                           type="text"
                           class="form-control @error('position') is-invalid @enderror"
                           value="{{ old('position', $user->adminProfile?->position) }}">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-wb-primary">
                <i class="bi bi-check2-circle me-2"></i>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success fw-semibold">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
