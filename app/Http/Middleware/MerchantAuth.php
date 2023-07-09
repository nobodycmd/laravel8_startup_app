<?php

namespace App\Http\Middleware;

use App\Models\AdminRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MerchantAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth('merchant')->check()) {
            return redirect()->route('merchant.index.login');
        }
        
        $isPass     = true;
        $permission = [];
        
        if ($isPass) {
            $permissionUriList   = [];
            $permissionUriList[] = 'merchant.index.password';
            $permissionUriList[] = 'merchant.index.logout';

            $permissionUriList[] = 'merchant.homepage.console';

            $permissionUriList[] = 'merchant.merchant.info';
            $permissionUriList[] = 'merchant.merchant.code';
            $permissionUriList[] = 'merchant.merchant.seekey';
            $permissionUriList[] = 'merchant.merchant.resetkey';
            $permissionUriList[] = 'merchant.merchant.seegc';
            $permissionUriList[] = 'merchant.merchant.resetgc';
            $permissionUriList[] = 'merchant.merchant.accountlog';
            $permissionUriList[] = 'merchant.merchant.merchantorderstatistic';
            $permissionUriList[] = 'merchant.merchant.innertransfer';

            $permissionUriList[] = 'merchant.payinorder.index';
            $permissionUriList[] = 'merchant.payinorder.export';
            $permissionUriList[] = 'merchant.payinorder.credentials';
            $permissionUriList[] = 'merchant.payinorder.query';
            $permissionUriList[] = 'merchant.payinorder.budan';
            $permissionUriList[] = 'merchant.payinorder.notify';
            $permissionUriList[] = 'merchant.payoutorder.index';
            $permissionUriList[] = 'merchant.payoutorder.export';
            $permissionUriList[] = 'merchant.payoutorder.exportimps';
            $permissionUriList[] = 'merchant.payoutorder.credentials';
            $permissionUriList[] = 'merchant.payoutorder.query';
            $permissionUriList[] = 'merchant.payoutorder.notify';

            $permissionUriList[] = 'merchant.autouppoint.index';
            $permissionUriList[] = 'merchant.autouppoint.create';
            $permissionUriList[] = 'merchant.autouppoint.upload';
            $permissionUriList[] = 'merchant.autodownpoint.index';
            $permissionUriList[] = 'merchant.autodownpoint.create';
            $permissionUriList[] = 'merchant.autodownpoint.upload';

            $permissionUriList[] = 'merchant.logo.index';
            $permissionUriList[] = 'merchant.logo.store';
            $permissionUriList[] = 'merchant.logo.destroy';
            $permissionUriList[] = 'merchant.logo.update';
            $permissionUriList[] = 'merchant.logo.upload';

            $permissionUriList[] = 'merchant.payinorder.batchupiorder';
            $permissionUriList[] = 'merchant.payinorder.upload';
            $permissionUriList[] = 'merchant.payinorder.batchupistatus';
            $permissionUriList[] = 'merchant.payinorder.daily_statistics';
            $permissionUriList[] = 'merchant.autodownpoint.del';
            $permission          = $permissionUriList;
            
            if (!in_array(Route::currentRouteName(), $permissionUriList)) {
                $isPass = false;
            }
        }
        
        if (!$isPass) {
            abort(403);
        }

        $menus = AdminRole::getBackendMenuForMerchant($permission);

        $request->attributes->add(compact('permission','menus'));
        
        return $next($request);
    }
}
