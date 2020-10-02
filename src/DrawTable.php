<?php
/**
 * User: Jason Wang
 */

namespace Jsyqw\PdfTable;


use Jsyqw\PdfTable\data\TableData;
use Jsyqw\PdfTable\data\TableRowData;
use Jsyqw\PdfTable\data\TableTitleData;
use Jsyqw\PdfTable\exceptions\PdfTableException;
use Jsyqw\PdfTable\models\BaseModel;
use Jsyqw\PdfTable\models\CircleModel;
use Jsyqw\PdfTable\models\ImageModel;
use Jsyqw\PdfTable\models\TextModel;
use Jsyqw\PdfTable\traits\TraitTableStyle;

class DrawTable extends BaseDraw
{
    use TraitTableStyle;

    public $table_data;
    /**
     * 是否颜色填充
     * @var bool
     */
    public $is_fill = true;
    /**
     * 是否显示边框
     * @var bool
     */
    public $is_border = true;
    public $first_fill = true;

    /**
     * 绘制 table
     * @param $pdf \TCPDF
     * @param $tableData TableData
     */
    public function __construct($tableData = null){
        $this->table_data = $tableData;
        $this->pdf = $tableData->pdf;
    }

    /**
     * 绘制数据
     */
    public function run(){
        if(!$this->table_data){
            return;
        }
        $pdf = $this->pdf;
        $pdf->SetFillColor($this->fill_color[0], $this->fill_color[1], $this->fill_color[2]); //浅蓝色 E0EBFF
        $pdf->SetTextColor($this->text_color[0], $this->text_color[1], $this->text_color[2]);
        $pdf->SetFontSize($this->font_size);
        $pdf->SetDrawColor($this->draw_border_color[0],$this->draw_border_color[2],$this->draw_border_color[2]);
        $pdf->SetLineWidth(0.1);

        $fill = $this->first_fill;
        foreach ($this->table_data->data as $k => $model){
            $this->drawTableRow($model, $k, $fill);
            $fill = !$fill;
        }

        //如果是最后一行的时候，添加边框
        if($this->table_data->data && $this->is_border){
            $pdf->Cell(array_sum($this->table_data->col_width), 1, '', 'T');
            $pdf->Ln();
        }
    }

    public function drawTableRow(TableRowData $tableRowData, $index, $fill = false){
        if(!$this->is_fill){
            $fill = false;
        }
        $pdf = $this->pdf;
        $x = $pdf->GetX();
        /**
         * @var $model ImageModel | CircleModel | TextModel
         */
        foreach ($tableRowData->data as $k => $model){
            $pdf->SetCellPadding(2);
            if($this->is_border){
                if($index == 0){
                    $border = 'LRT';
                }else{
                    $border = 'LR';
                }
            }else{
                $border = 0;
            }
            $padding = $pdf->getCellPaddings();
            $paddingHeight = $padding['T'] + $padding['B'];
            $cellHeight = $pdf->getCellHeight($pdf->getFontSize());
            $h = $cellHeight;
            if($tableRowData->row_height_ln > 1){
                $h += ($tableRowData->row_height_ln - 1) * ($cellHeight - $paddingHeight);
            }
            $w = $this->table_data->col_width[$k];
//            $pdf->SetFillColor($this->fill_color[0], $this->fill_color[1], $this->fill_color[2]); //浅蓝色 E0EBFF
            $pdf->SetDrawColor($this->draw_border_color[0],$this->draw_border_color[2],$this->draw_border_color[2]);
            $pdf->SetLineWidth(0.1);
            //如果设置了填充色，则以model的设置为准
            if($model->is_fill !== null){
                $fill = $model->is_fill;
            }
            switch ($model->show_type){
                case BaseModel::SHOW_TYPE_IMAGE:
                    if($k){
                        $x += $this->table_data->col_width[$k-1];
                    }
                    $imageX = $x;
                    $imageY = $pdf->GetY() + $padding['T'];
                    $imageW = $model->w;

                    $h = $model->h;
                    $imageH = $h - $padding['T'];
                    if($model->align === "C"){
                        $imageX = $x + ($w - $model->w) / 2;
                    }
                    if(!$model->h){
                        throw new PdfTableException("请设置图片的高度");
                    }
                    //如果有描边的话
                    if($border){
                        $pdf->Cell($w, $h, '', $border, 0, '', 0, '', 0, true);
                    }
                    //为了显示边框，当宽度等于table的宽度的时候，自动减小image的宽度
                    if($imageW == $w){
                        $imageW = $w - $padding['L'];
                        $imageX += $padding['L'] /2;
                    }
                    $pdf->Image(
                        $model->value,
                        $imageX,
                        $imageY,
                        $imageW,
                        $imageH,
                        '',
                        '',
                        $model->align,
                        false,
                        300,
                        '',
                        false,
                        false,
                        0,
                        false,
                        false,
                        false,
                        false
                    );
                    break;
                case BaseModel::SHOW_TYPE_TEXT:
                    if($model->h > $h){
                        $h = $model->h + $padding['T'];
                    }
                    $pdf->MultiCell(
                        $w,
                        $h,
                        $model->value,
                        $border,
                        $model->align,
                        $fill,
                        0,
                        "",
                        "",
                        true,
                        0,
                        false,
                        true,
                        0,
                        "M",
                        true
                    );
                    break;
                case BaseModel::SHOW_TYPE_CIRCLE:
                    if($k){
                        $x += $this->table_data->col_width[$k-1];
                    }
                    //圆形的 X的坐标点
                    $cricleX = $x + $model->r;
                    if($model->align === "C"){
                        $cricleX = $x + ($w) / 2;
                    }
                    $cricleY = $pdf->GetY() + $model->r + $padding['T'];
                    //如果有描边的话
                    if($border){
                        $h = $model->r * 2 + $padding['T'];
                        $pdf->Cell($w, $h, '', $border, 0, '', 0, '', 0, true);
                    }
                    $pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(105,105,105)));
                    $pdf->RegularPolygon($cricleX, $cricleY, $model->r, 200, 0, true, 'DF','', $model->drawColor, 'FD');
                    break;
                default:

            }
        }
        $pdf->Ln();
    }
}