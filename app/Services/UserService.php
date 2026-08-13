<?php

namespace App\Services;

use App\Constants\UserConstant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    private const ADMIN_ROLE_ID = 1;

    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return User::latest()->paginate($perPage);
    }

    public function getUserById(int|User $user): User
    {
        if ($user instanceof User) {
            return $user;
        }

        $found = User::find($user);
        if (!$found) {
            abort(404, UserConstant::MSG_NOT_FOUND);
        }

        return $found;
    }

    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    public function updateUser(int|User $user, array $data): User
    {
        $userInstance = $this->getUserById($user);

        // Business Logic: Protect the last remaining active admin
        $isCurrentAdmin = ($userInstance->role_id === self::ADMIN_ROLE_ID);

        if ($isCurrentAdmin) {
            $newRoleId = $data['role_id'] ?? $userInstance->role_id;
            $newIsActive = $data['is_active'] ?? $userInstance->is_active;

            $isChangingRole = ($newRoleId !== self::ADMIN_ROLE_ID);
            $isDeactivating = ($newIsActive === false || $newIsActive === 0);

            if ($isChangingRole || $isDeactivating) {
                $otherActiveAdminsCount = User::where('role_id', self::ADMIN_ROLE_ID)
                    ->where(function ($query) {
                        $query->where('is_active', true)
                              ->orWhereNull('is_active');
                    })
                    ->where('id', '!=', $userInstance->id)
                    ->count();

                if ($otherActiveAdminsCount === 0) {
                    throw ValidationException::withMessages([
                        'role_id' => [UserConstant::MSG_CANNOT_MODIFY_LAST_ADMIN],
                    ]);
                }
            }
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $userInstance->update($data);

        return $userInstance;
    }

    public function deleteUser(int|User $user): bool
    {
        $userInstance = $this->getUserById($user);

        // Business Logic: Prevent deleting the last admin
        if ($userInstance->role_id === self::ADMIN_ROLE_ID) {
            $otherActiveAdminsCount = User::where('role_id', self::ADMIN_ROLE_ID)
                ->where('id', '!=', $userInstance->id)
                ->count();

            if ($otherActiveAdminsCount === 0) {
                throw ValidationException::withMessages([
                    'id' => [UserConstant::MSG_CANNOT_DELETE_LAST_ADMIN],
                ]);
            }
        }

        return $userInstance->delete();
    }
}