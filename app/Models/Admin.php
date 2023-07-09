<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Admin
 *
 * @property int $id
 * @property int $admin_role_id 管理员角色ID
 * @property string $name 姓名
 * @property string $mobile 手机号
 * @property string $username 用户名
 * @property string $password 密码
 * @property int $status 状态（1正常，2禁用）
 * @property string|null $remember_token
 * @property string $google_authenticator Google身份验证器
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property-read \App\Models\AdminRole|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereGoogleAuthenticator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUsername($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    public function role():HasOne{
        return $this->hasOne(AdminRole::class,'id','admin_role_id');
    }

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
