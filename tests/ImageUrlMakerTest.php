<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 2017/10/28
 * Time: 19:31
 */

use cdcchen\oss\ImageUrlMaker;
use PHPUnit\Framework\TestCase;
use cdcchen\oss\base\ImageFormat;

class ImageUrlMakerTest extends TestCase
{
    const IMAGE_URL = 'example.jpg';
    const DELIMITER = '?x-oss-process=';

    /**
     * @var ImageUrlMaker
     */
    private static $url;

    public function setUp()
    {
        static::$url = new ImageUrlMaker(self::IMAGE_URL);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(ImageUrlMaker::class, new ImageUrlMaker(self::IMAGE_URL));
    }

    public function testGetMetaUrl()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/info';
        $this->assertEquals($excepted, static::$url->getMetaUrl());
    }

    public function testGetAverageHueUrl()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/average-hue';
        $this->assertEquals($excepted, static::$url->getAverageHueUrl());
    }

    public function testResizeThrowExpection()
    {
        $this->expectException(InvalidArgumentException::class);
        static::$url->resize();
    }

    public function testFitMaxSize()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_lfit,w_200,h_300';
        $this->assertEquals($excepted, static::$url->fitMaxSize(200, 300)->getUrl());
    }

    public function testFitMinSize()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_mfit,w_200,h_300';
        $this->assertEquals($excepted, static::$url->fitMinSize(200, 300)->getUrl());
    }

    public function testFitWidth()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_lfit,w_200';
        $this->assertEquals($excepted, static::$url->fitByWidth(200)->getUrl());
    }

    public function testFitHeight()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_lfit,h_200';
        $this->assertEquals($excepted, static::$url->fitByHeight(200)->getUrl());
    }

    public function testFixedSize()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fixed,w_200,h_300';
        $this->assertEquals($excepted, static::$url->fixedSize(200, 300)->getUrl());
    }

    public function testFixedWidth()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fixed,w_200';
        $this->assertEquals($excepted, static::$url->fixedWidth(200)->getUrl());
    }

    public function testFixedHeight()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fixed,h_200';
        $this->assertEquals($excepted, static::$url->fixedHeight(200)->getUrl());
    }

    public function testResizeFillWithWidthAndHeight()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fill,w_200,h_300';
        $this->assertEquals($excepted, static::$url->resizeFill(200, 300)->getUrl());
    }

    public function testResizeFillWithWidth()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fill,w_200';
        $this->assertEquals($excepted, static::$url->resizeFill(200)->getUrl());
    }

    public function testResizeFillWithWidthAndHeightAndLimit()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fill,w_200,h_300,limit_0';
        $this->assertEquals($excepted, static::$url->resizeFill(200, 300, 0)->getUrl());
    }

    public function testCircleCrop()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/circle,r_100';
        $this->assertEquals($excepted, static::$url->circle(100)->getUrl());
    }

    public function testCorpWithDefaultArgument()
    {
        $this->assertEquals(self::IMAGE_URL, static::$url->crop()->getUrl());
    }

    public function testCorp()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/crop,w_100,h_200,x_10,y_20,g_center';
        $this->assertEquals($excepted, static::$url->crop(100, 200, 10, 20, ImageUrlMaker::POSITION_CENTER)->getUrl());
    }

    /**
     * @param $width
     * @dataProvider indexCropInvalidWidth
     */
    public function testIndexCorpXThrowException($width)
    {
        $this->expectException(InvalidArgumentException::class);
        static::$url->indexCropX($width);
    }

    /**
     * @param $width
     * @dataProvider indexCropInvalidWidth
     */
    public function testIndexCorpYThrowException($width)
    {
        $this->expectException(InvalidArgumentException::class);
        static::$url->indexCropY($width);
    }

    public function indexCropInvalidWidth()
    {
        return [
            [0],
            [-1],
        ];
    }

    public function testIndexCorpX()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/indexcrop,x_100,i_0';
        $this->assertEquals($excepted, static::$url->indexCropX(100, 0)->getUrl());
    }

    public function testIndexCorpY()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/indexcrop,y_100,i_1';
        $this->assertEquals($excepted, static::$url->indexCropY(100, 1)->getUrl());
    }

    /**
     * @param $radius
     * @dataProvider roundedCornersInvalidRadius
     */
    public function testRoundedCornersThrowException($radius)
    {
        $this->expectException(RangeException::class);
        static::$url->roundedCorners($radius);
    }

    public function roundedCornersInvalidRadius()
    {
        return [
            [0],
            [4097],
        ];
    }

    public function testRoundedCorners()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/rounded-corners,r_30';
        $this->assertEquals($excepted, static::$url->roundedCorners(30)->getUrl());
    }

    /**
     * @param $value
     * @dataProvider autoOrientInvalidValue
     */
    public function testAutoOrientThrowException($value)
    {
        $this->expectException(RangeException::class);
        static::$url->autoOrient($value);
    }

    public function autoOrientInvalidValue()
    {
        return [
            [-1],
            [2],
        ];
    }

    /**
     * @param $value
     * @dataProvider autoOrientValidValue
     */
    public function testAutoOrient($value)
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/auto-orient,' . $value;
        $this->assertEquals($excepted, static::$url->autoOrient($value)->getUrl());
    }

    public function autoOrientValidValue()
    {
        return [
            [0],
            [1],
        ];
    }

    /**
     * @param $angel
     * @dataProvider rotateInvalidAngel
     */
    public function testRotateThrowException($angel)
    {
        $this->expectException(RangeException::class);
        static::$url->rotate($angel);
    }

    public function rotateInvalidAngel()
    {
        return [
            [-1],
            [361],
        ];
    }

    public function testRotate()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/rotate,90';
        $this->assertEquals($excepted, static::$url->rotate(90)->getUrl());
    }

    /**
     * @param $radius
     * @param $sigma
     * @dataProvider blurInvalidArguments
     */
    public function testBlurThrowException($radius, $sigma)
    {
        $this->expectException(RangeException::class);
        static::$url->blur($radius, $sigma);
    }

    public function blurInvalidArguments()
    {
        return [
            [-1, 51],
            [10, 51],
            [0, 51],
        ];
    }

    public function testBlur()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/blur,r_10,s_20';
        $this->assertEquals($excepted, static::$url->blur(10, 20)->getUrl());
    }

    /**
     * @param $value
     * @dataProvider brightInvalidArguments
     */
    public function testBrightThrowException($value)
    {
        $this->expectException(RangeException::class);
        static::$url->bright($value);
    }

    public function brightInvalidArguments()
    {
        return [
            [-101],
            [101],
        ];
    }

    public function testBright()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/bright,20';
        $this->assertEquals($excepted, static::$url->bright(20)->getUrl());
    }

    /**
     * @param $value
     * @dataProvider contrastInvalidArguments
     */
    public function testContrastThrowException($value)
    {
        $this->expectException(RangeException::class);
        static::$url->contrast($value);
    }

    public function contrastInvalidArguments()
    {
        return [
            [-101],
            [101],
        ];
    }

    public function testContrast()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/contrast,20';
        $this->assertEquals($excepted, static::$url->contrast(20)->getUrl());
    }

    /**
     * @param $value
     * @dataProvider sharpenInvalidArguments
     */
    public function testSharpenThrowException($value)
    {
        $this->expectException(RangeException::class);
        static::$url->sharpen($value);
    }

    public function sharpenInvalidArguments()
    {
        return [
            [49],
            [400],
        ];
    }

    public function testSharpenWithDefaultArgument100()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/sharpen,100';
        $this->assertEquals($excepted, static::$url->sharpen()->getUrl());
    }

    public function testSharpenWith150()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/sharpen,150';
        $this->assertEquals($excepted, static::$url->sharpen(150)->getUrl());
    }

    public function testFormatThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        static::$url->format('abc');
    }

    public function testFormat()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/format,gif';
        $this->assertEquals($excepted, static::$url->format(ImageFormat::GIF)->getUrl());
    }

    public function testFormatInterlace()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/format,jpg/interlace,1';
        $this->assertEquals($excepted, static::$url->format(ImageFormat::JPG, true)->getUrl());
    }

    public function testInterlaceWithDefaultArgument()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/interlace,1';
        $this->assertEquals($excepted, static::$url->interlace()->getUrl());
    }

    public function testInterlaceWithTrue()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/interlace,1';
        $this->assertEquals($excepted, static::$url->interlace(true)->getUrl());
    }

    public function testInterlaceWithFalse()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/interlace,0';
        $this->assertEquals($excepted, static::$url->interlace(false)->getUrl());
    }

    /**
     * @param $value
     * @dataProvider qualityInvalidArguments
     */
    public function testQualityThrowException($value)
    {
        $this->expectException(RangeException::class);
        static::$url->quality($value);
    }

    public function qualityInvalidArguments()
    {
        return [
            [-1],
            [101],
        ];
    }

    public function testQuality()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/quality,Q_90';
        $this->assertEquals($excepted, static::$url->quality(90)->getUrl());
    }
}