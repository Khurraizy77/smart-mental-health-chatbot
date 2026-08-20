<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-person-gear"></i>
                Account settings
            </span>
            <h1 class="fw-bold mb-0">{{ __('Profile') }}</h1>
        </div>
    </x-slot>

    <main class="wb-section">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="col-lg-4">
                    <div class="d-grid gap-4">
                        @include('profile.partials.update-password-form')

                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
