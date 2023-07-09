<?php

namespace App\Services;

class QueueNameService
{
    public const PAYIN_XIAFA = 'xiafa';

    public const all_send_tg = 'all_send_tg';

    public const payin_webhook = 'payin_webhook';

    public const payout_webhook = 'payout_webhook';

    public const payin_utr_from_telegram = 'payin_utr_from_telegram'; //telegram 补单

    public const PayinUtrDeviceMatchJob = 'PayinUtrDeviceMatchJob'; //卡收 代收python脚本流水匹配处理

    public const BankOrderFileHandleJob = 'BankOrderFileHandleJob'; //卡收 代收银行流水文件上传处理

    public const UtrUserMatchJob = 'UtrUserMatchJob'; //卡收 用户侧 上报进行补单
}
