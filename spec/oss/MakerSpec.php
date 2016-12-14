<?php

namespace spec\ImageUrlMaker\oss;

use ImageUrlMaker\base\ImageFormat;
use ImageUrlMaker\oss\Maker;
use PhpSpec\ObjectBehavior;
use RangeException;

class MakerSpec extends ObjectBehavior
{
    const IMAGE_URL = 'example.jpg';
    const DELIMITER = '?x-oss-process=';

    public function it_is_initializable()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldHaveType(Maker::class);
    }

    public function it_get_image_info()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/info';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->getMetaUrl()->shouldBeLike($excepted);
    }

    public function it_get_image_average_hue()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/average-hue';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->getAverageHueUrl()->shouldBeLike($excepted);
    }


    public function it_is_resize_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(\InvalidArgumentException::class)->during('resize');
    }

    public function it_is_fit_max_size()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_mfit,w_200,h_300';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->fitMaxSize(200, 300)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_fit_min_size()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_lfit,w_200,h_300';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->fitMinSize(200, 300)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_fit_width()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_lfit,w_200';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->fitWidth(200)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_fit_height()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_lfit,h_200';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->fitHeight(200)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_fixed_size()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fixed,w_200,h_300';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->fixedSize(200, 300)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_fixed_width()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fixed,w_200';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->fixedWidth(200)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_fixed_height()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fixed,h_200';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->fixedHeight(200)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_resize_fill()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fill,w_200,h_300';
        $this->resizeFill(200, 300)->getUrl()->shouldBeLike($excepted);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fill,w_200';
        $this->resizeFill(200)->getUrl()->shouldBeLike($excepted);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/resize,m_fill,w_200,h_300,limit_0';
        $this->resizeFill(200, 300, 0)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_circle_crop()
    {
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/circle,r_100';
        $this->beConstructedWith(self::IMAGE_URL);
        $this->circle(100)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_corp_not_params()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->crop()->getUrl()->shouldBeLike(self::IMAGE_URL);
    }

    public function it_is_corp()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/crop,w_100,h_200,x_10,y_20,g_center';
        $this->crop(100, 200, 10, 20, Maker::ALIGN_CENTER)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_index_corp_x_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(\InvalidArgumentException::class)->during('indexCropX', [0]);
        $this->shouldThrow(\InvalidArgumentException::class)->during('indexCropX', [-1]);
    }

    public function it_is_index_corp_x()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/indexcrop,x_100,i_0';
        $this->indexCropX(100, 0)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_index_corp_y_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(\InvalidArgumentException::class)->during('indexCropY', [0]);
        $this->shouldThrow(\InvalidArgumentException::class)->during('indexCropY', [-1]);
    }

    public function it_is_index_corp_y()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/indexcrop,y_100,i_1';
        $this->indexCropY(100, 1)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_rounded_corners_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('roundedCorners', [0]);
        $this->shouldThrow(RangeException::class)->during('roundedCorners', [4097]);
    }

    public function it_is_rounded_corners_y()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/rounded-corners,r_30';
        $this->roundedCorners(30)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_auto_orient_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('autoOrient', [-1]);
        $this->shouldThrow(RangeException::class)->during('autoOrient', [2]);
    }

    public function it_is_auto_orient()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/auto-orient,1';
        $this->autoOrient()->getUrl()->shouldBeLike($excepted);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/auto-orient,0';
        $this->autoOrient(0)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_rotate_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('rotate', [-1]);
        $this->shouldThrow(RangeException::class)->during('rotate', [361]);
    }

    public function it_is_rotate()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/rotate,90';
        $this->rotate(90)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_blur_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('blur', [-1, 51]);
        $this->shouldThrow(RangeException::class)->during('blur', [10, 51]);
        $this->shouldThrow(RangeException::class)->during('blur', [0, 51]);
    }

    public function it_is_blur()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/blur,r_10,s_20';
        $this->blur(10, 20)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_bright_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('bright', [-101]);
        $this->shouldThrow(RangeException::class)->during('bright', [101]);
    }

    public function it_is_bright()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/bright,20';
        $this->bright(20)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_contrast_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('contrast', [-101]);
        $this->shouldThrow(RangeException::class)->during('contrast', [101]);
    }

    public function it_is_contrast()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/contrast,20';
        $this->contrast(20)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_sharpen_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('sharpen', [49]);
        $this->shouldThrow(RangeException::class)->during('sharpen', [400]);
    }

    public function it_is_sharpen()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/sharpen,100';
        $this->sharpen()->getUrl()->shouldBeLike($excepted);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/sharpen,150';
        $this->sharpen(150)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_format_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(\InvalidArgumentException::class)->during('format', ['abc']);
    }

    public function it_is_format()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/format,gif';
        $this->format(ImageFormat::GIF)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_format_interlace()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/format,jpg/interlace,1';
        $this->format(ImageFormat::JPG, true)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_interlace()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/interlace,1';
        $this->interlace()->getUrl()->shouldBeLike($excepted);
        $this->interlace(true)->getUrl()->shouldBeLike($excepted);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/interlace,0';
        $this->interlace(false)->getUrl()->shouldBeLike($excepted);
    }

    public function it_is_quality_throw()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $this->shouldThrow(RangeException::class)->during('quality', [-1]);
        $this->shouldThrow(RangeException::class)->during('quality', [101]);
    }

    public function it_is_quality()
    {
        $this->beConstructedWith(self::IMAGE_URL);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/quality,Q_90';
        $this->quality(90)->getUrl()->shouldBeLike($excepted);
        $excepted = self::IMAGE_URL . self::DELIMITER . 'image/quality,q_80';
        $this->quality(80, false)->getUrl()->shouldBeLike($excepted);
    }




    public function it_is_test()
    {
        echo PHP_EOL;
        $maker = new Maker('http://ydbcdn.oss-cn-hangzhou.aliyuncs.com/5.jpg');
        echo $maker->fitWidth(200)->getUrl(), PHP_EOL;
        echo $maker->fitHeight(200)->getUrl(), PHP_EOL;
        echo $maker->circle(200)->getUrl(), PHP_EOL;
        echo $maker->format(ImageFormat::JPG, true)->getUrl(), PHP_EOL;
        echo $maker->quality(90, 0)->getUrl(), PHP_EOL;
    }
}
