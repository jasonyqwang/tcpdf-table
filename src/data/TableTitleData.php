<?php
/**
 * User: Jason Wang
 * @var int $col_num 列的个数
 */

namespace Jsyqw\PdfTable\data;


use Jsyqw\PdfTable\exceptions\PdfTableException;
use Jsyqw\PdfTable\models\BaseModel;

class TableTitleData extends TableData
{
    /**
     * TableTitleData constructor.
     * @param $pdf \TCPDF
     * @param $pdf
     * @param int $col_num
     * @param array $col_percent [50, 50]
     * @param null $table_width
     */
    public function __construct($pdf, $col_num = 2, $col_percent = [], $table_width = null){
        parent::__construct($pdf, $col_num, $col_percent, $table_width);
    }

}