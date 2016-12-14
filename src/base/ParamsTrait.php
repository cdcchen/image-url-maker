<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/7/2
 * Time: 14:44
 */

namespace ImageUrlMaker\base;


/**
 * Class ParamsTrait
 * @package ImageUrlMaker\base
 */
trait ParamsTrait
{
    /**
     * @var array
     */
    private $_params = [];

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParam($name, $value = null)
    {
        if ($value === null) {
            $this->_params[] = $name;
        } else {
            $this->_params[$name] = $value;
        }
        return $this;
    }

    /**
     * @param string $name
     * @return null|mixed
     */
    public function getParam($name)
    {
        return isset($this->_params[$name]) ? $this->_params[$name] : null;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        foreach ($params as $name => $value) {
            $this->setParam($name, $value);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
}