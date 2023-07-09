<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemConf
 *
 * @property int $id
 * @property int $payin_frequency 代收频率/秒
 * @property int $payinquery_frequency 代收查询频率/秒
 * @property int $payout_frequency 代付频率/秒
 * @property int $payoutquery_frequency
 * @property int $payoutbalancequery_frequency 代付余额查询频率/秒
 * @property int $is_settlement 代收是否结算:2-是；3-否;
 * @property int $settlement_time 结算截止日期
 * @property int $unservedregion_isopen 不服务地区开关 关0 开1
 * @property int $unservedregion_payinchannel 不服务地区默认通道
 * @property int|null $packet_time 抓包时间
 * @property int $D0_is_settlement D0是否结算
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf whereD0IsSettlement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf whereIsSettlement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf wherePacketTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf wherePayinFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf wherePayinqueryFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf wherePayoutFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf wherePayoutbalancequeryFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf wherePayoutqueryFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf whereSettlementTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf whereUnservedregionIsopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConf whereUnservedregionPayinchannel($value)
 * @mixin \Eloquent
 */
class SystemConf extends Model
{
    use HasFactory;

    public $timestamps = false;
}
