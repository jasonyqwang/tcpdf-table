<?php
/**
 * User: Jason Wang
 */

namespace Jsyqw\PdfTable\models;

class TextModel extends BaseModel
{
    /**
     * @var int[] 浅蓝色 E0EBFF
     */
    public $fill_color = [224, 235, 255];

    /**
     * @var array 文字颜色
     */
//    public $text_color = [0, 0, 0];
    public $text_color = [51,51,51]; //#333 深黑
//    public $text_color = [105,105,105]; //DimGray	暗淡的灰色	#696969
    public $text_style = '';
    /**
     * @var int 默认字体大小
     */
    public $font_size = 14;

    protected function init()
    {
        $this->show_type = self::SHOW_TYPE_TEXT;
    }
}