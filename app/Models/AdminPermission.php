<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminPermission
 *
 * @property int $id
 * @property int $pid 父级ID
 * @property string $name 名称
 * @property string $uri URI（模块.控制器.操作）
 * @property int $sort 排序值
 * @property int $status 状态（1正常，2禁用）
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereUri($value)
 * @mixin \Eloquent
 */
class AdminPermission extends Model
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
