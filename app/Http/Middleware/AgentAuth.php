<?php

namespace App\Http\Middleware;

use App\Models\AdminRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AgentAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth('agent')->check()){
            return redirect()->route('agent.index.login');
        }

        $isPass = true;
        $permission = [];

        if($isPass){
            $permissionUriList = [];
            $permissionUriList[] = 'agent.index.system';
            $permissionUriList[] = 'agent.index.password';
            $permissionUriList[] = 'agent.index.logout';
            $permissionUriList[] = 'agent.homepage.console';
            $permissionUriList[] = 'agent.agent.info';
            $permissionUriList[] = 'agent.agent.myagent';
            $permissionUriList[] = 'agent.agent.mymerchant';
            $permissionUriList[] = 'agent.agent.payinorder';
            $permissionUriList[] = 'agent.agent.payoutorder';
            $permissionUriList[] = 'agent.agentsettlement.index';
            $permissionUriList[] = 'agent.agentsettlement.export';
            $permissionUriList[] = 'agent.agent.merchatpayinline';
            $permissionUriList[] = 'agent.agent.merchatpayoutline';
            $permission = $permissionUriList;

            if(!in_array(Route::currentRouteName(),$permissionUriList)){
                $isPass = false;
            }
        }

        if(!$isPass){
            abort(404);
        }

        $menus = AdminRole::getBackendMenuForAgent($permission);

        $request->attributes->add(compact('permission','menus'));

        return $next($request);
    }
}
