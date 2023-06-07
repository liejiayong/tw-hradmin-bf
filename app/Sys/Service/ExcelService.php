<?php

namespace App\Sys\Service;

class ExcelService
{
    /**
     * 静态对象
     * @var ExcelService
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return ExcelService|static
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }


    /**
     * 导出excel
     * @param $fileName
     * @param $data
     */
    function downExcel($fileName, $data) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
        header('Cache-Control: max-age=0');


        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
   xmlns:x="urn:schemas-microsoft-com:office:excel"
   xmlns="http://www.w3.org/TR/REC-html40">
  <head>
   <meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
   <meta http-equiv=Content-Type content="text/html; charset=gb2312">
   <!--[if gte mso 9]><xml>
   <x:ExcelWorkbook>
   <x:ExcelWorksheets>
     <x:ExcelWorksheet>
     <x:Name></x:Name>
     <x:WorksheetOptions>
       <x:DisplayGridlines/>
     </x:WorksheetOptions>
     </x:ExcelWorksheet>
   </x:ExcelWorksheets>
   </x:ExcelWorkbook>
   </xml><![endif]-->
  </head>';
        echo "<table style=\"vnd.ms-excel.numberformat:@\">";
        foreach ($data as $val) {
            echo "<tr>";
            foreach ($val as $v) {
                $v = iconv('UTF-8', 'GBK', $v);
                echo "<td>{$v}</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        exit();

    }


}
