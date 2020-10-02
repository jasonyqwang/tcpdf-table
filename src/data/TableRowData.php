<?php
/**
 * User: Jason Wang
 * 表格的行数据
 */

namespace Jsyqw\PdfTable\data;


use Jsyqw\PdfTable\models\BaseModel;
use Jsyqw\PdfTable\models\TextModel;

class TableRowData extends BaseData
{
    //需要换几行
    public $row_height_ln = 1;

    /**
     * @var array $data [BaseModel]
     */
    public $data = [];

    /**
     * 添加行的内容
     * @param $content mixed | BaseModel
     */
    public function add($content){
        if(!($content instanceof BaseModel)){
            $content = new TextModel($content);
        }
        $this->data[] = $content;
    }

    /**
     * 初始化表的行数据
     * @param array $data 行的数组
     * @return void
     */
    public function initData($data = []){
        if(!$data){
            return;
        }
        foreach ($data as $k => $v){
           $this->add($v);
        }
        return;
    }
}