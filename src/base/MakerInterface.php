<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 2016/12/13
 * Time: 19:59
 */

namespace ImageUrlMaker\base;


/**
 * Interface MakerInterface
 * @package ImageUrlMaker\base
 */
interface MakerInterface
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param int $width
     * @param int $height
     * @return static
     */
    public function fitMinSize($width = 0, $height = 0);

    /**
     * @param int $width
     * @param int $height
     * @return static
     */
    public function fitMaxSize($width = 0, $height = 0);

    /**
     * @param int $width
     * @return static
     */
    public function fitByWidth($width);

    /**
     * @param int $height
     * @return static
     */
    public function fitByHeight($height);

    /**
     * @param int $width
     * @return static
     */
    public function fixedWidth($width);

    /**
     * @param int $height
     * @return static
     */
    public function fixedHeight($height);
}