<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/7/2
 * Time: 13:46
 */

namespace cdcchen\oss;

use cdcchen\oss\base\BoolToStringTrait;
use cdcchen\oss\base\ImageFormat;
use cdcchen\oss\base\MakerInterface;
use cdcchen\oss\base\ParamsTrait;


/**
 * Class ImageUrlMaker
 * @package cdcchen\oss\oss
 */
class ImageUrlMaker implements MakerInterface
{
    use ParamsTrait, BoolToStringTrait, ImageMakerTrait;

    const POSITION_NORTHWEST = 'nw';
    const POSITION_NORTH     = 'north';
    const POSITION_NORTHEAST = 'ne';
    const POSITION_EAST      = 'east';
    const POSITION_SOUTHEAST = 'se';
    const POSITION_SOUTH     = 'south';
    const POSITION_SOUTHWEST = 'sw';
    const POSITION_WEST      = 'west';
    const POSITION_CENTER    = 'center';

    /**
     * @var string image thumb styleName
     */
    protected $styleName;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $styleDelimiter = '!';
    /**
     * @var string
     */
    protected $processDelimiter = '?x-oss-process=image/';
    /**
     * @var array
     */
    protected $actions = [];

    /**
     * Maker constructor.
     * @param string $url
     * @param string $styleDelimiter
     */
    public function __construct($url, $styleDelimiter = null)
    {
        $this->url = $url;
        if (!empty($styleDelimiter)) {
            $this->setStyleDelimiter($styleDelimiter);
        }
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setStyleDelimiter($value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('$styleDelimiter is can not empty.');
        }

        $this->styleDelimiter = $value;
        return $this;
    }

    /**
     * @return array
     */
    public static function positions()
    {
        return [
            self::POSITION_CENTER,
            self::POSITION_EAST,
            self::POSITION_NORTH,
            self::POSITION_NORTHEAST,
            self::POSITION_NORTHWEST,
            self::POSITION_SOUTH,
            self::POSITION_SOUTHEAST,
            self::POSITION_SOUTHWEST,
            self::POSITION_WEST
        ];
    }


    /**
     * @param string $style
     * @return string
     */
    public function getStyleUrl($style)
    {
        if (empty($this->styleDelimiter)) {
            throw new \RuntimeException('Please set styleDelimiter');
        }
        return $this->url . $this->styleDelimiter . $style;
    }

    /**
     * Build final url
     * @return string
     */
    public function getUrl()
    {
        $params = static::buildParams($this->getParams());

        $url = $this->url;
        if (!empty($params)) {
            $url .= '?x-oss-process=image/' . $params;
        }

        return $url;
    }

    /**
     * Build fetch average-hue url
     * @return string
     */
    public function getAverageHueUrl()
    {
        return $this->setParam('average-hue')->getUrl();
    }

    /**
     * Build fetch image meta url
     * @return string
     */
    public function getMetaUrl()
    {
        return $this->setParam('info')->getUrl();
    }

    ################## resize ######################

    /**
     * @param null|string $mode Value: lfit|mfit|fill|pad|fixed
     * @param int $width
     * @param int $height
     * @param null|int $limit Value: 0 or 1
     * @param null|string $color Value range: 000000 - FFFFFF
     * @return static
     */
    public function resize($mode = null, $width = 0, $height = 0, $limit = null, $color = null)
    {
        if ($width <= 0 && $height <= 0) {
            throw new \InvalidArgumentException('Width or Height must set a value.');
        }

        $values = [];
        if ($mode !== null) {
            $values[] = 'm_' . $mode;
        }
        if ($width > 0) {
            $values[] = 'w_' . $width;
        }
        if ($height > 0) {
            $values[] = 'h_' . $height;
        }
        if ($limit !== null) {
            $values[] = 'limit_' . $limit;
        }
        if ($color !== null) {
            $values[] = 'color_' . $color;
        }

        return $this->setParam('resize', join(',', $values));
    }

    /**
     * @param int $width
     * @param int $height
     * @param null $limit
     * @return $this
     */
    public function resizeLFit($width = 0, $height = 0, $limit = null)
    {
        return $this->resize('lfit', $width, $height, $limit);
    }

    /**
     * @param int $width
     * @param int $height
     * @param null $limit
     * @return $this
     */
    public function resizeMFit($width = 0, $height = 0, $limit = null)
    {
        return $this->resize('mfit', $width, $height, $limit);
    }

    /**
     * @param int $width
     * @param int $height
     * @param null $limit
     * @return $this
     */
    public function resizeFill($width = 0, $height = 0, $limit = null)
    {
        return $this->resize('fill', $width, $height, $limit);
    }

    /**
     * @param int $width
     * @param int $height
     * @param null $limit
     * @param null $color
     * @return $this
     */
    public function resizePad($width = 0, $height = 0, $limit = null, $color = null)
    {
        return $this->resize('pad', $width, $height, $limit, $color);
    }

    /**
     * @param int $width
     * @param int $height
     * @param null|int $limit 0 or 1
     * @return $this
     */
    public function resizeFixed($width = 0, $height = 0, $limit = null)
    {
        return $this->resize('fixed', $width, $height, $limit);
    }

