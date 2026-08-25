<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\UserConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Assume role_id of ADMIN is 1 (or adjust according to the project's DB)
    private const ADMIN_ROLE_ID = 1;

    public function __construct(
        protected UserService $userService
    ) {}

    // Get paginated list of users
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $users = $this->userService->getAllUsers($perPage);

        return new BaseResourceCollection($users);
    }

    // Create a new user
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return $this->successResponse(
            new UserResource($user),
            UserConstant::MSG_CREATE_SUCCESS,
            201
        );
    }

    // Show user details
    public function show(User $user)
    {
        $userData = $this->userService->getUserById($user);

        return $this->successResponse(
            new UserResource($userData),
            UserConstant::MSG_GET_DETAIL_SUCCESS
        );
    }

    // Update user information
    public function update(UpdateUserRequest $request, User $user)
    {
        $updatedUser = $this->userService->updateUser($user, $request->validated());

        return $this->successResponse(
            new UserResource($updatedUser),
            UserConstant::MSG_UPDATE_SUCCESS
        );
        // --- BUSINESS LOGIC: PROTECT THE LAST ADMIN ---
        $isCurrentAdmin = ($user->role_id === self::ADMIN_ROLE_ID);
        
        if ($isCurrentAdmin) {
            $newRoleId = $data['role_id'] ?? $user->role_id;
            $newIsActive = $data['is_active'] ?? $user->is_active;

            $isChangingRole = ($newRoleId !== self::ADMIN_ROLE_ID);
            $isDeactivating = ($newIsActive === false || $newIsActive === 0);

            if ($isChangingRole || $isDeactivating) {
                // Count how many other active Admins remain in the system (excluding the current user)
                $otherActiveAdminsCount = User::where('role_id', self::ADMIN_ROLE_ID)
                    ->where(function ($query) {
                        $query->where('is_active', true)
                              ->orWhereNull('is_active');
                    })
                    ->where('id', '!=', $user->id)
                    ->count();

                if ($otherActiveAdminsCount === 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể thay đổi vai trò hoặc vô hiệu hóa Admin cuối cùng trong hệ thống.',
                    ], 422);
                }
            }
        }
        // ---------------------------------------------

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật người dùng thành công',
            'data' => new UserResource($user)
        ]);
    }

    // Delete a user
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        // Check and block immediately if deleting the last Admin (if delete API exists)
        if ($user->role_id === self::ADMIN_ROLE_ID) {
            $otherActiveAdminsCount = User::where('role_id', self::ADMIN_ROLE_ID)
                ->where('id', '!=', $user->id)
                ->count();

        return $this->successResponse(
            null,
            UserConstant::MSG_DELETE_SUCCESS
        );
    }
}