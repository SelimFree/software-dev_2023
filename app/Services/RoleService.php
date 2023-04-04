<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRoleMapping;
use Exception;

class RoleService
{
    /**
     * Adds default roles to a newly registered user.
     * @param User $user The newly registered user.
     */
    public function registerUser(User $user) {
        $userRole = $this->getRoleByName('User');

        if ($userRole) {
            UserRoleMapping::factory()->create([
                'user_id' => $user->id,
                'role_id' => $userRole->id,
            ]);
        } else {
            throw new Exception('Could not find the role');
        }
    }

    public function getRoleByName(string $name) {
        return Role::query()->where('name', $name)->first();
    }
}
