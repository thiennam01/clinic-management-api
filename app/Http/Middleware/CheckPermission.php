<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Map action (method name) -> ACTION (RESTful Verb)
     */
    protected array $actionMap = [
        'index'        => 'FINDALL',
        'show'         => 'FINDONE',
        'store'        => 'CREATE',
        'update'       => 'UPDATE',
        'destroy'      => 'DELETE',
        'updateStatus' => 'UPDATESTATUS',
        'adjustStock'  => 'ADJUSTSTOCK',
        'addItem'      => 'ADDITEM',
        'updateItem'   => 'UPDATEITEM',
        'removeItem'   => 'REMOVEITEM',
        'capture'      => 'CAPTURE',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Get current user
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get current Controller class + action method from Route
        $route = $request->route();
        $action = $route?->getAction();

        if (!isset($action['controller'])) {
            return $next($request);
        }

        // Split the Controller@method string (e.g., "App\Http\Controllers\UserController@index")
        [$controller, $method] = explode('@', $action['controller']);

        // Map Controller -> RESOURCE (VD: UserController -> USERS)
        $controllerName = class_basename($controller);
        $rawResource = str_replace('Controller', '', $controllerName);
        $resource = strtoupper(Str::plural($rawResource)); // Đưa về dạng số nhiều viết hoa

        // Map action -> ACTION
        $permissionAction = $this->actionMap[$method] ?? strtoupper($method);

        // Combine RESOURCE.ACTION (e.g., USERS.FINDALL)
        $requiredPermission = "{$resource}.{$permissionAction}";

        // Check if User -> Role -> Permission exists in the DB
        $hasPermission = $user->role?->permissions()
            ->where('name', $requiredPermission)
            ->exists();

        // No permission -> Return a 403 Forbidden error
        if (!$hasPermission) {
            return response()->json([
                'message' => 'Forbidden: You do not have permission to access this resource.',
                'required_permission' => $requiredPermission
            ], 403);
        }

        return $next($request);
    }
}
