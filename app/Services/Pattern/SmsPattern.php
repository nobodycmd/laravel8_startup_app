<?php

namespace App\Services\Pattern;

class SmsPattern
{
    public $_waring = [
        //You have made two incorrect login attempts to ICICI Bank Corporate Internet Banking on 21-10-2021 21:52:55. The next incorrect attempt will disable your login.
        'incorrect_login' => [
            'pattern' => '/.*?incorrect login attempts.*/i',
            'msg'     => '登录失败 密码可能被修改',
        ],
        //Dear Customer, as requested, your mobile no. has been updated for User ID VIJAYSHA. If you have not made this request, please call Customer Care immediately.
        'mobile_no_updated' => [
            'pattern' => '/.*?your mobile no\. has been updated.*/i',
            'msg'     => '手机号被修改',
        ],
    ];

    public $_pattern = [
        // 'hdfc' => [
        //     [
        //         // UPDATE: Your A/c XX2912 credited with INR 10.00 on 28-06-21 by A/c linked to mobile no XX7069 (IMPS Ref No. 117917326667) Available bal: INR 260.00
        //         'pattern' => '/UPDATE.*?(XX\d{4}).*INR (\d*\.\d{2}) .*?(XX\d{4}) \(.*?No\. (\d*)\) .*?INR (\d*\.\d{2})/i',
        //         'argument' => ['account', 'amount', 'mobile', 'utr', 'balance'],
        //     ],
        // ],
        'icici' => [
            [
                //Dear Customer, Account XXX905 is credited with INR 14.00 on 04-Aug-21 from segurapay@icici. UPI Ref. no. 121672917014 - ICICI Bank.
                //Dear Customer, Account XXX159 is credited with INR 100.00 on 15-Sep-21 from 9921934097@ybl. UPI Ref. no. 125863092719 - ICICI Bank.
                //Dear Customer, Account XXX159 is credited with INR 7072.98 on 07-Oct-21 from SUHAIL MUSHTAQ. UPI Ref. no. 128057810290 - ICICI Bank.
                'pattern'  => '/Dear.*?Account (XXX\d{3}).*INR (\d*\.\d{2}) .*?from (.*?). UPI.*?no\. (\d*).*/i',
                'argument' => ['account', 'amount', 'payer_name', 'utr'],
            ],
            [
                'pattern'  => '/(\d{6}) is the OTP.*ICICI.*/i',
                'argument' => ['otp'],
            ],
            [
                'pattern'  => '/.*?CIB.*?The OTP is (\d{6}).*/i',
                'argument' => ['otp'],
            ],
            [
                //Dear Customer, 7238 is OTP for InstaBIZ Merchant View Login (only collection options will be enabled).OTPs are SECRET. DO NOT disclose it to anyone. Bank NEVER asks for OTP.
                'pattern'  => '/.*?(\d{4}) is OTP.*/i',
                'argument' => ['otp'],
            ],
            [
                //40285 is your one time password to proceed on PhonePe. It is valid for 10 minutes. Do not share your OTP with anyone. mye1HyUG8DA
                'pattern'  => '/.*?(\d{5}).*?OTP.*/i',
                'argument' => ['otp'],
            ],
            [
                //BharatPe: 6704 is your OTP to register yourself on BharatPe Merchant App. BharatPe.com
                'pattern'  => '/.*?(\d{4}) is your OTP.*/i',
                'argument' => ['otp'],
            ],

        ],
        // 'idfc' => [
        //     [
        //         'pattern' => '/Your.*?(XXX\d{3}).*Rs\. (\d*\.\d{2}) .*?(XXX\d{3}) \(IMPS Ref no (\d*) \).*/i',
        //         'argument' => ['account', 'amount', 'mobile', 'utr'],
        //     ],
        //     [
        //         'pattern' => '/(\d{6}) is the OTP.*IDFC.*/i',
        //         'argument' => ['otp'],
        //     ]
        // ],

    ];

    public function checkWaring($text)
    {
        foreach ($this->_waring as $k => $v) {
            if ($v) {
                if (preg_match($v['pattern'], $text)) {
                    return $v['msg'];
                }
            }
        }

        return false;
    }

    public function match($text, $type = [])
    {
        if (empty($type)) {
            $type = array_keys($this->_pattern);
        }
        if (! is_array($type)) {
            $type = [$type];
        }
        foreach ($type as $t) {
            foreach ($this->_pattern[$t] as $v) {
                if ($v) {
                    preg_match($v['pattern'], $text, $match);
                    if ($match) {
                        array_shift($match);
                        $match = array_combine($v['argument'], $match);

                        return $match;
                    }
                }
            }
        }

        return false;
    }
}
