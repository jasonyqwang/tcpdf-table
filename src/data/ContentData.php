<?php
/**
 * User: Jason Wang
 * 内容
 */

namespace Jsyqw\PdfTable\data;


use Jsyqw\PdfTable\models\BaseModel;
use Jsyqw\PdfTable\models\ImageModel;
use Jsyqw\PdfTable\models\TextModel;

class ContentData extends BaseData
{
    /**
     * @var $pdf \TCPDF
     */
    public $pdf;

    /**
     * 段落的缩进单词数
     * @var int $indent_words
     */
    public $indent_words = 2;

    public function __construct($pdf, $indent_words = 2){
        $this->pdf = $pdf;
        $this->indent_words = $indent_words;
    }

    /**
     * 添加模型数据
     * @param $content TextModel | ImageModel | string
     */
    public function add($content){
        if(!($content instanceof BaseModel)){
            $content = new TextModel($content);
        }
        $this->data[] = $content;
    }
}