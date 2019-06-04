<?php

use App\Role;
use Laravel\Passport\Passport;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {

        $scopes = Passport::scopes();
        $permission = '';

        foreach ($scopes as $scope) {
            $permission = $permission . $scope->id . ' ';
        }

        Role::create([
            'role' => 'superAdmin',
            'permission' => $permission,
        ]);

        Role::create([
            'role' => 'admin',
            'permission' => 'get-users save-users edit-users delete-users',
        ]);

        Role::create([
            'role' => 'seller',
            'permission' => null,
        ]);
    }
}
