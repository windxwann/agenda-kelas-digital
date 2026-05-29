<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\ClassHistory;

class AcademicYearAndPromotionTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $student;
    protected $class1;
    protected $class2;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles, permissions, classes, users
        $this->seed();

        $this->admin = User::where('email', 'admin@school.com')->first();
        $this->student = User::where('email', 'siswa@school.com')->first();
        $this->class1 = Classes::where('name', 'XI RPL 1')->first();
        $this->class2 = Classes::where('name', 'XI RPL 2')->first();
    }

    /**
     * Test academic years management.
     */
    public function test_admin_can_manage_academic_years(): void
    {
        // 1. View Index
        $response = $this->actingAs($this->admin)
            ->get('/admin/academic-years');
        $response->assertStatus(200);

        // 2. Store Academic Year
        $response = $this->actingAs($this->admin)
            ->post('/admin/academic-years', [
                'name' => '2025/2026',
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
            ]);
        $response->assertRedirect('/admin/academic-years');
        $response->assertSessionHas('success', 'Tahun Ajaran berhasil ditambahkan.');

        $this->assertDatabaseHas('academic_years', [
            'name' => '2025/2026',
            'is_active' => false,
        ]);

        $newYear = AcademicYear::where('name', '2025/2026')->first();

        // 3. Set Active Academic Year
        $response = $this->actingAs($this->admin)
            ->post("/admin/academic-years/{$newYear->id}/set-active");
        $response->assertRedirect('/admin/academic-years');
        $response->assertSessionHas('success', 'Tahun Ajaran 2025/2026 berhasil diaktifkan.');

        $this->assertTrue($newYear->fresh()->is_active);

        // 4. Update Academic Year
        $response = $this->actingAs($this->admin)
            ->put("/admin/academic-years/{$newYear->id}", [
                'name' => '2025/2026 (Updated)',
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
            ]);
        $response->assertRedirect('/admin/academic-years');
        $response->assertSessionHas('success', 'Tahun Ajaran berhasil diperbarui.');

        $this->assertDatabaseHas('academic_years', [
            'id' => $newYear->id,
            'name' => '2025/2026 (Updated)',
        ]);
    }

    /**
     * Test student promotion index and preview.
     */
    public function test_admin_can_view_promotion_index_and_preview(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/class-promotions');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)
            ->getJson("/admin/class-promotions/preview?class_id={$this->class1->id}");
        $response->assertStatus(200)
            ->assertJsonStructure([
                'students' => [
                    '*' => ['id', 'name', 'nis']
                ]
            ]);
    }

    /**
     * Test student promotion process.
     */
    public function test_admin_can_promote_students(): void
    {
        // 1. Create a target academic year
        $targetYear = AcademicYear::create([
            'name' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_active' => false,
        ]);

        // Deactivate all existing academic years
        AcademicYear::query()->update(['is_active' => false]);

        // 2. Set current active year (source year)
        $sourceYear = AcademicYear::create([
            'name' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
            'is_active' => true,
        ]);

        // 3. Perform promotion
        $response = $this->actingAs($this->admin)
            ->from('/admin/class-promotions')
            ->post('/admin/class-promotions/promote', [
                'source_class_id' => $this->class1->id,
                'target_class_id' => $this->class2->id,
                'target_year_id' => $targetYear->id,
                'student_ids' => [$this->student->id],
            ]);

        $response->assertRedirect('/admin/class-promotions');
        $response->assertSessionHas('success');

        // 4. Verify user class is updated
        $this->assertEquals($this->class2->id, $this->student->fresh()->class_id);

        // 5. Verify class history contains records for both years
        $this->assertDatabaseHas('class_histories', [
            'user_id' => $this->student->id,
            'academic_year_id' => $sourceYear->id,
            'class_id' => $this->class1->id,
        ]);

        $this->assertDatabaseHas('class_histories', [
            'user_id' => $this->student->id,
            'academic_year_id' => $targetYear->id,
            'class_id' => $this->class2->id,
        ]);
    }
}
