<?php

/**
 * Error Class
 *
 * @package LightFramework\Core
 */
class Error
{
    /**
     * Shows an error
     *
     * @param  string $message Error String to show
     * @return void
     */
    public static function render($message = "")
    {
        //Get the current Config
        $config = Registry::getConfig();

        //CLI error
        if (php_sapi_name() == 'cli') {
            die($message."\n");
        }

        //Debug Enabled?
        if ($config->get("debug")) {
            Template::render("error", array("content" => $message));
        } else {
            Url::redirect();
        }
    }
}
