<?php

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

/** @return array{superAdmin: User, unit: Unit, adminUnit: User} */
function setupSuperAdminAndUnit(): array
{
    $superAdminRole = Role::factory()->create(['name' => 'Administrator Utama']);
    $adminUnitRole = Role::factory()->create(['name' => 'Administrator Unit']);
    Role::factory()->create(['name' => 'Peminjam']);
    Role::factory()->create(['name' => 'Penyetuju']);

    $unit = Unit::factory()->create(['level' => 'Jurusan', 'unit_name' => 'Jurusan TI', 'parent_id' => null]);

    $superAdmin = User::factory()->create([
        'role_id' => $superAdminRole->id,
        'unit_id' => $unit->id,
        'name' => 'SuperAdmin Test',
    ]);

    $adminUnit = User::factory()->create([
        'role_id' => $adminUnitRole->id,
        'unit_id' => $unit->id,
        'name' => 'Admin Unit Test',
    ]);

    return compact('superAdmin', 'unit', 'adminUnit');
}

uses(RefreshDatabase::class);

// ──────────────────────────────────────────────────────────────────────────────
// Authorization
// ──────────────────────────────────────────────────────────────────────────────

test('unauthenticated user cannot access users API', function () {
    getJson('/admin/api/users')->assertStatus(401);
});

test('authenticated SuperAdmin can list users', function () {
    ['superAdmin' => $superAdmin] = setupSuperAdminAndUnit();

    actingAs($superAdmin)
        ->getJson('/admin/api/users')
        ->assertOk()
        ->assertJsonPath('success', true);
});

// ──────────────────────────────────────────────────────────────────────────────
// Pagination
// ──────────────────────────────────────────────────────────────────────────────

test('users list is paginated to 10 items per page', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->count(15)->create(['unit_id' => $unit->id, 'role_id' => $userRole->id]);

    $response = actingAs($superAdmin)
        ->getJson('/admin/api/users?page=1')
        ->assertOk();

    expect($response->json('data.per_page'))->toBe(10);
    expect(count($response->json('data.data')))->toBeLessThanOrEqual(10);
    expect($response->json('data.last_page'))->toBeGreaterThan(1);
});

test('second page returns different users', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->count(15)->create(['unit_id' => $unit->id, 'role_id' => $userRole->id]);

    $page1Ids = actingAs($superAdmin)
        ->getJson('/admin/api/users?page=1')
        ->json('data.data.*.id');

    $page2Ids = actingAs($superAdmin)
        ->getJson('/admin/api/users?page=2')
        ->json('data.data.*.id');

    expect(array_intersect($page1Ids, $page2Ids))->toBeEmpty();
});

// ──────────────────────────────────────────────────────────────────────────────
// Search
// ──────────────────────────────────────────────────────────────────────────────

