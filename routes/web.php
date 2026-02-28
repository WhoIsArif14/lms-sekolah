<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\CourseController as GuruCourse;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\CourseController as AdminCourse;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\DiscussionController; // Tambahkan ini
use App\Http\Controllers\ScheduleController; // Tambahkan ini
use App\Http\Controllers\MaterialController;

// Import Models
use App\Models\User;
use App\Models\Course;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Route Dashboard Utama (Pintu Masuk)
 */
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru'  => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Grup Route Terautentikasi (Harus Login)
 */
Route::middleware('auth')->group(function () {

    // --- AREA DISKUSI (Bisa diakses Admin, Guru, Siswa) ---
    // Pindahkan ke sini agar semua role bisa POST komentar
    Route::post('/courses/{course}/discussion', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::delete('/discussion/{discussion}', [App\Http\Controllers\DiscussionController::class, 'destroy'])->name('discussions.destroy');

    // --- AREA KHUSUS ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            $count_guru = User::where('role', 'guru')->count();
            $count_siswa = User::where('role', 'siswa')->count();
            $count_course = Course::count();
            return view('admin.dashboard', compact('count_guru', 'count_siswa', 'count_course'));
        })->name('dashboard');

        Route::resource('users', AdminUser::class);
        Route::resource('courses', AdminCourse::class)->only(['index', 'destroy']);
    });

    // --- AREA KHUSUS GURU ---
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
        Route::resource('courses', GuruCourse::class);
        // tugas (assignment) CRUD
        Route::post('/courses/{course}/assignments', [GuruCourse::class, 'storeAssignment'])->name('courses.assignments.store');
        // legacy route kept for backwards compatibility (new form uses guru.materials.store)
        Route::post('courses/{course}/material', [GuruCourse::class, 'storeMaterial'])->name('courses.storeMaterial');
        Route::post('/courses/{course}/materials', [MaterialController::class, 'store'])->name('materials.store');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    });

    // --- AREA KHUSUS SISWA ---
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
        Route::get('/courses/{course}', [SiswaDashboard::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/assignments/{assignment}/submit', [SiswaDashboard::class, 'submitAssignment'])->name('courses.assignments.submit');
    });

    // --- AREA PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/jadwal-online', [ScheduleController::class, 'index'])->name('jadwal.index');
});

require __DIR__ . '/auth.php';
