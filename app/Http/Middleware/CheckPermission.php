<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Map controller method name to RESTful action verb.
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
        // Get current authenticated user
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get Controller class and action method from the current Route
        $route = $request->route();
        $action = $route?->getAction();

        if (!isset($action['controller'])) {
            return $next($request);
        }

        // Parse Controller@method string (e.g., "App\Http\Controllers\UserController@index")
        [$controller, $method] = explode('@', $action['controller']);

        // Map Controller to RESOURCE (e.g., UserController -> USERS)
        $controllerName = class_basename($controller);
        $rawResource = str_replace('Controller', '', $controllerName);
        $resource = strtoupper(Str::plural($rawResource)); // Convert to uppercase plural form

        // Map method to ACTION
        $permissionAction = $this->actionMap[$method] ?? strtoupper($method);

        // Combine into format: RESOURCE.ACTION (e.g., USERS.FINDALL)
        $requiredPermission = "{$resource}.{$permissionAction}";

        // Check if the user's role possesses this permission in the database
        $hasPermission = $user->role?->permissions()
            ->where('name', $requiredPermission)
            ->exists();

        // Deny access with 403 Forbidden if permission is missing
        if (!$hasPermission) {
            return response()->json([
                'message' => 'Forbidden: You do not have permission to access this resource.',
                'required_permission' => $requiredPermission
            ], 403);
        }

        return $next($request);
    }
}