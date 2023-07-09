<?php

namespace App\Services;

use App\Models\Journal;
use App\Models\Merchant;
use App\Models\PayinBulkSettlementLog;

/**
 * 资金 下发 服务
 * Class AccountService.
 */
class AccountService
{
    /**
     * 商户资金变动锁
     * @param $merchantid
     * @return string
     */
    public static function getLockKey($merchantid)
    {
        return 'AccountService'.$merchantid;
    }

    public static function getBalance($merchantid)
    {
        return Merchant::firstWhere([
            'merchantid' => $merchantid,
        ])->balance;
    }

    /**
     * 待下发.
     * @param $merchantid
     * @return int|mixed
     */
    public static function getWaitSettle($merchantid)
    {
        return PayinBulkSettlementLog::where('merchantid', $merchantid)
            ->where('is_xiafa', 2)->sum('account_fee');
    }

    /**
     * 冻结的金额.
     * @param $merchantid
     * @return int
     */
    public static function freezonMoney($merchantid)
    {
        return Merchant::firstWhere([
            'merchantid' => $merchantid,
        ])->freeze_amount;
    }

    /**
     * 商户间相互转账.
     * @param Merchant $from
     * @param Merchant $to
     * @param $money
     * @throws \Throwable
     */
    public static function innerTransfer(Merchant $from, Merchant $to, $money)
    {
        $lock1 = RedisService::getLockWithTimeout(self::getLockKey($from->merchantid), 60, 3);
        $lock2 = RedisService::getLockWithTimeout(self::getLockKey($to->merchantid), 60, 3);
        if ($lock1 && $lock2) {
            if ($from->balance < $money) {
                throw new \Exception('商户交易余额不足');
            }

            \DB::beginTransaction();
            try {
                //转账商户减少
                $beforeBalance = $from->balance;
                if (false == $from->decrement('balance', $money, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }
                $afterBalance = bcadd($beforeBalance, -$money, 2);

                if (false == Journal::add($from->merchantid, '', Journal::TYPE_MERCHANT, $money, $beforeBalance, $afterBalance, '商户之间转账')) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                //收益商户金额增加
                $beforeBalance2 = $to->balance;
                if (false == $to->increment('balance', $money, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }
                $afterBalance2 = bcadd($beforeBalance2, $money, 2);

                if (false == Journal::add($to->merchantid, '', Journal::TYPE_MERCHANT, $money, $beforeBalance2, $afterBalance2, '商户之间转账')) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            } finally {
                RedisService::del(self::getLockKey($from->merchantid));
                RedisService::del(self::getLockKey($to->merchantid));
            }
        } else {
            if ($lock1) {
                RedisService::del(self::getLockKey($from->merchantid));
            }
            if ($lock2) {
                RedisService::del(self::getLockKey($to->merchantid));
            }
            throw new \Exception('交易双方忙，稍后再试');
        }
    }

    public static function freezeMerchantMoney($merchantid, $amount, $type = Journal::TYPE_FROZEN, $remark = '冻结')
    {
        $amount = abs($amount);

        return RedisService::executeWithLock(self::getLockKey($merchantid), function () use ($merchantid, $amount, $type, $remark) {
            $balance = self::getBalance($merchantid);

            \DB::beginTransaction();
            try {
                if (false == Merchant::where('merchantid', $merchantid)->decrement('balance', $amount, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                if (false == Merchant::where('merchantid', $merchantid)->increment('freeze_amount', $amount, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                $beforeBalance = $balance;
                $afterBalance  = bcadd($balance, -$amount, 2);

                if (false == Journal::add($merchantid, '', $type, $amount, $beforeBalance, $afterBalance, $remark)) {
                    throw new \Exception(__FILE__.__LINE__);
                }
                \DB::commit();

                return $afterBalance;
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        });
    }

    public static function unFreezeMerchantMoney($merchantid, $amount, $type = Journal::TYPE_UNFROZEN, $remark = '解冻')
    {
        $amount = abs($amount);

        return RedisService::executeWithLock(self::getLockKey($merchantid), function () use ($merchantid, $amount, $type, $remark) {
            $balance = self::getBalance($merchantid);

            \DB::beginTransaction();
            try {
                if (false == Merchant::where('merchantid', $merchantid)->increment('balance', $amount, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                if (false == Merchant::where('merchantid', $merchantid)->decrement('freeze_amount', $amount, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                $beforeBalance = $balance;
                $afterBalance  = bcadd($balance, $amount, 2);

                if (false == Journal::add($merchantid, '', $type, $amount, $beforeBalance, $afterBalance, $remark)) {
                    throw new \Exception(__FILE__.__LINE__);
                }
                \DB::commit();

                return $afterBalance;
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * 获取余额【含等待未下发金额].
     * @param $merchantid
     */
    public static function getBalanceIncludeWaiting($merchantid)
    {
        return bcadd(self::getBalance($merchantid), self::getWaitSettle($merchantid), 2);
    }

    /**
     * 账户金额增加.
     *
     * @param string $merchantid 商户ID
     * @param float $amount 账变金额
     * @param int $related_id 关联记录 代收时候是 结算记录表ID 代付时候是代付表ID
     * @param string $type 交易类型
     * @param string $remark
     * @return false|mixed
     * @throws \Throwable
     */
    public static function increase($merchantid, $amount, $related_id, $type = Journal::TYPE_IN, $remark = '代收')
    {
        $amount = abs($amount);

        return RedisService::executeWithLock(self::getLockKey($merchantid), function () use ($merchantid, $amount, $related_id, $type, $remark) {
            $balance = self::getBalance($merchantid);

            \DB::beginTransaction();
            try {
                if (false == Merchant::where('merchantid', $merchantid)->increment('balance', $amount, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                $beforeBalance = $balance;
                $afterBalance  = bcadd($balance, $amount, 2);

                if (false == Journal::add($merchantid, $related_id, $type, $amount, $beforeBalance, $afterBalance, $remark)) {
                    throw new \Exception(__FILE__.__LINE__);
                }
                \DB::commit();

                return $afterBalance;
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * 账户金额减少.
     *
     * @param string $merchantid 商户ID
     * @param float $amount 账变金额
     * @param int $related_id 关联记录 代收时候是 结算记录表ID 代付时候是代付表ID
     * @param string $type 交易类型
     * @param string $remark 备注
     * @return false|mixed
     * @throws \Throwable
     */
    public static function decrease($merchantid, $amount, $related_id, $type = Journal::TYPE_OUT, $remark = '代付')
    {
        $amount = abs($amount);

        return RedisService::executeWithLock(self::getLockKey($merchantid), function () use ($merchantid, $amount, $related_id, $type, $remark) {
            $balance = self::getBalance($merchantid);

            \DB::beginTransaction();
            try {
                if (false == Merchant::where('merchantid', $merchantid)->decrement('balance', $amount, ['update_time' => time()])) {
                    throw new \Exception(__FILE__.__LINE__);
                }

                $beforeBalance = $balance;
                $afterBalance  = bcsub($balance, $amount, 2);

                //变为负数
                $amount = bcsub(0, $amount, 2);
                if (false == Journal::add($merchantid, $related_id, $type, $amount, $beforeBalance, $afterBalance, $remark)) {
                    throw new \Exception(__FILE__.__LINE__);
                }
                \DB::commit();

                return $afterBalance;
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * 代付银行发生了反转.
     * @param $payout_order_id
     * @param string $errorMsg
     * @return false|mixed
     * @throws \Throwable
     */
    public static function payoutReverse($payout_order_id, $errorMsg = '')
    {
        $log = Journal::where('related_id', $payout_order_id)->get();
        if (! $errorMsg) {
            $errorMsg = 'payout order be reversed by bank';
        } else {
            $errorMsg .= '(reversed by bank)';
        }
        if (strlen($errorMsg) > 1020) {
            $errorMsg = substr($errorMsg, 0, 1020);
        }
        if ($log && count($log) == 1 && $log[0]->type == Journal::TYPE_OUT) {
            $log = $log[0];

            return RedisService::executeWithLock(self::getLockKey($log->merchantid), function () use ($log, $errorMsg) {
                $beforeBalance = $balance = self::getBalance($log->merchantid);

                \DB::beginTransaction();
                try {
                    if (false == Merchant::where('merchantid', $log->merchantid)->increment('balance', abs($log->amount), ['update_time' => time()])) {
                        throw new \Exception(__FILE__.__LINE__);
                    }

                    $afterBalance = bcadd($balance, abs($log->amount), 2);

                    if (false == Journal::add($log->merchantid, $log->related_id, Journal::TYPE_IN, abs($log->amount), $beforeBalance, $afterBalance, $errorMsg)) {
                        throw new \Exception(__FILE__.__LINE__);
                    }
                    \DB::commit();

                    return $afterBalance;
                } catch (\Exception $e) {
                    \DB::rollBack();
                    throw $e;
                }
            });
        }

        return false;
    }
}
