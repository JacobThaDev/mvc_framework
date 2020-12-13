<?php
namespace Fox;

/**
 * Class Request
 * @package Fox\Req
 */
class Request {

    private static $instance;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Request();
            return self::$instance;
        }
        return self::$instance;
    }

    /**
     * Returns true if $_GET is not empty.
     * @return bool
     */
    public function isQuery() {
        return !empty($_GET);
    }

    /**
     * Returns true if $_POST is not empty.
     * @return bool
     */
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] == "POST";
    }

    /**
     * Returns true if $_REQUEST is not empty. Will also return true if $_POST or $_GET is not empty
     * as this gets all variables within the super-globals
     * @return bool
     */
    public function isRequest() {
        return !empty($_REQUEST);
    }

    /**
     * Returns if a key is set in $_QUERY
     * @param $key
     * @return bool
     */
    public function hasQuery($key) {
        return isset($_GET[$key]);
    }

    /**
     * Returns a value from $_GET
     * @param $key
     * @return mixed
     */
    public function getQuery($key = null, $filter = null) {
        if ($key != null && !$this->hasQuery($key)) {
            return null;
        }

        $value = $key == null ? $_GET : $_GET[$key];

        if ($filter != null) {
            if ($filter == 'string') {
                $value = htmlspecialchars(filter_var($value, FILTER_SANITIZE_STRING,
                    FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            }
            if ($filter == "int") {
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT,
                    FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
            }
            if ($filter == "email") {
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
            }
            if ($filter == "url") {
                $value = filter_var($value, FILTER_SANITIZE_URL);
            }
        }

        return $value;
    }

    /**
     * Returns if a key is set in $_POST
     * @param $key
     * @return bool
     */
    public function hasPost($key) {
        return isset($_POST[$key]) && !empty($_POST[$key]);
    }

    /**
     * Returns a value from $_POST
     * @param $key
     * @param $filter
     * @return mixed
     */
    public function getPost($key = null, $filter = null) {
        if ($key != null && !$this->hasPost($key)) {
            return null;
        }

        $value = $key == null ? $_POST : $_POST[$key];

        if ($filter != null) {
            if ($filter == 'string') {
                $value = htmlspecialchars(filter_var($value, FILTER_SANITIZE_STRING,
                    FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            }
            if ($filter == "int") {
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT,
                    FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
            }
            if ($filter == "email") {
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
            }
            if ($filter == "url") {
                $value = filter_var($value, FILTER_SANITIZE_URL);
            }
        }

        return $value;
    }

    /**
     * Returns if a key is set in $_REQUEST
     * @param $key
     * @return bool
     */
    public function hasRequest($key) {
        return isset($_REQUEST[$key]) && !empty($_REQUEST[$key]);
    }

    /**
     * Returns a value from $_REQUEST
     * @param $key
     * @return mixed
     */
    public function getRequest($key = null) {
        if (!$this->hasRequest($key)) {
            return null;
        }
        return $key ? $_REQUEST[$key] : $_REQUEST;
    }

    /**
     * @return string IP Address
     */
    public function getAddress() {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } else if(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Redirects to give url within application
     * @param $url String
     * @param $internal bool
     */
    public function redirect($url, $internal = true) {
        header("Location: ".($internal ? web_root.$url : $url));
    }

    /**
     * Redirects to a URL with after a delay in seconds.
     * @param $url
     * @param $time
     * @param bool $internal
     */
    public function delayedRedirect($url, $time, $internal = false) {
        header("refresh:{$time}; url=".($internal ? web_root.$url : $url));
    }
    
}