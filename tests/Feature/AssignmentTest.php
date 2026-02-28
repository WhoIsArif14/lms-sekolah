<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssignmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guru_can_create_assignment_and_student_can_submit()
    {
        Storage::fake('public');

        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);

        // buat kursus milik guru
        $course = Course::create([
            'title' => 'Kursus Contoh',
            'description' => 'Deskripsi',
            'day' => 'senin',
            'time_start' => '08:00',
            'time_end' => '10:00',
            'classroom' => 'A1',
            'user_id' => $guru->id,
        ]);

        // guru membuat tugas
        $this->actingAs($guru)
            ->post(route('guru.courses.assignments.store', $course), [
                'title' => 'Tugas Uji',
                'instruction' => 'Kerjakan soal berikut...',
                'deadline' => now()->addDays(3)->format('Y-m-d H:i'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('assignments', [
            'course_id' => $course->id,
            'title' => 'Tugas Uji',
        ]);

        $assignment = Assignment::first();

        // siswa mengunggah file untuk tugas tersebut
        $this->actingAs($siswa)
            ->post(route('siswa.courses.assignments.submit', [$course, $assignment]), [
                'file' => UploadedFile::fake()->create('jawaban.pdf', 50, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('submissions', [
            'assignment_id' => $assignment->id,
            'user_id' => $siswa->id,
        ]);

        $submission = Submission::first();
        Storage::disk('public')->assertExists($submission->file_path);
    }
}
