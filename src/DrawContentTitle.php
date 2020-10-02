<?php
/**
 * User: Jason Wang
 * 内容标题
 */

namespace Jsyqw\PdfTable;


use Jsyqw\PdfTable\data\ContentTitleData;
use Jsyqw\PdfTable\models\TextModel;

class DrawContentTitle extends DrawContent
{
    /**
     * @param $pdf
     * @param $content
     * @param int $indent_words
     */
    public function drawLeftTitle($pdf, $content, $indent_words = 0){
       $this->draw($pdf, $content, 'L', $indent_words, 0);
    }

    /**
     * 快捷画标题, 添加模型数据
     * @param $pdf
     * @param models\ImageModel|TextModel|string $content
     * @param string $align
     * @param int $indent_words
     * @param int $marginBottom 距离下面的距离
     */
    public function draw($pdf, $content, $align = 'C', $indent_words = 0, $marginBottom = 5){
        $data = new ContentTitleData($pdf, $indent_words);
        if(!($content instanceof BaseModel)){
            $content = new TextModel($content);
            $content->font_size = $data->default_font_size;
            $content->text_style = $data->default_text_style;
            $content->align = $align ? $align : $data->default_align;
        }
        $data->add($content);
        self::__construct($data);
        $this->run();
        $this->drawHeight($marginBottom);
    }
}