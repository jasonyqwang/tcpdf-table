<?php
/**
 * User: Jason Wang
 */

namespace Jsyqw\PdfTable;


use Jsyqw\PdfTable\data\TableData;
use Jsyqw\PdfTable\data\TableRowData;
use Jsyqw\PdfTable\data\TableTitleData;
use Jsyqw\PdfTable\models\BaseModel;

class DrawTableTitle extends DrawTable
{
    public $first_fill = true;

//    /**
//     * 直接画表头
//     * @param $header ["AA", "BB", "CC"]
//     */
//    public function draw($pdf, $data){
//        if(!$data){
//            return;
//        }
//
//        $tableTitleData = new TableTitleData($pdf, count($header));
//        $tableRowData = new TableRowData();
//        foreach ($header as $k => $v){
//            $tableRowData->add($v);
//        }
//        $tableTitleData->addRow($tableRowData);
//        $this->table_title_data = $tableTitleData;
//    }

    public function run()
    {
        if(!$this->table_data){
            return;
        }
        $pdf = $this->pdf;
        if($this->is_fill){
            $pdf->SetFillColor($this->fill_title_color[0], $this->fill_title_color[1], $this->fill_title_color[2]); //浅蓝色 E0EBFF
            $pdf->SetTextColor($this->text_title_color[0], $this->text_title_color[1], $this->text_title_color[2]);
            $pdf->SetDrawColor($this->fill_title_color[0],$this->fill_title_color[2],$this->fill_title_color[2]);
        }else{
            $pdf->SetDrawColor($this->draw_border_color[0],$this->draw_border_color[2],$this->draw_border_color[2]);
        }
        $pdf->SetFont('', 'B', $this->font_title_size);
        $pdf->SetLineWidth(0.1);

        $fill = $this->first_fill;
        foreach ($this->table_data->data as $k => $model){
            $this->drawTableRow($model, $k, $fill);
        }
    }
}