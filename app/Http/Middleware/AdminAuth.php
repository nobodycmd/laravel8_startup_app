<?php

namespace App\Http\Middleware;

use App\Models\AdminRole;
use App\Services\WebLogServer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth('admin')->check()) {
            return redirect()->route('admin.index.login');
        }

        $isPass = true;
        $permission = [];

        $adminRoleStatus = AdminRole::where('id', auth('admin')->user()->admin_role_id)->value('status');
        if ($adminRoleStatus != 1 || auth('admin')->user()->status != 1) {
            $isPass = false;
        }

        if ($isPass) {
            $permission = $permissionUriList = AdminRole::getPermissionList();

            if (!in_array(Route::currentRouteName(), $permissionUriList)) {
                $isPass = false;
            }
        }

        if (auth('admin')->user()->admin_role_id == 1) {
            $isPass = true;
            $permission = $permissionUriList = AdminRole::getPermissionList(true);
            $menus = AdminRole::getBackendMenu(true);
        } else {
            $menus = AdminRole::getBackendMenu();
        }

        if (!$isPass) {
            if ($request->ajax()) {
                echo json_encode([
                    'code' => 403,
                    'message' => 'have no right',
                    'data' => []
                ]);
                exit;
            } else {
                abort(403);
            }
        }

        $request->attributes->add(compact('permission', 'menus'));
//        request()->attributes->add(compact('permission'));
        WebLogServer::setLog($request);
        return $next($request);
    }
}
