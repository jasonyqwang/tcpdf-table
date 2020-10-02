<?php
/**
 * User: Jason Wang
 * 图片
 */

namespace Jsyqw\PdfTable\models;

class ImageModel extends BaseModel
{
    protected function init()
    {
        $this->show_type = self::SHOW_TYPE_IMAGE;
        $this->align = "C";
    }
}