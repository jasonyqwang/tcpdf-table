<?php
/**
 * User: Jason Wang
 * 绘图的基类
 */

namespace Jsyqw\PdfTable;


abstract class BaseDraw
{
    /**
     * @var $pdf \TCPDF
     */
    public $pdf;

    abstract public function run();

    /**
     * 绘制一个换行的高度
     * @param int $h
     */
    public function drawHeight($h = 5){
        $pdf = $this->pdf;
        $lh = $pdf->GetY();
        $pdf->SetY($lh + $h);
    }

    /**
     * 预计增加的高度
     * @param int $h
     */
    protected function checkPageBreak($h = 0){
        $pdf = $this->pdf;
        $ph = $pdf->getPageHeight();
        /*
         [
          "left" => 5
          "right" => 5
          "top" => 15
          "bottom" => 10
          "header" => 5
          "footer" => 5
          "cell" => array:4 [
            "T" => 1
            "R" => 1
            "B" => 1
            "L" => 1
          ]
          "padding_left" => 1
          "padding_top" => 1
          "padding_right" => 1
          "padding_bottom" => 1
        ]
         */
        $h = (int)$h;
        $margin = $pdf->getMargins();
        $y = $pdf->getY();
        if($y + $h + $margin['footer'] > $pdf->getPageHeight()){
            $pdf->AddPage();
        }
    }

    /**
     * 预计字符串的高度
     * @param $str
     * @param string $container_w
     * @return float|int
     */
    protected function maybeStrHeight($str, $container_w = ''){
        $pdf = $this->pdf;
        $h = $pdf->getCellHeight($pdf->getFontSize());

        return $this->getStrLnNum($str, $container_w) * $h;
    }

    /**
     * 获取一个字符串换行的个数
     * @param $str
     * @param $container_w
     */
    protected function getStrLnNum($str, $container_w = ''){
        $pdf = $this->pdf;
        if(!$container_w){
            $container_w = $this->geContainerWidth();
        }
        $stringWidth = $pdf->GetStringWidth($str);
        $lnNum = ceil($stringWidth / $container_w);
        return $lnNum;
    }

    /**
     * 去除边框的容器宽度
     * @return int
     */
    protected function geContainerWidth(){
        $pdf = $this->pdf;
        $pageWidth = $pdf->getPageWidth();
        $margin = $pdf->getMargins();
        $containerWidth = $pageWidth - $margin['left'] - $margin['right'];
        return $containerWidth;
    }
}