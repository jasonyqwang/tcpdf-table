<?php
/**
 * User: Jason Wang
 * @var int $col_num 列的个数
 * @var int $row_num 行的个数
 */

namespace Jsyqw\PdfTable\data;


use Jsyqw\PdfTable\exceptions\PdfTableException;
use Jsyqw\PdfTable\models\BaseModel;

class TableData extends BaseData
{
    /**
     * @var $pdf \TCPDF
     */
    public $pdf;
    /**
     * @var array $data [BaseModel]
     */
    public $data = [];

    protected $col_num = 2;
    protected $row_num = 0;
    /**
     * @var 列的比例，百分比
     */
    protected $col_percent = [];
    /**
     * @var array
     * 表格中，每一列的宽度
     */
    public $col_width = [];
    /**
     * @var 表格的宽度
     */
    protected $table_width;


    /**
     * TableData constructor.
     * @param $pdf \TCPDF
     * @param $pdf
     * @param int $col_num
     * @param array $col_percent [50, 50]
     * @param null $table_width
     */
    public function __construct($pdf, $col_num = 2, $col_percent = [], $table_width = null){
        $this->pdf = $pdf;
        $this->col_num = $col_num;
        $this->col_percent = $col_percent;
        $this->table_width = $table_width;

        $this->_initTableWidth();
    }

    /**
     * 初始化表格的宽度
     */
    private function _initTableWidth(){
        $table_width = $this->table_width;
        $col_percent = $this->col_percent;
        $col_num = $this->col_num;
        $pdf = $this->pdf;
        if(!$table_width){
            $page_width = $pdf->getPageWidth();
            $margin = $pdf->getMargins();
            $table_width = $page_width - $margin['left'] - $margin['right'];
            $this->table_width = $table_width;
        }
        //如果没有设置列的比例，则默认平均分配比例
        if(!$col_percent){
            $cell = round(100 / $col_num, 1);
            for ($i = 0; $i < $col_num; $i++){
                $col_percent[$i] = $cell;
            }
            $this->col_percent = $col_percent;
        }else{
            if(array_sum($col_percent) != 100){
                throw new PdfTableException("表格列的百分比之和要等于100！");
            }
        }

        if(count($col_percent) != $col_num){
            $rowColNum = count($col_percent);
            throw new PdfTableException("表格列的百分比个数{$rowColNum},和设定的表格列数{$this->col_num}不一致");
        }
        //每列的表格宽度
        foreach ($this->col_percent as $k => $v){
            $this->col_width[$k] = round($v * $table_width / 100, 1);
        }
    }

    /**
     * 初始化表的数据
     * @param array $data 表体的二维数组 eg:[[1,2,3],[4,5,6]]
     */
    public function initData($data = []){
        if(!$data){
            return;
        }
        //如果就是一行，而且第一行不是数组，则不需要再进行二次循环
        if(count($data) == 1 && !is_array($data[0])){
            $tableRowData = new TableRowData();
            $tableRowData->initData($data);
            $this->addRow($tableRowData);
        }else{
            foreach ($data as $k => $row){
                $tableRowData = new TableRowData();
                $tableRowData->initData($row);
                $this->addRow($tableRowData);
            }
        }
        return;
    }

    /**
     * 添加一行数据
     * @param $tableRowData TableRowData
     */
    public function addRow(TableRowData $tableRowData){
        //赋值 行数据
        $this->data[] = $tableRowData;

        $this->fitRowDataWidth($tableRowData);

        //动态增加一行数
        $this->row_num ++;
    }

    /**
     * 检查数据是否正确
     * 1.检查table的列的个数新增行的列数是否一致
     * @param $tableRowData TableRowData
     */
    protected function checkRowData($tableRowData){
        $rowColNum = count($tableRowData->data);
        if($this->col_num != $rowColNum){
            throw new PdfTableException("新增行的列数{$rowColNum},和设定的表格列数{$this->col_num}不一致");
        }
    }

    /**
     * 对于数据的宽度进行处理
     * @param $tableRowData TableRowData
     */
    protected function fitRowDataWidth($tableRowData){
        $pdf = $this->pdf;
        //检查
        $this->checkRowData($tableRowData);
        /**
         * @var $model BaseModel
         */
        foreach ($tableRowData->data as $k => &$model){
            //1.调整一行中数据的换行符的个数
            $dataWidth = $this->col_width[$k];
            $stringWidth = $pdf->GetStringWidth($model->value);
            $ln = ceil($stringWidth / $dataWidth);
            if($ln > $tableRowData->row_height_ln){
                $tableRowData->row_height_ln = $ln;
            }
            //2.设置列的宽度
            if($model->w == ''){
                $model->w = $dataWidth;
            }
        }

    }
}