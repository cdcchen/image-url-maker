<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 2016/12/14
 * Time: 15:31
 */

namespace ImageUrlMaker\base;


/**
 * Class ImageFormat
 * @package ImageUrlMaker\base
 */
class ImageFormat
{
    const JPG  = 'jpg';
    const PNG  = 'png';
    const WEBP = 'webp';
    const BMP  = 'bmp';
    const GIF  = 'gif';
    const SRC  = 'src';

    /**
     * @return array
     */
    public static function collections()
    {
        return [
            self::PNG,
            self::BMP,
            self::GIF,
            self::JPG,
            self::WEBP,
            self::SRC,
        ];
    }
}