<?php
/**
 * User: Jason Wang
 * 快捷绘制表格,包含表头和体
 */

namespace Jsyqw\PdfTable;

use Jsyqw\PdfTable\data\TableData;
use Jsyqw\PdfTable\data\TableTitleData;

class DrawTables
{
    protected $data = [];
    /**
     * @var \TCPDF
     */
    public $pdf;
    public $header_col_percent;//表头 - 百分比
    public $data_col_percent;//表体 - 百分比
    public $header_is_border;
    public $data_is_border;
    //设置表头边框的颜色
    public $header_draw_border_color;
    //设置表体边框的颜色
    public $data_draw_border_color;


    /**
     * DrawTables constructor.
     * @param $pdf
     * @param $data ["header" => [], "data" => []]
     */
    public function __construct($pdf, $data)
    {
        $this->pdf = $pdf;
        $this->data = $data;
    }

    public function draw(){
        //头部
        if($this->data['header']){
            $colNum = 1;
            if(isset($this->data['header'][0]) && !is_array($this->data['header'][0])){
                $this->data['header'] = [$this->data['header']];
            }
            $colNum = count($this->data['header'][0]);
            $tableData = new TableTitleData($this->pdf, $colNum, $this->header_col_percent);
            $tableData->initData($this->data['header']);
            $drawTable = new DrawTableTitle($tableData);
            if($this->header_is_border !== null){
                $drawTable->is_border = $this->header_is_border;
            }
            if($this->header_draw_border_color !== null){
                $drawTable->draw_border_color = $this->header_draw_border_color;
            }
            $drawTable->run();
        }
        //表格内容部分
        if($this->data['data']){
            $colNum = count($this->data['data'][0]);
            $tableData = new TableData($this->pdf, $colNum,  $this->data_col_percent);
            $tableData->initData($this->data['data']);
            $drawTable = new DrawTable($tableData);
            if($this->data['header']){
                $drawTable->first_fill = 0;
            }
            if($this->data_is_border !== null){
                $drawTable->is_border = $this->data_is_border;
            }
            if($this->data_draw_border_color !== null){
                $drawTable->draw_border_color = $this->data_draw_border_color;
            }
            $drawTable->run();
        }
    }
}