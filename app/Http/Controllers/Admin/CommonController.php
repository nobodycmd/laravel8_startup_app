<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OSS;

class CommonController extends Controller
{
    /**
     * 文件上传.
     */
    public function webFileUpload()
    {
        $return        = [];
        $return['url'] = '';

        $file = request()->file('file');

        if (! $file->isValid()) {
            return get_response_message(0, '文件验证失败', $return);
        }
        if (strpos(strtolower($file->getClientOriginalName()), 'php') !== false) {
            return get_response_message(0, '文件验证失败', $return);
        }

        $filename = uniqid() . '_' . $file->getClientOriginalName();

        $oss_fileuri = OSS::uploadFile(request()->getHost(), $file->getRealPath(), $filename);

        $return['url'] = $oss_fileuri;

        return get_response_message(0, 'success', $return);
    }
}
