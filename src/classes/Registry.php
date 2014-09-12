<?php

/**
 * Registry Class
 *
 * @package LightFramework\Core
 */
class Registry
{
    /**
     * Current Url object
     * @var object
     */
    private static $url = null;

    /**
     * Current Database object
     * @var object
     */
    private static $db = null;

    /**
     * Current Config object
     * @var object
     */
    private static $config = null;

    /**
     * Current User object
     * @var object
     */
    private static $user = null;

    /**
     * Current Template object
     * @var object
     */
    private static $template = null;

    /**
     * Current Messages array
     * @var array
     */
    private static $messages = array();

    /**
     * Current Language object
     * @var object
     */
    private static $language = null;

    /**
     * Current Debug values
     * @var array
     */
    private static $debug = array();

    /**
     * Returns the current Mailer
     *
     * @return object Mailer
     */
    public function getMailer()
    {
        $config = Registry::getConfig();
        $mailer = new PHPMailer();

        //Server setup?
        if ($config->get("mailHost") && $config->get("mailPort") && $config->get("mailUsername") && $config->get("mailPassword")) {
            $mailer->isSMTP();
            $mailer->Host = $config->get("mailHost");
            $mailer->Port = $config->get("mailPort");
            $mailer->SMTPAuth = true;
            $mailer->SMTPSecure = $config->get("mailSecure");
            $mailer->Username = $config->get("mailUsername");
            $mailer->Password = $config->get("mailPassword");
        //Sendmail
        } else {
            $mailer->isSendmail();
        }

        //From adress
        if ($config->get("mailFromAdress")) {
            $mailer->setFrom($config->get("mailFromAdress"), $config->get("mailFromName"));
        }

        return $mailer;
    }

    /**
     * Preserve the current debug (for ajax/redirections)
     *
     * @return void
     */
    public static function preserveDebug()
    {
        $_SESSION['debug'] = self::$debug;
    }

    /**
     * Save a Debug message
     *
     * @param mixed $message String/array/object to store
     *
     * @return bool
     */
    public function addDebugMessage($message)
    {
        $current = self::getDebug("messages");
        //Backtrace
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_contents();
        ob_end_clean();
        $current[] = array(
            "message" => $message,
            "trace" => $trace,
        );

        return self::setDebug("messages", $current);
    }

    /**
     * Get the current Debug Log
     *
     * @param string $key Log Key (variable)
     *
     * @return multiple Debug Log or Value of passed Log Key
     */
    public static function getDebug($key = "")
    {
        if ($key) {
            return self::$debug[$key];
        } else {
            return self::$debug;
        }
    }

    /**
     * Set a Debug Log object
     *
     * @param string $key  Key
     * @param mixed  $data Value
     *
     * @return void
     */
    public static function setDebug($key, $data)
    {
        self::$debug[$key] = $data;
    }

    /**
     * Get the current Url object
     *
     * @return object Url
     */
    public static function getUrl()
    {
        if (self::$url == null) {
            self::$url = new Url();
        }

        return self::$url;
    }

    /**
     * Set an Url object
     *
     * @return object Url
     */
    public static function setUrl($urlObject)
    {
        self::$url = $urlObject;
    }

    /**
     * Get the current Language object
     *
     * @param string $lang Desired language
     *
     * @return object Url
     */
    public static function getLanguage($lang = "")
    {
        if (self::$language == null) {
            self::$language = new Language($lang);
        }

        return self::$language;
    }

    /**
     * Get the current Data Base object
     *
     * @return object Data Base
     */
    public static function getDb()
    {
        $config = self::getConfig();
        if (self::$db == null) {
            self::$db = new Database($config->get("dbHost"), $config->get("dbUser"), $config->get("dbPass"), $config->get("dbName"));
        }

        return self::$db;
    }

    /**
     * Get the current User object
     *
     * @return object User
     */
    public static function getUser()
    {
        if (self::$user == null || !self::$user->id) {
            $config = Registry::getConfig();
            //Cookie
            if (isset($_COOKIE[$config->get("cookie")])) {
                self::$user = @current(User::getBy("token", $_COOKIE[$config->get("cookie")]));
            }
        }

        return self::$user;
    }

    /**
     * Get the current Config object
     *
     * @return object Config
     */
    public static function getConfig()
    {
        if (self::$config == null) {
            global $_config;
            self::$config = new Config($_config);
        }

        return self::$config;
    }

    /**
     * Get the current Template object
     *
     * @return object Template
     */
    public static function getTemplate()
    {
        if (self::$template == null) {
            self::$template = new Template();
        }

        return self::$template;
    }

    /**
     * Add a message on the current session
     *
     * @param string  $message Message itself
     * @param integer $type    Type of message
     * @param string  $field   Related Form field
     * @param string  $url     Url to redirect
     *
     * @return bool
     */
    public static function addMessage($message = "", $type = 1, $field = "", $url = "")
    {
        if (php_sapi_name() != 'cli') {
            session_start();
        }
        $msg = new Message($message, $type, $field, $url);
        $_SESSION['messages'][] = $msg;
        self::$messages[] = $_SESSION['messages'];

        return true;
    }

    /**
     * Get the current session messages
     *
     * @param bool $keep Don't delete the messages
     *
     * @return array List of Message objects
     */
    public static function getMessages($keep = false)
    {
        if (php_sapi_name() != 'cli') {
            session_start();
        }
        $messages = $_SESSION['messages'];
        self::$messages = $messages;

        //Keep messages?
        if (!$keep) {
            self::$messages = array();
            $_SESSION['messages'] = array();
        }

        return $messages;
    }
}
