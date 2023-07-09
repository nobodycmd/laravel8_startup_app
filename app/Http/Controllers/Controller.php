<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function uploadToLocal()
    {
        $file = request()->file('file');

        if (! $file->isValid()) {
            return false;
        } else {
            if (strpos(strtolower($file->getClientOriginalName()), 'php') !== false) {
                return false;
            }

            $filename      = $file->storeAs('file', get_unique_number('Bank').'.'.$file->getClientOriginalExtension());
            $filepath      = '/static/upload/'.$filename;

            return public_path().$filepath;
        }
    }

    public function redirect($url, string $tip = '')
    {
        if ($tip) {
            return redirect($url)->with('pageTip', $tip);
        }

        return redirect($url);
    }

    /**
     * 导出csv.
     * @param string $title 标题
     * @param array  $aryLetterTitle 字母标题 such as ['A' => '商户号','B' => '订单号']
     * @param array $data 数据 such as [['A12891','JFKI02192']]
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function getExportCsv($title, $aryLetterTitle, $data = [])
    {
        //实例化工作表
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        //设置单元格标题名称
        foreach ($aryLetterTitle as $k => $v) {
            $spreadsheet->getActiveSheet()->setCellValue($k.'1', $v);
        }

        //填充数据
        $spreadsheet->getActiveSheet()->fromArray($data, null, 'A2');

        //导出csv
        header('Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:attachment;filename='.$title.'.csv');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        $writer->setUseBOM(true);
        $writer->setPreCalculateFormulas(false);
        $writer->save('php://output');

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }

    /**
     * 导出为 Excel.
     * @param string $title such as 2023订单
     * @param array $letterTitle such as [
     *  'A' => '姓名',
     *  'B' => '年龄',
     * ]
     * @param array $data such as [
     *  ['zs','20'],
     *  ['ls','18'],
     * ]
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function getExportExcel($title, $letterTitle, $data = [])
    {
        $sLetter = 'A';
        $eLetter = 'Z';

        //实例化工作表
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        //设置当前工作表标题
        $spreadsheet->getActiveSheet()->setTitle($title);

        //设置字体
        $spreadsheet->getActiveSheet()->getStyle($sLetter.'1:'.$eLetter.'1')->getFont()->setBold(true)->setName('Arial')->setSize(11);

        //设置颜色
        $spreadsheet->getActiveSheet()->getStyle($sLetter.'1:'.$eLetter.'1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

        //设置列宽
        foreach ($letterTitle as $k => $v) {
            $spreadsheet->getActiveSheet()->getColumnDimension($k)->setWidth(25);
        }

        //设置对齐
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle($sLetter.'1:'.$eLetter.'1')->applyFromArray($styleArray);

        //设置单元格标题名称
        foreach ($letterTitle as $k => $v) {
            $spreadsheet->getActiveSheet()->setCellValue($k.'1', $v);
        }

        //数据条数
        $count = count($data) + 1;

        //设置字体
        $spreadsheet->getActiveSheet()->getStyle($sLetter.'2:'.$eLetter.$count)->getFont()->setBold(false)->setName('Arial')->setSize(11);

        //设置对齐
        $spreadsheet->getActiveSheet()->getStyle($sLetter.'2:'.$eLetter.$count)->applyFromArray($styleArray);

        //填充数据
        $spreadsheet->getActiveSheet()->fromArray($data, null, $sLetter.'2');

        //导出Excel
        header('Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:attachment;filename='.$title.'.xlsx');

        $wirter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $wirter->save('php://output');

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}
