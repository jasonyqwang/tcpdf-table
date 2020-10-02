<?php
/**
 * Created by PhpStorm.
 * @author  jason <jasonwang1211@gmail.com>
 */

namespace Jsyqw\PdfTable;


use Jsyqw\PdfTable\data\ContentData;
use Jsyqw\PdfTable\exceptions\PdfTableException;
use Jsyqw\PdfTable\models\BaseModel;
use Jsyqw\PdfTable\models\ImageModel;
use Jsyqw\PdfTable\models\TextModel;

class DrawContent extends BaseDraw
{
    /**
     * @var null
     */
    public $content_data;

    /**
     * 绘制内容
     * @param $contentData ContentData
     */
    public function __construct($contentData = null){
        $this->content_data = $contentData;
        if($contentData){
            $this->pdf = $contentData->pdf;
        }
    }

    /**
     * 快捷画内容
     * 添加模型数据
     * @param $content TextModel | ImageModel | string
     */
    public function draw($pdf, $content, $align = '', $indent_words = 2){
        $data = new ContentData($pdf, $indent_words);
        if(!($content instanceof BaseModel)){
            $content = new TextModel($content);
            $content->align = $align;
        }
        $data->add($content);
        self::__construct($data);
        $this->run();
    }

    /**
     * 绘制数据
     */
    public function run()
    {
        if (!$this->content_data) {
            return;
        }
        $pdf = $this->pdf;
        /**
         * @var $model TextModel|ImageModel
         */
        foreach ($this->content_data->data as $k => $model){
            switch ($model->show_type){
                case BaseModel::SHOW_TYPE_IMAGE:
                    $pdf->SetCellPadding(2);
                    $w = $model->w;
                    if(!$w){
                        $w = $this->geContainerWidth();
                    }
                    if(!$model->h){
                        $maybeHeight = $model->h;
                        //检查是否换行
                        $this->checkPageBreak($maybeHeight);
                    }
                    $margin = $pdf->getMargins();
                    $x = $margin['left'];
                    $pdf->Image(
                        $model->value,
                        $x,
                        '',
                        $model->w,
                        $model->h,
                        '',
                        '',
                        $model->align,
                        false,
                        300,
                        'C',
                        false,
                        false,
                        array('LTRB' => array('width' => 0.1,  'join' => 'miter', 'dash' => 1, 'color' => array(245,245,245))),//白烟
                        "B",
                        false,
                        true,
                        false
                    );
                    break;
                case BaseModel::SHOW_TYPE_TEXT:
                    $pdf->SetCellPadding(1);
                    $pdf->SetFillColor($model->fill_color[0], $model->fill_color[1], $model->fill_color[2]);
                    $pdf->SetFont('', $model->text_style, $model->font_size);
                    $pdf->SetTextColor($model->text_color[0], $model->text_color[1], $model->text_color[2]);
                    //模拟首行缩进
                    $text = $this->getIndentText($model->value);

                    if(!$model->h){
                        $maybeHeight = $this->maybeStrHeight($text, $model->w);
                    }else{
                        $maybeHeight = $model->h;
                    }
                    //检查是否换行
                    $this->checkPageBreak($maybeHeight);

                    $pdf->MultiCell(
                        $model->w,
                        $model->h,
                        $text,
                        0,
                        $model->align,
                        $model->is_fill,
                        0,
                        "",
                        '',
                        true,
                        0,
                        false,
                        true
                    );
                    if($model->h){
                        $maybeHeight = $model->h;
                    }
                    break;
                default:
            }
            $pdf->Ln();
        }
    }

    /**
     * 对字符进行缩进处理
     * @param $value
     * @return string
     */
    private function getIndentText($value){
        if(!$this->content_data->indent_words){
            return $value;
        }
        $prefix = '';
        for ($i = 0; $i < $this->content_data->indent_words; $i++){
            $prefix .= '    ';
        }
        return $prefix . $value;
    }
}