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
    use ApiResponse;

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
    }

    // Delete a user
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return $this->successResponse(
            null,
            UserConstant::MSG_DELETE_SUCCESS
        );
    }
}