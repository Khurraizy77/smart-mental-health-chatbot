<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Students - Smart Mental Health Chatbot</title>

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
            <a href="/admin/dashboard"
               class="btn btn-wb-outline">
                Back to Dashboard
            </a>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
            <div>
                <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-people"></i>
                    Student records
                </span>

                <h1 class="fw-bold mb-2">
                    Manage Students
                </h1>

                <p class="lead wb-muted mb-0">
                    View, update, and delete student accounts.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="wb-card p-4">
            <div class="table-responsive">
                <table class="table wb-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Matric No</th>
                            <th>Program</th>
                            <th>Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td class="fw-semibold">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->studentProfile?->matric_no ?? '-' }}</td>
                                <td>{{ $student->studentProfile?->program ?? '-' }}</td>
                                <td>{{ $student->created_at?->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="btn btn-sm btn-wb-primary">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.students.destroy', $student) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this student and all related records?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-wb-danger">
                                            <i class="bi bi-trash me-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center wb-muted py-4">
                                    No students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
