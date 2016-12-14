# CURL Http Client

## composer 安装

```
composer require cdcchen/image-url-maker:^1.0.0
```

## 使用教程

### 实例化 `Maker`

```php
use ImageUrlMaker\oss\Maker;

$maker = new Maker($url);
```

### 获取预定义样式地址

```php
$url = $maker->getStyleUrl($styleName);
```

### 获取参数方式地址

```php
$url = $maker->fitWidth($width)->getUrl();
```
or 
```php
$url = (string)$maker->fitWidth($width);
```

## 参数方式预定义方法列表

缩放
* `resize($mode = null, $width = 0, $height = 0, $limit = null, $color = null)`
* `resizeFitMax($width = 0, $height = 0, $limit = null)`
* `resizeFitMin($width = 0, $height = 0, $limit = null)`
* `resizeFill($width = 0, $height = 0, $limit = null)`
* `resizePad($width = 0, $height = 0, $limit = null, $color = null)`
* `resizeFixed($width = 0, $height = 0, $limit = null)`
* `fitMaxSize($width = 0, $height = 0)`
* `fitMinSize($width = 0, $height = 0)`
* `fitWidth($width)`
* `fitHeight($height)`
* `fixedSize($width = 0, $height = 0)`
* `fixedWidth($width)`
* `fixedHeight($height)`

裁剪
* `circle($radius)`
* `roundedCorners($radius)`
* `crop($width = 0, $height = 0, $x = 0, $y = 0, $g = null)`
* `indexCropX($width, $i = 0)`
* `indexCropY($height, $i = 0)`

旋转
* `autoOrient($value = 1)`
* `rotate($angle)`

效果
* `blur($radius, $sigma)`
* `bright($value)`
* `contrast($value)`
* `sharpen($value = 100)`

格式
* `format($format, $interlace = false)`
* `interlace($value = true)`
* `quality($quality, $absolute = true)`

水印
> 暂无