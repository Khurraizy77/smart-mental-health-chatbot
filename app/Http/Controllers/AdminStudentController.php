<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminStudentController extends Controller
{
    public function index()
    {
        $students = User::with('studentProfile')
            ->where('role', 'student')
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    public function edit(User $student)
    {
        $this->ensureStudent($student);

        $student->load('studentProfile');

        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        $this->ensureStudent($student);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($student->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'matric_no' => ['nullable', 'regex:/^[A-Z][0-9]{9}$/'],
            'program' => ['nullable', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'year_of_study' => ['nullable', 'integer', 'min:1', 'max:10'],
            'phone_number' => ['nullable', 'regex:/^[0-9]{10,11}$/'],
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'date_of_birth' => ['nullable', 'date'],
        ], [
            'matric_no.regex' => 'The matric number must use one capital letter followed by 9 digits, for example B032310177.',
            'phone_number.regex' => 'The phone number must contain 10 to 11 digits only, for example 0115925326.',
        ]);

        $student->name = $validated['name'];
        $student->email = $validated['email'];

        if (! empty($validated['password'])) {
            $student->password = Hash::make($validated['password']);
        }

        $student->role = 'student';
        $student->save();

        $student->studentProfile()->updateOrCreate(
            ['user_id' => $student->id],
            [
                'matric_no' => $validated['matric_no'] ?? null,
                'program' => $validated['program'] ?? null,
                'faculty' => $validated['faculty'] ?? null,
                'year_of_study' => $validated['year_of_study'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
            ]
        );

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student information updated successfully.');
    }

    public function destroy(User $student)
    {
        $this->ensureStudent($student);

        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    private function ensureStudent(User $student): void
    {
        abort_unless($student->role === 'student', 404);
    }
}