test('search filters users by name', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->create(['name' => 'Alice Wonder', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Bob Builder', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);

    $response = actingAs($superAdmin)
        ->getJson('/admin/api/users?search=Alice')
        ->assertOk();

    $names = collect($response->json('data.data'))->pluck('name');
    expect($names)->toContain('Alice Wonder');
    expect($names)->not->toContain('Bob Builder');
});

test('search returns empty when no match found', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->create(['name' => 'Charlie Delta', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);

    $response = actingAs($superAdmin)
        ->getJson('/admin/api/users?search=zzznomatch')
        ->assertOk();

    expect($response->json('data.data'))->toBeEmpty();
});

// ──────────────────────────────────────────────────────────────────────────────
// Filter by Unit
// ──────────────────────────────────────────────────────────────────────────────

test('filter by unit_id returns only users in that unit', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $otherUnit = Unit::factory()->create(['level' => 'Jurusan', 'unit_name' => 'Jurusan Sipil', 'parent_id' => null]);
    $userRole = Role::where('name', 'Peminjam')->first();

    User::factory()->create(['name' => 'In Unit', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Other Unit', 'unit_id' => $otherUnit->id, 'role_id' => $userRole->id]);

    $response = actingAs($superAdmin)
        ->getJson("/admin/api/users?unit_id={$unit->id}")
        ->assertOk();

    $unitIds = collect($response->json('data.data'))->pluck('unit_id')->unique()->values();
    expect($unitIds->all())->toEqual([$unit->id]);
});

// ──────────────────────────────────────────────────────────────────────────────
// Filter by Level
// ──────────────────────────────────────────────────────────────────────────────

test('filter by level returns users belonging to units of that level', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $orgUnit = Unit::factory()->create(['level' => 'Organisasi', 'parent_id' => $unit->id, 'unit_name' => 'HMTI']);
    $userRole = Role::where('name', 'Peminjam')->first();

    User::factory()->create(['name' => 'Jurusan User', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Org User', 'unit_id' => $orgUnit->id, 'role_id' => $userRole->id]);

    $response = actingAs($superAdmin)
        ->getJson('/admin/api/users?level=Organisasi')
        ->assertOk();

    $names = collect($response->json('data.data'))->pluck('name');
    expect($names)->toContain('Org User');
    expect($names)->not->toContain('Jurusan User');
});

// ──────────────────────────────────────────────────────────────────────────────
// Filter by Role
// ──────────────────────────────────────────────────────────────────────────────

test('filter by role_id returns only users with that role', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    $approverRole = Role::where('name', 'Penyetuju')->first();

    User::factory()->create(['name' => 'Regular User', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Approver Guy', 'unit_id' => $unit->id, 'role_id' => $approverRole->id]);

    $response = actingAs($superAdmin)
        ->getJson("/admin/api/users?role_id={$approverRole->id}")
        ->assertOk();

    $names = collect($response->json('data.data'))->pluck('name');
    expect($names)->toContain('Approver Guy');
    expect($names)->not->toContain('Regular User');
});

// ──────────────────────────────────────────────────────────────────────────────
// Sorting
// ──────────────────────────────────────────────────────────────────────────────

test('sort_name asc returns users sorted A to Z', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->create(['name' => 'Zebra', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Apple', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Mango', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);

    $response = actingAs($superAdmin)
        ->getJson('/admin/api/users?search=&sort_name=asc')
        ->assertOk();

    $names = collect($response->json('data.data'))->pluck('name')->values();
    $sorted = $names->sort()->values();
    expect($names->all())->toEqual($sorted->all());
});

test('sort_name desc returns users sorted Z to A', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->create(['name' => 'Zebra', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Apple', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Mango', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);

    $response = actingAs($superAdmin)
        ->getJson('/admin/api/users?search=&sort_name=desc')
        ->assertOk();

    $names = collect($response->json('data.data'))->pluck('name')->values();
    $sorted = $names->sortDesc()->values();
    expect($names->all())->toEqual($sorted->all());
});

// ──────────────────────────────────────────────────────────────────────────────
// Stats
// ──────────────────────────────────────────────────────────────────────────────

test('response always includes global stats unaffected by filters', function () {
    ['superAdmin' => $superAdmin, 'unit' => $unit] = setupSuperAdminAndUnit();

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->count(3)->create(['unit_id' => $unit->id, 'role_id' => $userRole->id]);

    $totalBeforeFilter = actingAs($superAdmin)
        ->getJson('/admin/api/users')
        ->json('stats.total');

    $totalAfterFilter = actingAs($superAdmin)
        ->getJson('/admin/api/users?search=zzznomatch')
        ->json('stats.total');

    expect($totalAfterFilter)->toBe($totalBeforeFilter);
});

// ──────────────────────────────────────────────────────────────────────────────
// Admin_Unit Scope
// ──────────────────────────────────────────────────────────────────────────────

test('Admin_Unit can only see users within their unit scope', function () {
    $adminUnitRole = Role::factory()->create(['name' => 'Administrator Unit']);
    Role::factory()->create(['name' => 'Administrator Utama']);
    Role::factory()->create(['name' => 'Peminjam']);
    Role::factory()->create(['name' => 'Penyetuju']);

    $unit = Unit::factory()->create(['level' => 'Jurusan', 'unit_name' => 'Jurusan TI', 'parent_id' => null]);
    $otherUnit = Unit::factory()->create(['level' => 'Jurusan', 'unit_name' => 'Jurusan Sipil', 'parent_id' => null]);

    $adminUser = User::factory()->create(['role_id' => $adminUnitRole->id, 'unit_id' => $unit->id, 'name' => 'Admin TI']);

    $userRole = Role::where('name', 'Peminjam')->first();
    User::factory()->create(['name' => 'In Scope', 'unit_id' => $unit->id, 'role_id' => $userRole->id]);
    User::factory()->create(['name' => 'Out of Scope', 'unit_id' => $otherUnit->id, 'role_id' => $userRole->id]);

    $response = actingAs($adminUser)
        ->getJson('/admin/api/users')
        ->assertOk();

    $names = collect($response->json('data.data'))->pluck('name');
    expect($names)->toContain('In Scope');
    expect($names)->not->toContain('Out of Scope');
});