    /**
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function fitMinSize($width = 0, $height = 0)
    {
        return $this->resizeMFit($width, $height);
    }

    /**
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function fitMaxSize($width = 0, $height = 0)
    {
        return $this->resizeLFit($width, $height);
    }

    /**
     * @param int $width
     * @return $this
     */
    public function fitByWidth($width)
    {
        return $this->resizeLFit($width);
    }

    /**
     * @param int $height
     * @return $this
     */
    public function fitByHeight($height)
    {
        return $this->resizeLFit(0, $height);
    }

    /**
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function fixedSize($width = 0, $height = 0)
    {
        return $this->resizeFixed($width, $height);
    }

    /**
     * @param int $width
     * @return $this
     */
    public function fixedWidth($width)
    {
        return $this->resizeFixed($width);
    }

    /**
     * @param int $height
     * @return $this
     */
    public function fixedHeight($height)
    {
        return $this->resizeFixed(0, $height);
    }


    ################## crop ######################

    /**
     * @param int $radius
     * @return $this
     */
    public function circle($radius)
    {
        return $this->setParam('circle', 'r_' . $radius);
    }

    /**
     * @param int $radius
     * @return $this
     */
    public function roundedCorners($radius)
    {
        static::rangeValidate('Radius', $radius, 1, 4096);
        return $this->setParam('rounded-corners', 'r_' . $radius);
    }

    /**
     * @param int $width
     * @param int $height
     * @param int $x
     * @param int $y
     * @param null|string $g
     * @return $this
     */
    public function crop($width = 0, $height = 0, $x = 0, $y = 0, $g = null)
    {
        $values = [];
        if ($width > 0) {
            $values[] = 'w_' . $width;
        }
        if ($height > 0) {
            $values[] = 'h_' . $height;
        }
        if ($x > 0) {
            $values[] = 'x_' . $x;
        }
        if ($y > 0) {
            $values[] = 'y_' . $y;
        }
        if (!empty($g) && in_array($g, static::positions())) {
            $values[] = 'g_' . $g;
        }

        if (!empty($values)) {
            $this->setParam('crop', join(',', $values));
        }

        return $this;
    }

    /**
     * @param int $width
     * @param int $i
     * @return $this
     */
    public function indexCropX($width, $i = 0)
    {
        if ($width <= 0) {
            throw new \InvalidArgumentException('Width must be greater than 0');
        }

        return $this->setParam('indexcrop', "x_{$width},i_{$i}");
    }

    /**
     * @param int $height
     * @param int $i
     * @return $this
     */
    public function indexCropY($height, $i = 0)
    {
        if ($height <= 0) {
            throw new \InvalidArgumentException('Height must be greater than 0');
        }

        return $this->setParam('indexcrop', "y_{$height},i_{$i}");
    }


    ##################### rotate #####################

    /**
     * @param int $value
     * @return $this
     */
    public function autoOrient($value)
    {
        static::rangeValidate('Value', $value, 0, 1);
        return $this->setParam('auto-orient', $value);
    }

    /**
     * @param int $angle
     * @return $this
     */
    public function rotate($angle)
    {
        static::rangeValidate('Angle', $angle, 0, 360);
        return $this->setParam('rotate', $angle);
    }


    ################## effects #####################

    /**
     * @param int $radius
     * @param int $sigma
     * @return $this
     */
    public function blur($radius, $sigma)
    {
        static::rangeValidate('Radius', $radius, 1, 50);
        static::rangeValidate('Sigma', $sigma, 1, 50);
        return $this->setParam('blur', "r_{$radius},s_{$sigma}");
    }

    /**
     * @param int $value
     * @return $this
     */
    public function bright($value)
    {
        static::rangeValidate('Value', $value, 1, 50);
        return $this->setParam('bright', $value);
    }

    /**
     * @param int $value
     * @return $this
     */
    public function contrast($value)
    {
        static::rangeValidate('Value', $value, -100, 100);
        return $this->setParam('contrast', $value);
    }

    /**
     * @param int $value
     * @return $this
     */
    public function sharpen($value = 100)
    {
        static::rangeValidate('Value', $value, 50, 399);
        return $this->setParam('sharpen', $value);
    }


    ################# formats ##########################

    /**
     * @param string $format
     * @param bool $interlace
     * @return $this
     */
    public function format($format, $interlace = false)
    {
        if (!in_array($format, ImageFormat::collections())) {
            $formats = join('|', ImageFormat::collections());
            throw new \InvalidArgumentException("Image output format: {$format} is not valid, values: {$formats}.");
        }

        $this->setParam('format', $format);
        if ($interlace && $format === ImageFormat::JPG) {
            $this->interlace(true);
        }

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function interlace($value = true)
    {
        return $this->setParam('interlace', $value ? 1 : 0);
    }

    /**
     * @param int $quality Value: 1 - 100
     * @param bool $absolute
     * @return $this
     */
    public function quality($quality, $absolute = true)
    {
        static::rangeValidate('Quality', $quality, 1, 100);
        $param = $absolute ? 'Q' : 'q';
        return $this->setParam('quality', $param . '_' . $quality);
    }


    /**
     * @param array $params
     * @return string
     */
    private static function buildParams($params)
    {
        $actions = [];
        foreach ((array)$params as $key => $value) {
            if (is_int($key)) {
                $actions[] = $value;
            } else {
                $actions[] = "{$key},{$value}";
            }
        }

        return empty($actions) ? '' : join('/', $actions);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUrl();
    }
}