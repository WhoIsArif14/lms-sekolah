<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaterialUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guru_can_upload_file_material()
    {
        Storage::fake('public');

        $guru = User::factory()->create(['role' => 'guru']);
        // buat kursus secara manual karena tidak ada factory untuk Course
        $course = Course::create([
            'title' => 'Contoh Kursus',
            'description' => 'Deskripsi',
            'day' => 'senin',
            'time_start' => '08:00',
            'time_end' => '10:00',
            'classroom' => 'A1',
            'user_id' => $guru->id,
        ]);

        $this->actingAs($guru)
            ->post(route('guru.materials.store', $course), [
                'title' => 'Dokumen Uji',
                'type' => 'file',
                'file_content' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('materials', [
            'course_id' => $course->id,
            'title' => 'Dokumen Uji',
            'type' => 'file',
        ]);

        $material = Material::first();
        Storage::disk('public')->assertExists($material->content);
    }

    /** @test */
    public function guru_can_save_video_link_material()
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $course = Course::create([
            'title' => 'Contoh Kursus',
            'description' => 'Deskripsi',
            'day' => 'senin',
            'time_start' => '08:00',
            'time_end' => '10:00',
            'classroom' => 'A1',
            'user_id' => $guru->id,
        ]);

        $this->actingAs($guru)
            ->post(route('guru.materials.store', $course), [
                'title' => 'Link Youtube',
                'type' => 'video_link',
                'link_content' => 'https://www.youtube.com/watch?v=abc123',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('materials', [
            'course_id' => $course->id,
            'title' => 'Link Youtube',
            'type' => 'video_link',
            'content' => 'https://www.youtube.com/watch?v=abc123',
        ]);
    }
}
