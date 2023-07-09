<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Merchant
 *
 * @property int $id
 * @property string $merchantid 商户号
 * @property string $secretkey 密钥
 * @property string $password 密码
 * @property string $balance 余额
 * @property string|null $remember_token
 * @property int $agent_id 代理ID
 * @property string $name 商户名称
 * @property string $identity 统一引用
 * @property int $settlement_cycle 结算周期（D+几）
 * @property int $status 状态（1正常，2禁用）
 * @property string $manual_payin_poundage 手动代收手续费（百分之几+每笔）
 * @property string $payin_poundage 代收手续费（百分之几+每笔）
 * @property string $payin_limit 代收限额
 * @property int $payin_status 代收状态（1正常，2禁用）
 * @property int $payin_is_cycle 代收是否轮训（1是，2否）
 * @property string $payin_channel_id 代收通道ID
 * @property string $payout_poundage 代付手续费（百分之几+每笔）
 * @property string $payout_trigger 代付：代付金额多少后不收每笔手续费
 * @property string $payout_limit 代付限额
 * @property int $payout_status 代付状态（1正常，2禁用）
 * @property int $payout_is_cycle 代付类型（1正常，2轮训，3排序）
 * @property string $payout_channel_id 代付通道ID
 * @property int $is_delete 是否删除（1是，2否）
 * @property string $google_authenticator Google身份验证器
 * @property string $request_ip 报备IP
 * @property string $request_domain 报备域名
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property string $jump_link 商户跳转链接
 * @property string $payinsettlement_proportion 代收结算比例（百分之几）
 * @property string $payinsettlement_remark 下发税务成本备注
 * @property int $is_settlement 是否结算（1是，2否）
 * @property int $type 商户业务类型
 * @property string $url 商户官网
 * @property int $in_sorting_channel_group
 * @property int $out_sorting_channel_group
 * @property string|null $credits_amount 用户信用额度
 * @property string|null $freeze_amount 用户冻结金额
 * @property string|null $in_min_amount 代收最低金额
 * @property string|null $in_max_amount 代收最高金额
 * @property string|null $out_min_amount 代付最低金额
 * @property string|null $out_max_amount 代付最高金额
 * @property string $agent_in_fee 代理的代收费率
 * @property string $agent_out_fee 代理的代付费率
 * @property int $allow_manual_payout 是否允许手动代付：1-是;2-否
 * @property string $product_name 产品名称
 * @property string $product_logo 产品logo
 * @property string|null $tg_id tg群ID
 * @property string $exchange_min_amount 换汇最低金额
 * @property string|null $tg_contact tg联系人
 * @property int $is_show_video 充值页面视频是否显示：1-是；2-否
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereAgentInFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereAgentOutFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereAllowManualPayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCreditsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereExchangeMinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereFreezeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereGoogleAuthenticator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereInMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereInMinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereInSortingChannelGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereIsSettlement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereIsShowVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereJumpLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereManualPayinPoundage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMerchantid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereOutMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereOutMinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereOutSortingChannelGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayinChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayinIsCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayinLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayinPoundage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayinStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayinsettlementProportion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayinsettlementRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayoutChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayoutIsCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayoutLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayoutPoundage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayoutStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePayoutTrigger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereProductLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereRequestDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereRequestIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereSecretkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereSettlementCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereTgContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereTgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereUrl($value)
 * @mixin \Eloquent
 */
class Merchant extends Authenticatable
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
        }
        
        return $array[$key];
    }
    
    public static function payinStatus($key = '')
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
    
    public static function payinIsCycle($key = '')
    {
        $array = [
            '1' => '固定',
            '2' => '挑选',
            '3' => '分组',
        ];
        
        if (empty($key)) {
            return $array;
        } else {
            return $array[$key];
        }
    }
    
    public static function payoutStatus($key = '')
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
    
    public static function payoutIsCycle($key = '')
    {
        $array = [
            '1' => '固定',
            '2' => '挑选',
            '3' => '分组',
        ];
        
        if (empty($key)) {
            return $array;
        } else {
            return $array[$key];
        }
    }
    
    public static function isDelete($key = '')
    {
        $array = [
            '1' => '是',
            '2' => '否',
        ];
        
        if (empty($key)) {
            return $array;
        } else {
            return $array[$key];
        }
    }
    
    public static function isSettlement($key = '')
    {
        $array = [
            '1' => '是',
            '2' => '否',
        ];
        
        if (empty($key)) {
            return $array;
        } else {
            return $array[$key];
        }
    }
    
    public static function allowManualPayout($key = '')
    {
        $array = [
            '1' => '是',
            '2' => '否',
        ];
        
        if (empty($key)) {
            return $array;
        } else {
            return $array[$key];
        }
    }
    
    public static function type($key = '')
    {
        $array = [
            '0'   => '无',
            '1'   => '棋牌',
            '2'   => '菠菜',
            '3'   => '网赚',
            '5'   => '理财',
            '6'   => '四方',
            '7'   => '直播',
            '8'   => '风险',
            '10'  => '现金贷',
            '9'   => 'VIP',
            '99'  => '停止合作',
            '4'   => '其他',
        ];
        
        if (empty($key)) {
            return $array;
        } else {
            return $array[$key];
        }
    }

}
