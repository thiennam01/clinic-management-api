<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use App\Constants\UserConstant;
use Illuminate\Http\Request;

class UserWebController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->q . '%')
                  ->orWhere('email', 'ilike', '%' . $request->q . '%');
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('display_name')->get();

        return view('users.index', compact(
            'users',
            'roles'
        ));
    }

    public function create()
    {
        $roles = Role::orderBy('display_name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        try {
            $validated['is_active'] =
                $request->boolean('is_active', true);

            $user = $this->userService->createUser($validated);

            return redirect()
                ->route('users.web.index')
                ->with(
                    'success',
                    UserConstant::MSG_CREATE_SUCCESS
                );

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'user' => $e->getMessage(),
                ]);
        }
    }

    public function edit(User $user)
    {
        $user->load('role');

        $roles = Role::orderBy('display_name')->get();

        return view('users.edit', compact(
            'user',
            'roles'
        ));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        try {
            $validated['is_active'] =
                $request->boolean('is_active');

            if (empty($validated['password'])) {
                unset($validated['password']);
            }

            $this->userService->updateUser(
                $user,
                $validated
            );

            return redirect()
                ->route('users.web.index')
                ->with(
                    'success',
                    UserConstant::MSG_UPDATE_SUCCESS
                );

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'user' => $e->getMessage(),
                ]);
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);

            return redirect()
                ->route('users.web.index')
                ->with(
                    'success',
                    UserConstant::MSG_DELETE_SUCCESS
                );

        } catch (\Exception $e) {
            return back()
                ->withErrors([
                    'user' => $e->getMessage(),
                ]);
        }
    }
}