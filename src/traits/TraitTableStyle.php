<?php
/**
 * User: Jason Wang
 */

namespace Jsyqw\PdfTable\traits;


trait TraitTableStyle
{
    /**
     * #006DAC logo蓝色 0,109,172
     */
    public $fill_title_color = [0,109,172];
    /**
     * @var array 文字颜色
     */
    public $text_title_color = [255, 255, 255];
    /**
     * @var int 默认字体大小
     */
    public $font_title_size = 14;

    /**
     * @var int[] 浅蓝色 E0EBFF
     */
    public $fill_color = [224, 235, 255];
    /**
     * @var array 文字颜色
     */
    public $text_color = [51,51,51]; //#333 深黑
//    public $text_color = [0, 0, 0];
    /**
     * @var int 默认字体大小
     */
    public $font_size = 12;

    /**
     * 灰色 #C4C4C4    196, 196, 196
     * 灰色 #ccc       204,204,204
     * 白烟	#F5F5F5	   245,245,245
     * 绘图线条颜色
     * @var int[]
     */
    public $draw_border_color = [204,204,204];
    public $draw_image_border_color = [245,245,245];
}