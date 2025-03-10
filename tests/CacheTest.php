<?php

namespace vsrklabs\Permission\Test;

use Illuminate\Support\Facades\DB;
use Maklad\Permission\Models\Permission;
use Maklad\Permission\Models\Role;
use Maklad\Permission\PermissionRegistrar;

class CacheTest extends TestCase
{
    protected mixed $registrar;

    public function setUp(): void
    {
        parent::setUp();

        $this->registrar = app(PermissionRegistrar::class);

        $this->registrar->forgetCachedPermissions();

        DB::connection('mongodb')->enableQueryLog();

        $this->assertCount(0, DB::getQueryLog());

        $this->registrar->registerPermissions();

        $this->assertCount(1, DB::getQueryLog());

        DB::flushQueryLog();
    }

    /** @test */
    public function it_can_cache_the_permissions()
    {
        $this->registrar->registerPermissions();

        $this->assertCount(0, DB::getQueryLog());
    }

    /** @test */
    public function permission_creation_and_updating_and_deleting_should_flush_the_cache()
    {
        $permission = app(Permission::class)->create(['name' => 'new']);
        $this->assertCount(1, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(2, DB::getQueryLog());

        $permission->name = 'other name';
        $permission->save();
        $this->assertCount(3, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(4, DB::getQueryLog());

        $permission->delete();
        $this->assertCount(5, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(6, DB::getQueryLog());
    }

    /** @test */
    public function role_creation_and_updating_and_deleting_should_flush_the_cache()
    {
        $role = app(Role::class)->create(['name' => 'new']);
        $this->assertCount(2, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(3, DB::getQueryLog());

        $role->name = 'other name';
        $role->save();
        $this->assertCount(4, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(5, DB::getQueryLog());

        $role->delete();
        $this->assertCount(6, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(7, DB::getQueryLog());
    }

    /** @test */
    public function user_creation_should_not_flush_the_cache()
    {
        User::create(['email' => 'new']);
        $this->assertCount(1, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(1, DB::getQueryLog());
    }

    /** @test */
    public function adding_a_permission_to_a_role_should_flush_the_cache()
    {
        $this->testUserRole->givePermissionTo($this->testUserPermission);
        $this->assertCount(1, DB::getQueryLog());

        $this->registrar->registerPermissions();
        $this->assertCount(2, DB::getQueryLog());
    }

    /** @test */
    public function has_permission_to_should_use_the_cache()
    {
        $this->testUserRole->givePermissionTo(['edit-articles', 'edit-news']);
        $this->testUser->assignRole('testRole');
        $this->assertCount(4, DB::getQueryLog());

        $this->assertTrue($this->testUser->hasPermissionTo('edit-articles'));
        $this->assertCount(8, DB::getQueryLog());

        $this->assertTrue($this->testUser->hasPermissionTo('edit-news'));
        $this->assertCount(10, DB::getQueryLog());

        $this->assertTrue($this->testUser->hasPermissionTo('edit-articles'));
        $this->assertCount(11, DB::getQueryLog());
    }
}
