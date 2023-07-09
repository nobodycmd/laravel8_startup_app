<?php

namespace App\Services;

class ExcelService
{
    /**
     * 非常规EXCEL读取编码模版.
     *
     * @param string $filepath 文件路径
     * @param int $startRow
     * @param callable $func 接受 [
    'row' => $row,
    'worksheet' => $worksheet,
    ]
    $description = $worksheet->getCell("F" . $row)->getValue();
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function readExcelWithBizFunc($filepath, $startRow)
    {
        if (strtolower(pathinfo($filepath)['extension']) == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $reader->load($filepath);

        $worksheet = $spreadsheet->getActiveSheet();

        $rowCount = $worksheet->getHighestRow();

        try {
            for ($row = $startRow; $row <= $rowCount; $row++) {
//            $description = $worksheet->getCell("F" . $row)->getValue();
//            $amount = $worksheet->getCell("D" . $row)->getValue();
//            $time = $worksheet->getCell("C" . $row)->getValue();
//            $balance = $worksheet->getCell("G" . $row)->getValue();
            }
        } finally {
            $worksheet->disconnectCells();
            unset($worksheet);
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }
    }

    /**
     * 假设第一行是 表头
     * 读取出来的数据，每行都进行表头 key=> value 复制对应.
     *
     * \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($one['日期']) excel 常见日期读取
     *
     * @param string $filepath 文件路径
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function readExcelWithTitle($filepath)
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filepath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filepath);
        $aryXlsData  = $spreadsheet->getActiveSheet()->toArray(); //'',true,false
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        if ($aryXlsData && count($aryXlsData) > 1) {
            $i        = 0;
            $aryTitle = [];
            foreach ($aryXlsData as &$one) {
                $i++;
                if ($i == 1) {
                    $aryTitle = $one;
                    continue;
                }
                foreach ($aryTitle as $index => $title) {
                    //给头
                    $one[$title] = $one[$index];
                    //去掉数字索引
                    unset($one[$index]);
                }
            }
            unset($aryXlsData[0]);
        }
        reset($aryXlsData);

        return $aryXlsData;
    }
}
