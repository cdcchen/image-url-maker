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

    public function fitMaxSize($width = 0, $height = 0);

    public function fitMinSize($width = 0, $height = 0);

    public function fitWidth($width);

    public function fitHeight($height);

    public function fixedWidth($width);

    public function fixedHeight($height);
}