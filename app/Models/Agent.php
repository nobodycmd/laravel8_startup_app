<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Agent
 *
 * @property int $id
 * @property string $agentid 代理号
 * @property int $pid 父级ID
 * @property string $name 代理名称
 * @property string $payin_poundage 代收手续费（百分之几+每笔）
 * @property string $payout_poundage 代付手续费（百分之几+每笔）
 * @property int $status 状态（1正常，2禁用）
 * @property string $password 密码
 * @property string|null $remember_token
 * @property string $google_authenticator Google身份验证器
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property string $remark 备注
 * @method static \Illuminate\Database\Eloquent\Builder|Agent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereAgentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereGoogleAuthenticator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent wherePayinPoundage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent wherePayoutPoundage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereUpdateTime($value)
 * @mixin \Eloquent
 */
class Agent extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    public static function status($key = '')
    {
        $array = [
            '1' => '正常',
            '2' => '禁用',
        ];

        if(empty($key)){
            return $array;
        }else{
            return $array[$key];
        }
    }
}
