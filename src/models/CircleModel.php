<?php
/**
 * User: Jason Wang
 * 圆形
 */

namespace Jsyqw\PdfTable\models;

class CircleModel extends BaseModel
{

    public $drawColor = [255, 255, 255];
    /**
     * @var 半径
     */
    public $r;

    protected function init()
    {
        $this->show_type = self::SHOW_TYPE_CIRCLE;
        $this->align = "C";
    }
}