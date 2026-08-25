<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Map controller method name to permission action.
     */
    protected array $actionMap = [
    'index'        => 'FINDALL',
    'show'         => 'FINDONE',
    'create'       => 'CREATE',
    'store'        => 'CREATE',
    'edit'         => 'UPDATE',
    'update'       => 'UPDATE',
    'destroy'      => 'DELETE',

    'updateStatus' => 'UPDATESTATUS',
    'adjustStock'  => 'ADJUSTSTOCK',
    'addItem'      => 'ADDITEM',
    'updateItem'   => 'UPDATEITEM',
    'removeItem'   => 'REMOVEITEM',
    'capture'      => 'CAPTURE',
    ];

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->route('login');
        }

        $route = $request->route();
        $action = $route?->getAction();

        if (!isset($action['controller'])) {
            return $next($request);
        }

        [$controller, $method] = explode(
            '@',
            $action['controller']
        );

        $controllerName = class_basename($controller);

        $rawResource = str_replace(
            ['WebController', 'Controller'],
            '',
            $controllerName
        );

        $resource = strtoupper(
            Str::plural($rawResource)
        );

        $permissionAction =
            $this->actionMap[$method]
            ?? strtoupper($method);

        $requiredPermission =
            "{$resource}.{$permissionAction}";

        $hasPermission = $user->role?->permissions()
            ->where('name', $requiredPermission)
            ->exists();

        if (!$hasPermission) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' =>
                        'Forbidden: You do not have permission to access this resource.',
                    'required_permission' =>
                        $requiredPermission,
                ], 403);
            }

            abort(403);
        }

        return $next($request);
    }
}