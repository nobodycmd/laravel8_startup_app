<?php

use App\Services\FlakeService;
use Illuminate\Support\Facades\Log;
use Earnp\GoogleAuthenticator\Facades\GoogleAuthenticator;

//页面是否展示元素
if (!function_exists('is_show_html_element')) {
    function is_show_html_element($route_name)
    {
        $show = true;
        if ($show) {
            return true;
        }
        $ary_route_name = request()->get('permission');
        return in_array($route_name, $ary_route_name);
    }
}

if (!function_exists('getExportExcel')) {
    function getExportExcel($sLetter, $eLetter, $title, $letterTitle, $data = [], $text = array())
    {
        //实例化工作表
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        //设置当前工作表标题
        $spreadsheet->getActiveSheet()->setTitle($title);

        //设置字体
        $spreadsheet->getActiveSheet()->getStyle($sLetter . '1:' . $eLetter . '1')->getFont()->setBold(true)->setName('Arial')->setSize(11);

        //设置颜色
        $spreadsheet->getActiveSheet()->getStyle($sLetter . '1:' . $eLetter . '1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

        //设置列宽
        foreach ($letterTitle as $k => $v) {
            $spreadsheet->getActiveSheet()->getColumnDimension($k)->setWidth(25);
        }

        if ($text) {
            foreach ($text as $t) {
                $spreadsheet->getActiveSheet()->getStyle($t)->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            }
        }

        //设置对齐
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle($sLetter . '1:' . $eLetter . '1')->applyFromArray($styleArray);

        //设置单元格标题名称
        foreach ($letterTitle as $k => $v) {
            $spreadsheet->getActiveSheet()->setCellValue($k . '1', $v);
        }

        //数据条数
        $count = count($data) + 1;

        //设置字体
        $spreadsheet->getActiveSheet()->getStyle($sLetter . '2:' . $eLetter . $count)->getFont()->setBold(false)->setName('Arial')->setSize(11);

        //设置对齐
        $spreadsheet->getActiveSheet()->getStyle($sLetter . '2:' . $eLetter . $count)->applyFromArray($styleArray);

        //填充数据
        $spreadsheet->getActiveSheet()->fromArray($data, null, $sLetter . '2');

        //导出Excel
        header('Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:attachment;filename=" . $title . ".xlsx");
        $wirter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $wirter->save('php://output');
    }
}

if(!function_exists('dates_between_dates')) {
    function dates_between_dates($startDate, $endDate)
    {
        $aryDates = [$startDate];

        $current_date = $startDate;
        while (strtotime($current_date) < strtotime($endDate)) {
            //日期进1
            $current_date = date('Y-m-d', strtotime('+1 days', strtotime($current_date)));
            $aryDates[] = $current_date;
        }
        return $aryDates;
    }
}

if(!function_exists('get_response_message')){
    /**
     * 获取响应消息（统一使用JSON格式返回数据）
     */
    function get_response_message($code, $message, $data, $errorCode=0){
        $return = [];
        $return['code'] = $code;
        $return['message'] = $message;
        $return['data'] = $data;

        return response()->json($return)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}

if (!function_exists('apiRender')) {
    function apiRender($data = [])
    {
        $code = 0;
        $message = "success";
        $errorCode = 0;

        return get_response_message($code, $message, $data, $errorCode);
    }
}

if (!function_exists('apiRenderError')) {
    function apiRenderError($errorCode, $errorMessage)
    {
        $return = [];
        $return['code'] = 1;
        $return['message'] = $errorMessage;
        $return['data'] = [];
//        $return['errno'] = $errorCode;
        return response()->json($return)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}


if(!function_exists('get_unique_number')){
    /**
     * 获取唯一编号
     */
    function get_unique_number($start = ''){
        $str = date('ymd');
        $id = \App\Services\RedisService::incrValue($start . 'id_generate_' . $str,1);
        return $start.$str . str_pad($id,8,'0',STR_PAD_LEFT);
    }
}

if(!function_exists('get_flake_nextid')){
    /**
     * 获取唯一编号
     */
    function get_flake_nextid($start = ''){

        $dataCenterID = env('DATACENTER_ID', 0);
        $workerID = env('WORKER_ID', 0);

        return $start.FlakeService::generate($dataCenterID, $workerID);
    }
}

if(!function_exists('get_millisecond')){
    /**
     * 获取毫秒级别的时间戳
     */
    function get_millisecond(){
        $time = explode(' ',microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time = explode('.',$time);
        $time = $time[0];
        return $time;
    }
}

if(!function_exists('get_logs')){
    /**
     * 日志
     * @param string|array $log 日志
     * @param string $dir 目录
     */
    function get_logs($log,$dir = 'default'){
        try{
            $path = storage_path('logs/'.$dir);
            if(!is_dir($path)){
                mkdir($path,0777,true);
            }

            $file = fopen($path.'/'.date('Ymd').'.log','a+');
            $txt = date('Y-m-d H:i:s')."\n";
            $txt .= (is_string($log) ? $log : json_encode($log,JSON_UNESCAPED_UNICODE))."\n";
            $txt .= "\n";
            fwrite($file, $txt);
            fclose($file);
        }catch (Exception $e){
            throw $e;
        }
        return true;
    }
}

if(!function_exists('get_curl_post')){
    /**
     * get_curl_post
     * @param string $url url
     * @param array $data 数据
     * @return bool|string
     */
    function get_curl_post($url, $data){
//        return \Illuminate\Support\Facades\Http::asForm()->post($url, $data)->body();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        $errno  = curl_errno($ch);
        $errMsg = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ((false === $result) || (0 !== $errno)) {
            $error = "curl errno:$errno, errmsg:" .$errMsg;
            //Log::channel("curl")->error($error ,[$url, $data]);

            return false;
        }

        if (200 != $httpCode) {
            $error = "http code:$httpCode,response:$result";
            //Log::channel("curl")->error($error ,[$url, $data]);

            return false;
        }

        return $result;
    }
}


if(!function_exists('get_sign')){
    /**
     * 签名
     * @param array $parameter 参数
     * @param string $secretKey 密钥
     * @return string
     */
    function get_sign($parameter,$secretKey)
    {
        unset($parameter['sign']);

        ksort($parameter);
        reset($parameter);

        $signStr = '';
        foreach ($parameter as $key => $value) {
            if(strlen(trim($value))){
                $signStr .= $key . '=' . $value . '&';
            }
        }
        $signStr .= 'key=' . $secretKey;

        $sign = strtolower(md5($signStr));

        return $sign;
    }
}

if(!function_exists('get_google_authenticator')){
    /**
     * Google身份验证器
     */
    function get_google_authenticator()
    {
        $createSecret = \Earnp\GoogleAuthenticator\GoogleAuthenticator::CreateSecret();
        return $createSecret['secret'];
    }
}


if(!function_exists('get_google_authenticator_checkcode')){
    /**
     * Google身份验证器校验码
     */
    function get_google_authenticator_checkcode($secret,$onecode)
    {
        return \Earnp\GoogleAuthenticator\GoogleAuthenticator::CheckCode($secret, $onecode);
    }
}


if(!function_exists('get_source_domain')){
    /**
     * 获取来源域名
     */
    function get_source_domain(){
        $sourceDomain = parse_url($_SERVER['HTTP_REFERER'])['host'];
        return $sourceDomain;
    }
}

if(!function_exists('get_source_ip')){
    /**
     * 获取来源IP
     */
    function get_source_ip(){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENTIP'])) {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENTIP')) {
            $ip = getenv('HTTP_CLIENTIP');
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = '';
        }
        $pos = strpos($ip, ',');
        if ($pos > 0) {
            $ip = substr($ip, 0, $pos);
        }

        return trim($ip);
    }
}

if(!function_exists('get_http_post')){
    /**
     * get_http_post
     * @param $url
     * @param $data
     * @return bool|string
     */
    function get_http_post($url, $data){
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'timeout' => 60
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url,false,$context);
        return $result;
    }
}

if(!function_exists('get_form_post')){
    /**
     * get_form_post
     * @param $url
     * @param $data
     */
    function get_form_post($url, $data){
        $html = '<form name="form" action="'.$url.'" method="post">';
        foreach($data as $key=>$value){
            $html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }
        $html .= '</form>';
        $html .= '<script>';
        $html .= 'document.form.submit();';
        $html .= '</script>';
        exit($html);
    }
}

if (! function_exists('get_list_sort')) {
    /**
     * 对结果集进行排序
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param array $sort 排序类型
     * asc正向排序 desc逆向排序 nat自然排序
     * @return array
     */
    function get_list_sort($list,$field, $sort = 'asc') {
        if(is_array($list)){
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sort) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ( $refer as $key=> $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }
}


if(!function_exists('get_day_start_time')){
    /**
     * 当日开始时间戳
     */
    function get_day_start_time($timestamp){
        return \Illuminate\Support\Carbon::createFromTimestamp($timestamp)->startOfDay()->getTimestamp();
//        return mktime(0,0,0,date('m',$timestamp),date('d',$timestamp),date('Y',$timestamp));
    }
}

if(!function_exists('get_day_end_time')){
    /**
     * 当日结束时间戳
     */
    function get_day_end_time($timestamp){
        return \Illuminate\Support\Carbon::createFromTimestamp($timestamp)->endOfDay()->getTimestamp();
//        return mktime(0,0,0,date('m',$timestamp),date('d',$timestamp)+1,date('Y',$timestamp))-1;
    }
}

if(!function_exists('get_date_from_range')){
    /**
     * 获取两个时间段内的所有日期
     */
    function get_date_from_range($day)
    {
        $startTimestamp = get_day_start_time(strtotime('-'.($day-1).' day'));
        $endTimestamp = get_day_start_time(time());

        $days = ($endTimestamp-$startTimestamp)/86400+1;

        $date = [];
        for($i = 0; $i < $days; $i++){
            $date[] = date('Y-m-d',$startTimestamp+(86400*$i));
        }

        return $date;
    }
}

if (!function_exists('convertAmountToCn')) {
    /**
     * 将数值金额转换为中文大写金额
     * @param $amount float 金额(支持到分)
     * @param $type   int   补整类型,0:到角补整;1:到元补整
     * @return mixed 中文大写金额
     */
    function convertAmountToCn($amount, $type = 0) {
        // 判断输出的金额是否为数字或数字字符串
        if(!is_numeric($amount)){
            return "要转换的金额只能为数字!";
        }

        // 金额为0,则直接输出"零元整"
        if($amount == 0) {
            return "零元整";
        }

        // 金额不能为负数
        if($amount < 0) {
            return "要转换的金额不能为负数!";
        }

        // 金额不能超过万亿,即12位
        if(strlen(intval($amount)) > 12) {
            return "要转换的金额不能为万亿及更高金额!";
        }

        // 预定义中文转换的数组
        $digital = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
        // 预定义单位转换的数组
        $position = array('仟', '佰', '拾', '亿', '仟', '佰', '拾', '万', '仟', '佰', '拾', '元');

        // 将金额的数值字符串拆分成数组
        $amountArr = explode('.', $amount);

        // 将整数位的数值字符串拆分成数组
        $integerArr = str_split($amountArr[0], 1);

        // 将整数部分替换成大写汉字
        $result = '';
        $integerArrLength = count($integerArr);     // 整数位数组的长度
        $positionLength = count($position);         // 单位数组的长度
        for($i = 0; $i < $integerArrLength; $i++) {
            // 如果数值不为0,则正常转换
            if($integerArr[$i] != 0){
                $result = $result . $digital[$integerArr[$i]] . $position[$positionLength - $integerArrLength + $i];
            }else{
                // 如果数值为0, 且单位是亿,万,元这三个的时候,则直接显示单位
                if(($positionLength - $integerArrLength + $i + 1)%4 == 0){
                    $result = $result . $position[$positionLength - $integerArrLength + $i];
                }
            }
        }

        // 如果小数位也要转换
        if($type == 0) {
            // 将小数位的数值字符串拆分成数组
            $decimalArr = str_split($amountArr[1], 1);
            // 将角替换成大写汉字. 如果为0,则不替换
            if($decimalArr[0] != 0){
                $result = $result . $digital[$decimalArr[0]] . '角';
            }
            // 将分替换成大写汉字. 如果为0,则不替换
            if($decimalArr[1] != 0){
                $result = $result . $digital[$decimalArr[1]] . '分';
            }
        }else{
            $result = $result . '整';
        }
        return $result;
    }
}

if (!function_exists('getClientIP')) {
    function getClientIP()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENTIP'])) {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENTIP')) {
            $ip = getenv('HTTP_CLIENTIP');
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = '';
        }

        $pos = strpos($ip, ',');
        if ($pos > 0) {
            $ip = substr($ip, 0, $pos);
        }

        return trim($ip);
    }
}
if (!function_exists('isBlockIpRegion')) {
    function isBlockIpRegion($ip, $method = "-")
    {
        $city_blacklist = ["delhi"];
        $region_blacklist = ["bihar", "telangana", "andhra pradesh", "assam", "odisha", "tamil nadu"];

        $microtime = microtime(true);
        if(!is_dir(base_path('/storage/logs/ipregion/'))){
            mkdir(base_path('/storage/logs/ipregion/'));
        }
        $logFIle = base_path('/storage/logs/ipregion/').date('Ymd').'.log';
        $logFIleHF = $logFIle. '.hf';
        $logFIleWF = $logFIle. '.wf';

        $url = "http://ip-api.com/json/".$ip;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            $errno  = curl_errno($ch);
            $errMsg = curl_error($ch);
            curl_close($ch);
            if ((false === $result) || (0 !== $errno)) {
                file_put_contents($logFIleWF, sprintf("[%s] [%s] %s, %s\n", date('m-d H:i:s', time()), (microtime(true) - $microtime), $errno, $errMsg),FILE_APPEND);
                return false;
            }
            file_put_contents($logFIleHF, sprintf("[%s] %.2f %s %s %s\n", date('m-d H:i:s', time()), (microtime(true) - $microtime), $ip, $method, $result), FILE_APPEND);

            if($result){
                $result = json_decode($result, true);
                if($result && isset($result['regionName']) && isset($result['city'])){
                    if(in_array(strtolower($result['regionName']), $region_blacklist) || in_array(strtolower($result['city']), $city_blacklist)){
                        file_put_contents($logFIle, sprintf("[%s] %s %s %s\n", date('m-d H:i:s', time()), $ip, "Y", $method), FILE_APPEND);

                        return true;
                    }
                }
            }
            file_put_contents($logFIle, sprintf("[%s] %s %s %s\n", date('m-d H:i:s', time()), $ip, "N", $method), FILE_APPEND);

        } catch (\Throwable $th) {
            file_put_contents($logFIleWF, sprintf("[%s] [%s] %s\n", date('m-d H:i:s', time()), (microtime(true) - $microtime), $th->getMessage()), FILE_APPEND);
            throw $th;
        }

        return false;
    }
}

