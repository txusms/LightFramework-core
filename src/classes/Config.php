<?php

/**
 * Config Class
 *
 * @package LightFramework\Core
 */
class Config
{
    /**
     * Array of stored values
     *
     * @var array
     */
    private $data;

    /**
     * Default constructor
     *
     * @param array $vars
     */
    public function __construct($vars=array())
    {
        if (is_array($vars)) {
            $this->data = $vars;
        }
    }

    /**
     * Set a value into self data array
     *
     * @param string $name
     * @param mixed  $value
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Get a value previously stored in self data array
     *
     * @param mixed $name
     */
    public function get($name)
    {
        return $this->data[$name];
    }
}
