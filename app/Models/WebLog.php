<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WebLog
 *
 * @property int $id
 * @property string|null $model 模块
 * @property string|null $controll 控制器
 * @property string|null $action 方法
 * @property string|null $request_type 请求方式
 * @property mixed|null $params 参数
 * @property int|null $admin_user_id 操作用户ID
 * @property string|null $create_time 操作时间
 * @property string $request_ip
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereAdminUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereControll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereRequestIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereRequestType($value)
 * @mixin \Eloquent
 */
class WebLog extends Model
{
    protected $table = "web_log";
}
