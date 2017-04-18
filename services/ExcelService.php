<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 4/16/17
 * Time: 7:52 PM
 */

namespace app\services;

class ExcelService
{
    public static function export($data)
    {
        $export_excel_title = "统计报表";

        $excel = new \PHPExcel();
        // Set document properties
        $excel->getProperties()->setCreator("DIX")
            ->setLastModifiedBy("DIX")
            ->setTitle("")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("")
            ->setCategory("");

        $excel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 1, 'ID')
            ->setCellValueByColumnAndRow(1, 1, '名称');

        foreach ($data as $key => $v) {
            $id = 1;
            $name = "";

            $excel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0, $id)
                ->setCellValueByColumnAndRow(1, $name);
        }

        $excel->setActiveSheetIndex(0);
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < 14; $i++) {
            $column_name = $chars[$i];
            $excel->getActiveSheet()->getColumnDimension($column_name)->setWidth(20);
            $excel->getActiveSheet()->getStyle($column_name)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $excel->getActiveSheet()->setTitle($export_excel_title);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excel->setActiveSheetIndex(0);

        $filename = $export_excel_title . date('YmdHis', time());
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $excelWrite = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $excelWrite->save('php://output');
        exit();
    }
}