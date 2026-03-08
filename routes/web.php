<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Guru\AssignmentController;

// Import Controllers by Role
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\CourseController as GuruCourse;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\CourseController as AdminCourse;
use App\Http\Controllers\Admin\ImportController as ImportController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendance;
use App\Http\Controllers\Admin\ClassController as ClassController;
use App\Http\Controllers\Guru\ExamController as ExamController;
use App\Http\Controllers\Siswa\ExamController as SiswaExamController;
use App\Http\Controllers\Ortu\ParentController as ParentController;

// Import Models
use App\Models\User;
use App\Models\Course;

Route::get('/', function () {
    // metrics untuk halaman utama (belum diautentikasi)
    // rata‑rata skor ujian seluruh siswa
    $avgAcademic = \App\Models\ExamResponse::query()->avg('score') ?: 0;

    // tingkat kehadiran: persentase record dengan status "present"
    $totalAttendance = \App\Models\Attendance::count();
    $presentCount = $totalAttendance
        ? \App\Models\Attendance::where('status', 'present')->count()
        : 0;
    $attendanceRate = $totalAttendance ? ($presentCount / $totalAttendance) * 100 : 0;

    // jumlah siswa dengan nilai di bawah KKM (anggap KKM = 75)
    $kkm = 75;
    $lowScoreCount = \App\Models\ExamResponse::where('score', '<', $kkm)
        ->distinct('user_id')
        ->count();

    // jumlah siswa yang sering absen (paling sederhana: pernah absen)
    $lowAttendanceCount = \App\Models\Attendance::where('status', '!=', 'present')
        ->distinct('user_id')
        ->count();

    return view('welcome', compact(
        'avgAcademic',
        'attendanceRate',
        'lowScoreCount',
        'lowAttendanceCount'
    ));
});

/**
 * Route Dashboard Utama (Auto-Redirect berdasarkan Role)
 */
Route::get('/dashboard', function () {
    if (!Auth::check()) return redirect()->route('login');

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru'  => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        'ortu'  => redirect()->route('ortu.dashboard'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Grup Route Terautentikasi
 */
Route::middleware('auth')->group(function () {

    // --- AREA UMUM (Diskusi & Jadwal) ---
    Route::post('/courses/{course}/discussion', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::delete('/discussion/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');
    Route::get('/jadwal-online', [ScheduleController::class, 'index'])->name('jadwal.index');

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
        Route::get('/attendance', [AdminAttendance::class, 'index'])->name('attendance.index');
        Route::post('/attendance/update', [AdminAttendance::class, 'updateSetting'])->name('attendance.update');
        Route::get('/attendance/classes', [AdminAttendance::class, 'index'])->name('attendance.classes');
        Route::get('/attendance/classes/{id}', [AdminAttendance::class, 'showClass'])->name('attendance.class');

        // Menu Manajemen Kelas
        Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
        Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
        Route::delete('/classes/{class}', [ClassController::class, 'destroy'])->name('classes.destroy');

        // import siswa/ortu per kelas
        Route::post('/classes/{id}/import', [ClassController::class, 'importSiswa'])->name('classes.import.siswa');
        Route::post('/classes/{id}/import-parents', [ClassController::class, 'importParents'])->name('classes.import.parents');

        Route::get('/classes/{id}', [ClassController::class, 'show'])->name('classes.show');

        // Menu Import Data
        Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
        Route::post('/imports/students', [ImportController::class, 'importStudents'])->name('imports.students');
        Route::post('/imports/parents', [ImportController::class, 'importParents'])->name('imports.parents');
        Route::get('/imports/template/students', [ImportController::class, 'downloadStudentTemplate'])->name('imports.template.students');
        Route::get('/imports/template/parents', [ImportController::class, 'downloadParentTemplate'])->name('imports.template.parents');
    });

    // --- AREA KHUSUS GURU ---
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

        // Course Management
        Route::resource('courses', GuruCourse::class);

        // Material Management (Disederhanakan)
        Route::post('/courses/{course}/materials', [MaterialController::class, 'store'])->name('materials.store');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');

        // Assignment Management (CRUD Global & Specific)
        Route::resource('assignments', AssignmentController::class);
        // Rute khusus jika Guru ingin buat tugas LANGSUNG dari dalam halaman detail Kursus
        Route::post('/courses/{course}/assignments', [AssignmentController::class, 'store'])->name('courses.assignments.store');

        Route::get('/course/{courseId}/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::post('/course/{courseId}/exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/exams/{id}', [ExamController::class, 'show'])->name('exams.show');
        Route::post('/exams/{id}/questions', [ExamController::class, 'storeQuestion'])->name('exams.questions.store');
        Route::post('/exams/{id}/toggle', [ExamController::class, 'toggleStatus'])->name('exams.toggle');
    });

    // --- AREA KHUSUS SISWA ---
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
        Route::get('/courses/{course}', [SiswaDashboard::class, 'show'])->name('courses.show');
        Route::get('/attendance', [SiswaDashboard::class, 'attendanceIndex'])->name('attendance.index');
        Route::post('/attendance/store', [SiswaDashboard::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/assignments', [SiswaDashboard::class, 'indexAssignments'])->name('assignments.index');
        Route::post('/assignments/{assignment}/submit', [SiswaDashboard::class, 'submitAssignment'])->name('assignments.submit');
        Route::post('/exams/{id}/submit', [SiswaExamController::class, 'submit'])->name('siswa.exams.submit');
        Route::post('/exams/{id}/submit', [SiswaExamController::class, 'submit'])->name('exams.submit');
    });

    // --- AREA PROFILE ---
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::middleware(['auth', 'role:ortu'])->prefix('ortu')->name('ortu.')->group(function () {
        Route::get('/dashboard', [ParentController::class, 'index'])->name('dashboard');
        Route::get('/child/{id}', [ParentController::class, 'showChildActivity'])->name('child.detail');
        Route::get('/notifications', [ParentController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [ParentController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [ParentController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
    });
});

require __DIR__ . '/auth.php';
