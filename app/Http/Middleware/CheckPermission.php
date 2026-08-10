<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * ⑧ Map action (method name) -> ACTION (RESTful Verb)
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
        // ⑩ Lấy user hiện tại
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // ⑥ Lấy Controller class + action method hiện tại từ Route
        $route = $request->route();
        $action = $route?->getAction();

        if (!isset($action['controller'])) {
            return $next($request);
        }

        // Tách chuỗi Controller@method (VD: "App\Http\Controllers\UserController@index")
        [$controller, $method] = explode('@', $action['controller']);

        // ⑦ Map Controller -> RESOURCE (VD: UserController -> USERS)
        $controllerName = class_basename($controller);
        $rawResource = str_replace('Controller', '', $controllerName);
        $resource = strtoupper(Str::plural($rawResource)); // Đưa về dạng số nhiều viết hoa

        // ⑧ Map action -> ACTION
        $permissionAction = $this->actionMap[$method] ?? strtoupper($method);

        // ⑨ Ghép RESOURCE.ACTION (VD: USERS.FINDALL)
        $requiredPermission = "{$resource}.{$permissionAction}";

        // ⑩ Check User -> Role -> Permission có tồn tại trong DB không
        $hasPermission = $user->role?->permissions()
            ->where('name', $requiredPermission)
            ->exists();

        // ⑪ Không có quyền -> Trả về lỗi 403 Forbidden
        if (!$hasPermission) {
            return response()->json([
                'message' => 'Forbidden: You do not have permission to access this resource.',
                'required_permission' => $requiredPermission
            ], 403);
        }

        return $next($request);
    }
}