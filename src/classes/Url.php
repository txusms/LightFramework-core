<?php

/**
 * Url Class
 *
 * @package LightFramework\Core
 */
class Url {
    /**
     * Current Url
     * @var string
     */
    public $url;

    /**
     * Router (Optional)
     * @var string
     */
    public $router;

    /**
     * Current App name
     * @var string
     */
    public $app;

    /**
     * Current Action name
     * @var string
     */
    public $action;

    /**
     * Url extra GET vars
     * @var array
     */
    public $vars;

    /**
     * Contructor
     */
    public function __construct() {
        $this->build($_SERVER['REQUEST_URI']);
    }

    /**
     * Url builder
     *
     * @param string $uri Url URI
     * @return void
     */
    public function build($uri) {
        //Get the current config
        $config = Registry::getConfig();

        //Cleaning url
        $url = $this->clean($uri);

        //Set Url
        $this->url = $url;

        //Set Default App
        $this->app = $config->get("defaultApp");

        //Set Default Action
        $this->action = "index";

        //URL construction
        if ($url) {

            //Read Vars
            $vars = explode("/", $url);
            $this->app = $vars[0];
            if ($vars[1]) {
                $this->action = $vars[1];
            }

            //GET Vars
            if (count($vars) > 2) {
                for ($i = 2; $i < count($vars); $i++) {
                    $this->vars[$i - 2] = $vars[$i];
                }
            }
        }

        //POST is mandatory
        $this->checkPost();
    }

    /**
     * Check POST parameters override
     *
     * @return void
     */
    private function checkPost() {
        // Router
        if ($_POST['router']) {
            $this->router = $_POST['router'];
        }

        // App
        if ($_POST['app']) {
            $this->app = $_POST['app'];
        }

        // Action
        if ($_POST['action']) {
            $this->action = $_POST['action'];
        }
        if ($_GET['action']) {
            $this->action = $_GET['action'];
        }
    }

    /**
     * Cleans an URI
     *
     * @param string $uri
     * @return string
     */
    private function clean($uri) {
        //Get the current config
        $config = Registry::getConfig();

        //Extra slashes
        $dir = $config->get("dir");
        if ($dir == "/") {
            $dir = "";
        }

        //Read the Url
        $url = trim(str_replace("//", "/", str_replace($dir, "", $uri)), "/");

        //Exclude GET params
        if (strstr($url, "?")) {
            $url = substr($url, 0, strpos($url, "?"));
        } elseif (strstr($url, "&")) {
            $url = substr($url, 0, strpos($url, "&"));
        }

        return $url;
    }

    /**
     * Gets the full site Url
     *
     * @param string $path Extra Url
     * @return string Url
     */
    public static function site($path = "") {
        $config = Registry::getConfig();
        $url = trim($config->get('url'), "/");
        $path = trim($path, "/");

        return $url . "/" . $path;
    }

    /**
     * Gets the curren template Url
     *
     * @param string $path
     * @param string $templateName
     * @return string Url
     */
    public static function template($path = "", $templateName = null) {
        $config = Registry::getConfig();
        if (!$templateName) {
            $template = Registry::getTemplate();
            $templateName = $template->name;
        }
        $url = trim($config->get('url'), "/");
        $path = trim($path, "/");

        return $url . "/templates/" . $templateName . "/" . $path;
    }

    /**
     * Url redirection
     *
     * @param string $url Url to redirect
     * @param string $message Message text
     * @param string $type Message type
     * @return void
     */
    function redirect($url = "", $message = "", $type = "") {
        // Site as default
        if (!$url) {
            $url = Url::site();
        }

        // Add a message
        if ($message) {
            Registry::addMessage($message, $type);
        }

        header("Location: " . $url);
        die();
    }
}
