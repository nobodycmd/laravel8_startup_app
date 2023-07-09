<?php

namespace App\Console\Commands;

use App\Models\BankOrderFile;
use App\Services\PayinBankFileService;
use App\Services\PayoutOrderFileService;
use Illuminate\Console\Command;

class Demo extends Command
{
    protected $signature = 'Demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '临时脚本';

    public function handle()
    {
        $bankOrderFile = BankOrderFile::query()->find(45905);

        //将远程文件下载到本地
        $bankOrderFile->local_path = $this->downLoadFile($bankOrderFile->path, storage_path('bankstatementfiles'), $bankOrderFile->file_name);

        echo $bankOrderFile->local_path."\n";

        if ($bankOrderFile->statement_type == BankOrderFile::STATEMENT_TYPE_PAYIN) {
            $pbfs = new PayinBankFileService();
            $pbfs->payinOrderFileHandle($bankOrderFile);
        }

        if ($bankOrderFile->statement_type == BankOrderFile::STATEMENT_TYPE_PAYOUT) {
            PayoutOrderFileService::acceptPayoutUploadFileFromBankToHandle($bankOrderFile);
        }
    }

    public function downLoadFile($remoteUrl, $path, $saveFileName = '')
    {
        if (is_dir($path) == false) {
            @mkdir($path, 0777, true);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remoteUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);

        preg_match('/file\d{14}(.*)?\//', $remoteUrl, $match);
        $ext = $match[1];

        $resource = fopen($path.'/'.$saveFileName.'.'.$ext, 'w+');

        fwrite($resource, $file);

        fclose($resource);

        $source      =  $path.'/'.$saveFileName.'.'.$ext;

        return $source;
    }
}
