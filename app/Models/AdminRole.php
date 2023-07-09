<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\AdminRole
 *
 * @property int $id
 * @property string $name 名称
 * @property string $identity 标识
 * @property int $status 状态（1正常，2禁用）
 * @property string $permission 权限（多个设置，用|隔开）
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereUpdateTime($value)
 * @mixin \Eloquent
 */
class AdminRole extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function status($key = '')
    {
        $array = [
            '1' => '正常',
            '2' => '禁用',
        ];

        if (empty($key)) {
            return $array;
        } else {
            return $array[$key];
        }
    }

    public static function getPermissionList($isSuperRole = false)
    {
        $adminRolePermission = AdminRole::where('id', auth('admin')->user()->admin_role_id)->value('permission');

        if (empty($adminRolePermission)) {
            $adminRolePermission = [0];
        } else {
            $adminRolePermission = explode('|', $adminRolePermission);
        }

        if ($isSuperRole)
            $permissionUriList = AdminPermission::where('status', 1)
                ->where('uri', '<>', '')
                ->pluck('uri')->toArray();
        else
            $permissionUriList = AdminPermission::whereIn('id', $adminRolePermission)
                ->where('status', 1)->where('uri', '<>', '')
                ->pluck('uri')
                ->toArray();

        $permissionUriList[] = 'admin.index.system';
        $permissionUriList[] = 'admin.index.password';
        $permissionUriList[] = 'admin.index.logout';
        $permissionUriList[] = 'admin.system.clearcache';
        $permissionUriList[] = 'admin.transfersmoney.balance';
        $permissionUriList[] = 'admin.transfersmoney.config';
        $permissionUriList[] = 'admin.autodownpoint.upload';
        $permissionUriList[] = 'admin.homepage.ordernumbercount';
//        $permissionUriList[] = 'admin.devtool.index';
//        $permissionUriList[] = 'admin.devtool.tables';
//        $permissionUriList[] = 'admin.devtool.rows';
//        $permissionUriList[] = 'admin.devtool.codes';
        return $permissionUriList;
    }


    public static function getBackendMenu($isSuperRole = false)
    {
        $permission = AdminRole::where('id', auth('admin')->user()->admin_role_id)->value('permission');
        if (empty($permission)) {
            $permission = [0];
        } else {
            $permission = explode('|', $permission);
        }

        if ($isSuperRole)
            $list = AdminPermission::select('id', 'name', 'uri')->where('status', 1)->where('pid', 0)->orderBy('sort', 'asc')->get()->toArray();
        else
            $list = AdminPermission::select('id', 'name', 'uri')->where('status', 1)->whereIn('id', $permission)->where('pid', 0)->orderBy('sort', 'asc')->get()->toArray();

        if ($list) {
            foreach ($list as $k => $v) {
                $childList = AdminPermission::select('id', 'name', 'uri')->where('status', 1)->whereIn('id', $permission)->where('pid', $v['id'])->orderBy('sort', 'asc')->get()->toArray();
                if ($childList) {
                    $list[$k]['childList'] = $childList;
                } else {
                    unset($list[$k]);
                }
            }
        }
        return $list;
    }


    public static function getBackendMenuForMerchant($permission)
    {

        $list = new AdminPermission();
        $list->id = 0;
        $list->name = '主页';
        $list->uri = '';

        $list = $list->toArray();
        $list['childList'] = [
            [
                'name' => '看板',
                'uri' => 'merchant.homepage.console',
            ],
            [
                'name' => '信息查看',
                'uri' => 'merchant.merchant.info',
            ],
            [
                'name' => '代收订单',
                'uri' => 'merchant.payinorder.index',
            ],
            [
                'name' => '代付订单',
                'uri' => 'merchant.payoutorder.index',
            ],
            [
                'name' => '资金变动',
                'uri' => 'merchant.merchant.accountlog',
            ],
            [
                'name' => '订单报告',
                'uri' => 'merchant.merchant.merchantorderstatistic',
            ],
            [
                'name' => '上分模块',
                'uri' => 'merchant.autouppoint.index',
            ],
            [
                'name' => '下分模块',
                'uri' => 'merchant.autodownpoint.index',
            ],
        ];

        return [$list];
    }

    public static function getBackendMenuForAgent($permission)
    {

        $list = new AdminPermission();
        $list->id = 0;
        $list->name = '主页';
        $list->uri = '';

        $list = $list->toArray();
        $list['childList'] = [
            [
                'name' => '控制台',
                'uri' => 'agent.homepage.console',
            ],
            [
                'name' => '信息',
                'uri' => 'agent.agent.info',
            ],
            [
                'name' => '商户',
                'uri' => 'agent.agent.mymerchant',
            ],
            [
                'name' => '代收',
                'uri' => 'agent.agent.payinorder',
            ],
            [
                'name' => '代付',
                'uri' => 'agent.agent.payoutorder',
            ],
            [
                'name' => '结算',
                'uri' => 'agent.agentsettlement.index',
            ],
        ];

        return [$list];
    }
}
