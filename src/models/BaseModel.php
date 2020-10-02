<?php
/**
 * User: Jason Wang
 * 表单数据的基类
 */

namespace Jsyqw\PdfTable\models;


class BaseModel
{
    const SHOW_TYPE_TEXT = 'text'; //文本
    const SHOW_TYPE_IMAGE = 'image'; //图片
    const SHOW_TYPE_CIRCLE = 'circle'; //圆形
    /**
     * @var 显示类型
     */
    public $show_type;
    /**
     * @var 对应的值
     */
    public $value;

    //当前模型的宽高
    public $w = '';
    public $h = '';

    //位置
    public $align = '';

    /**
     * 是否填充背景色
     * @var bool
     */
    public $is_fill;

    /**
     * 初始化
     * BaseModel constructor.
     * @param $value
     */
    public function __construct($value, $align = '')
    {
        $this->value = $value;
        $this->align = $align;

        $this->init();
    }

    protected function init(){

    }
}