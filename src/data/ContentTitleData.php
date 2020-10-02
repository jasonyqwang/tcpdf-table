<?php
/**
 * User: Jason Wang
 * 内容
 */

namespace Jsyqw\PdfTable\data;


use Jsyqw\PdfTable\models\BaseModel;
use Jsyqw\PdfTable\models\TextModel;
use Jsyqw\PdfTable\traits\TraitTableStyle;

class ContentTitleData extends ContentData
{
    public $default_font_size = 18;
    public $default_text_style = "B";
    public $default_align = "L";

    /**
     * 缩进单词数
     * @var int $indent_words
     */
    public $indent_words = 0;

    /**
     * 添加模型数据
     * @param $content TextModel | ImageModel | string
     */
    public function add($content){
        if(!($content instanceof BaseModel)){
            $content = new TextModel($content);
            $content->font_size = $this->default_font_size;
            $content->text_style = $this->default_text_style;
            $content->align = $this->default_align;
        }else{
            if($content->font_size == ''){
                $content->font_size = $this->default_font_size;
            }
            if($content->text_style == ''){
                $content->text_style = $this->default_text_style;
            }
            if($content->align == ''){
                $content->align = $this->default_align;
            }
        }
        $this->data[] = $content;
    }
}