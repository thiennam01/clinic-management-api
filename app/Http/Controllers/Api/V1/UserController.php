<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Assume role_id of ADMIN is 1 (or adjust according to the project's DB)
    private const ADMIN_ROLE_ID = 1;

    public function index(Request $request): JsonResponse
    {
        $users = User::latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách người dùng thành công',
            'data' => UserResource::collection($users->items()),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tạo người dùng thành công',
            'data' => new UserResource($user)
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin người dùng thành công',
            'data' => new UserResource($user)
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

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

    public function destroy(User $user): JsonResponse
    {
        // Check and block immediately if deleting the last Admin (if delete API exists)
        if ($user->role_id === self::ADMIN_ROLE_ID) {
            $otherActiveAdminsCount = User::where('role_id', self::ADMIN_ROLE_ID)
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherActiveAdminsCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa Admin cuối cùng trong hệ thống.',
                ], 422);
            }
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa người dùng thành công',
            'data' => null
        ]);
    }
}