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
    public $header_style = [
        'is_border' => true,
        'fill_title_color' => [0,109,172],//标题背景
        'draw_border_color' => [0,109,172],//绘图线条颜色
        'text_title_color' => [255, 255, 255],//文字颜色
        'font_title_size' => 14,//默认字体大小
        'fill_color' => [224, 235, 255],//浅蓝色
        'text_color' => [51,51,51], //#333 深黑
    ];
    public $data_style = [
        'is_border' => true,
        'draw_border_color' => [204,204,204],//绘图线条颜色
        'font_size' => 12,//默认字体大小
        'fill_color' => [224, 235, 255],//浅蓝色
        'text_color' => [51,51,51], //#333 深黑
    ];

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
            foreach ($this->header_style as $k => $v){
                $drawTable->$k = $v;
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
            foreach ($this->data_style as $k => $v){
                $drawTable->$k = $v;
            }
            $drawTable->run();
        }
    }
}