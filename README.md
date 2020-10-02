# PDF表格生成

# 需求背景

在使用PHP生成PDF文件的时候，经常生成一些表格的样式；

* 当选择使用 html 直接集成发现，可控性不强，样式也不好调整（tcpdf 只 支持部分的 css 样式）
* 于是想有一个可定制化的 table 表格就能很好满足当前定制需求
    - 表头可定制化
    - 实现常用的文本表格
    - 实现图片表格
    - 处理图文混合的表格
    - 针对表格中的数据进行可定制化扩展
    - 解决表中的字数自动换行问题
 

# 功能概要   
    
* 生成页面标题
* 生成 Table 的标题
* 生成 Table 内容
* PDF生成的表格如果是文字，则自动换行


# 安装

    composer require jsyqw/tcpdf-table
    
    
# 使用方法

## 1.图片 + 内容
```php
$imageFile = '/xxx/company.jpg';
//1.图片
$imageModel = new ImageModel($imageFile);
//$imageModel->h = 120;
$contentData = new ContentData($this->pdf);
$contentData->add($imageModel);
$drawContent = new DrawContent($contentData);
$drawContent->run();

$drawContent->drawHeight(5);

//2.文字
//2.1 居中标题
$textModel = new TextModel("公司介绍");
$textModel->font_size = 18;
$textModel->align = 'C';
$textModel->text_style = 'B';
$contentTitleData = new ContentTitleData($this->pdf);
$contentTitleData->add($textModel);
$drawContentTitle = new DrawContentTitle($contentTitleData);
$drawContentTitle->run();

$drawContent->drawHeight(5);

//2.2 段落内容,带首行缩进，以及自动换行的功能
$contentData = new ContentData($this->pdf);
$contentData->add('1.xxxxx。');
$contentData->add('2.xxxxx');
$contentData->add('3.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$drawContent = new DrawContent($contentData);
$drawContent->run();

```
![图文格式](https://github.com/jasonyqwang/tcpdf-table/blob/master/doc/图文格式.png)

## 2. 纯文字的表数据
有好几种写法，这里举例，最原始的写法！
```php

        $tableData = new TableData($this->pdf, 2);
        //表行数据
        $tableRowData = new TableRowData();
        $tableRowData->add("驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群驱蚊器翁群");
        $tableRowData->add("驱蚊器翁群");
        $tableData->addRow($tableRowData);

        $tableRowData = new TableRowData();
        $tableRowData->add("2-1 打发斯蒂芬斯蒂芬");
        $tableRowData->add("2-2 打发斯蒂芬斯蒂");
        $tableData->addRow($tableRowData);

        $tableRowData = new TableRowData();
        $tableRowData->add("驱蚊器啊翁群");
        $tableRowData->add("驱蚊器啊翁群");
        $tableData->addRow($tableRowData);

        $tableData = new TableData( $this->pdf,2);
        $drawTable = new DrawTable($tableData);
        $drawTable->drawTable();
```
![纯文字的表数据](https://github.com/jasonyqwang/tcpdf-table/blob/master/doc/table1.png)
![纯文字的表数据](https://github.com/jasonyqwang/tcpdf-table/blob/master/doc/table2.png)

## 3.图文格式
## 3.1 图文格式写法1
```php
        $imageFile = \Yii::$app->getBasePath() . DIRECTORY_SEPARATOR . 'xxx/test.jpeg';
        //页面： 图片 + 内容
        //1.图片
        $imageModel = new ImageModel($imageFile);
        $imageModel->h = 120;
        $contentData = new ContentData($this->pdf);
        $contentData->add($imageModel);
        $drawContent = new DrawContent($contentData);
        $drawContent->run();

        $drawContent->drawHeight(2);

        //2.文字
        //2.1 居中标题
        $textModel = new TextModel("公司介绍");
        $textModel->font_size = 16;
        $textModel->align = 'C';
        $textModel->text_style = 'B';
        $contentTitleData = new ContentTitleData($this->pdf);
        $contentTitleData->add($textModel);
        $drawContentTitle = new DrawContentTitle($contentTitleData);
        $drawContentTitle->run();

        //2.2 段落内容
        $contentData = new ContentData($this->pdf);
        $contentData->add('考虑到许多天文学数据库的规模都大得惊人，区分并不是一件简单的事，但是，人工智能算法给此事带来前所未有的希望。');
        $contentData->add('近日，英国华威大学的大卫·阿姆斯特朗博士（David Armstrong）领导的研究团队开发了一项新的机器学习算法，从美国航空航天局（NASA）的陈旧数据中，识别出太阳系外的行星。他们确认了50颗系外行星的存在，从海王星大小的气体巨行星到比地球还小的岩石世界，无所不包。');
        $contentData->add('方法是，计算出每个行星的成为候选星球的概率。');
        $contentData->add('历史上天文学家一般相信在太阳系以外存在着其它行星，然而它们的普遍程度和性质则是一个谜。直至1990年代人类才首次确认系外行星的存在，而自2002年起每年都有超过20个新发现的系外行星。');
        $drawContent = new DrawContent($contentData);
        $drawContent->run();
```
![](https://github.com/jasonyqwang/tcpdf-table/blob/master/doc/图文格式.png) 
 
## 3.2 简化的图文格式写法
```php
        $image = $data['img_url'];

        $drawContentTitle = new DrawContentTitle();
        $drawContentTitle->draw($this->pdf, '底坑安装尺寸');

        $drawContent = new DrawContent();
        $imageModel = new ImageModel($image);
        $drawContent->draw($this->pdf, $imageModel);
```

## 4.图文表格混合格式
```php
        $drawContentTitle = new DrawContentTitle();
        $drawContentTitle->draw($this->pdf, '22');

        $tableData = new TableData($this->pdf, 4, [20, 30, 25, 25]);
        $tableData->initData($data);
        $drawTable = new DrawTable($tableData);
        $drawTable->run();

        $envData = $this->getEnvData($data1);
        $tableData = new TableData($this->pdf, 2, [50, 50]);
        $tableData->initData($envData['data']);
        $drawTable = new DrawTable($tableData);
        $drawTable->run();

        $textArr = [
            '1、xxx。',
            '2、xxxxxxxxx',
            'xxx：',
            '1）xxx',
            '2）xxx',
            '3）xxx'
        ];
        foreach ($textArr as $v){
            $drawContent = new DrawContent();
            $drawContent->draw($this->pdf, $v, 'L', 0);
        }
```
![](https://github.com/jasonyqwang/tcpdf-table/blob/master/doc/图文表格混合格式.png) 

## 5.文字-图片-表格混合格式
```php
        $drawContentTitle = new DrawContentTitle();
        $drawContentTitle->draw($this->pdf, '1');

        $textArr = [
            'xxxx',
            'xxxx',
        ];
        $drawContent = new DrawContent();
        foreach ($textArr as $v){
            $drawContent->draw($this->pdf, $v, 'L', 0);
        }

        $tableData = new TableData($this->pdf, 2, [50, 50]);
        $tableData->initData($data);
        $drawTable = new DrawTable($tableData);
        $drawTable->run();
``` 
![](https://github.com/jasonyqwang/tcpdf-table/blob/master/doc/文字-图片-表格混合格式.png)    
    
# 其他
    
我是在Yii2框架中编写的扩展，

这里提醒下，为了能快速测试，不需要发布到 Packagist ，可以做如下操作：
* 在项目的 vendor 目录下，建立文件夹  建立 tcpdf-table 项目
* 配置 Yii2 框架中的 composer.json
* 修改 autoload 配置,添加如下的配置,注意 必须 vendor 开头的
```json
// ....  
// class autoloading specs
    "autoload": {
        "psr-4": {
            "Jsyqw\\PdfTable\\": "vendor/tcpdf-table/src/"
        }
    }
//...
```
*  执行 composer dump-autoload