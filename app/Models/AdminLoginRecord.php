<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminLoginRecord
 *
 * @property int $id
 * @property int $admin_id 管理员ID
 * @property string $login_ip 登录IP
 * @property int $login_time 登录时间
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord whereLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord whereLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginRecord whereUpdateTime($value)
 * @mixin \Eloquent
 */
class AdminLoginRecord extends Model
{
    use HasFactory;

    public $timestamps = false;
}
