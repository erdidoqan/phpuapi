<?php

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'view lessons']);
        Permission::create(['name' => 'create lessons']);
        Permission::create(['name' => 'update lessons']);
        Permission::create(['name' => 'delete lessons']);

        Permission::create(['name' => 'view episodes']);
        Permission::create(['name' => 'create episodes']);
        Permission::create(['name' => 'update episodes']);
        Permission::create(['name' => 'delete episodes']);

        Permission::create(['name' => 'view difficulties']);
        Permission::create(['name' => 'create difficulties']);
        Permission::create(['name' => 'update difficulties']);
        Permission::create(['name' => 'delete difficulties']);

        Permission::create(['name' => 'view skills']);
        Permission::create(['name' => 'create skills']);
        Permission::create(['name' => 'update skills']);
        Permission::create(['name' => 'delete skills']);

        Permission::create(['name' => 'view tags']);
        Permission::create(['name' => 'create tags']);
        Permission::create(['name' => 'update tags']);
        Permission::create(['name' => 'delete tags']);

        Permission::create(['name' => 'view subscriptions']);
        Permission::create(['name' => 'update subscriptions']);

        Permission::create(['name' => 'view subscription types']);
        Permission::create(['name' => 'update subscription types']);

        Permission::create(['name' => 'view post categories']);
        Permission::create(['name' => 'create post categories']);
        Permission::create(['name' => 'update post categories']);
        Permission::create(['name' => 'delete post categories']);

        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'update posts']);
        Permission::create(['name' => 'delete posts']);

        Permission::create(['name' => 'view threads']);
        Permission::create(['name' => 'create threads']);
        Permission::create(['name' => 'update threads']);
        Permission::create(['name' => 'delete threads']);

        Permission::create(['name' => 'view thread categories']);
        Permission::create(['name' => 'create thread categories']);
        Permission::create(['name' => 'update thread categories']);
        Permission::create(['name' => 'delete thread categories']);

        Permission::create(['name' => 'view students']);
        Permission::create(['name' => 'create students']);
        Permission::create(['name' => 'update students']);
        Permission::create(['name' => 'delete students']);

        Permission::create(['name' => 'view roles']);

        Permission::create(['name' => 'create videos']);

        Permission::create(['name' => 'create images']);

        $role = Role::create(['name' => 'admin']);

        $role = Role::create(['name' => 'instructor']);
        $role->givePermissionTo([
            'view lessons',
            'create lessons',
            'update lessons',
            'delete lessons',

            'view episodes',
            'create episodes',
            'update episodes',
            'delete episodes',

            'view difficulties',
            'create difficulties',
            'update difficulties',

            'view skills',
            'create skills',
            'update skills',

            'view tags',
            'create tags',
            'update tags',

            'create videos',
        ]);

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo([
            'view post categories',
            'create post categories',
            'update post categories',

            'view posts',
            'create posts',
            'update posts',
            'delete posts',

            'create images'
        ]);

        factory(User::class)
            ->create(['name' => 'Admin', 'email' => 'admin@example.com'])
            ->assignRole('admin');
    }
}