if (!function_exists('sendMessage')) {
    function sendMessage($text, $chatId,$user="",$bot="")
    {
        if(!$bot){
            $bot = \App\Services\TgNameService::xiaocainiao;
        }

        get_logs($text . "--" . $chatId, "tg_logs");
        switch ($bot) {
            case \App\Services\TgNameService::xiaocainiao:
                $key = \App\Services\TgNameService::xiaocainiao_key;
                break;
            default:
                $key = \App\Services\TgNameService::monitor_key;
        }
        $tgUrl = "https://api.telegram.org/bot" . $key . "/sendmessage";
        if ($user) {
            $message = $text . $user;
        } else {
            $message = $text;// + ' @xx1 ';
        }
        $data = [
            'text' => $message,
            'chat_id' => $chatId,
            'parse_mode' => 'HTML'
        ];

        return getCurl($tgUrl, $data);
    }
}


if(!function_exists("verifiContainZh")){
    function verifiContainZh($str){
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $str)) {
            return true;
        }else{
            return  false;
        }
    }
}

if (!function_exists('getCurl')){
    function getCurl($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        $errno  = curl_errno($ch);
        $errMsg = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ((false === $result) || (0 !== $errno)) {
            $error = "curl errno:$errno, errmsg:" .$errMsg;
            //Log::channel("curl")->error($error ,[$url, $data]);
            get_logs($error,"sendTgErrorLog");
            return false;
        }

        if (200 != $httpCode) {
            $error = "http code:$httpCode,response:$result";
            get_logs($error,"sendTgErrorLog");
            //Log::channel("curl")->error($error ,[$url, $data]);

            return false;
        }

        return $result;
    }
}
