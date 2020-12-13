<?php

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TemplateWrapper;
use Twig\TwigFilter;

class Template extends FilesystemLoader {

    public $path;

    public function __construct($paths = [], $rootPath = null) {
        $this->path = $paths;
        parent::__construct($paths, $rootPath);
    }

    private $cache_enabled = true;

    /**
     * @param $path
     * @return TemplateWrapper|null
     */
    public function load($path) {
        try {
            $twig = new \Twig\Environment($this, !$this->cache_enabled ? [] : ['cache' => 'app/cache']);
            
            $twig->addFunction(new \Twig\TwigFunction('url', function ($string, $internal = true) {
                return $internal ? web_root . $string : $string;
            }));

            $twig->addFunction(new \Twig\TwigFunction('css', function ($string) {
                return web_root.'public/css/' . $string . '';
            }));

            $twig->addFunction(new \Twig\TwigFunction('jscript', function ($string) {
                return web_root . 'public/js/' . $string . '';
            }));

            $twig->addFunction(new \Twig\TwigFunction('constant', function ($string) {
                return constant($string);
            }));

            $twig->addFunction(new \Twig\TwigFunction('date', function ($string) {
                return date($string);
            }));

            $twig->addFunction(new \Twig\TwigFunction('strtotime', function ($string) {
                return strtotime($string);
            }));

            $twig->addFunction(new \Twig\TwigFunction('debugArr', function ($string) {
                return json_encode($string, JSON_PRETTY_PRINT);
            }));

            $twig->addFunction(new \Twig\TwigFunction('in_array', function ($needle, $haystack) {
                return in_array($needle, $haystack);
            }));

            $twig->addFunction(new \Twig\TwigFunction('decode', function ($array) {
                return json_decode($array, true);
            }));

            $twig->addFilter(new TwigFilter('array_chunk', function($array, $limit) {
                return array_chunk($array, $limit);
            }));

            $twig->addFilter(new TwigFilter('strtotime', function($array) {
                return strtotime($array);
            }));

            $twig->addFilter(new TwigFilter('str_replace', function($str, $search, $replace) {
                return str_replace($search, $replace, $str);
            }));

            $twig->addFunction(new \Twig\TwigFunction('shortexp', function ($val) {
                return Functions::shortenExp($val);
            }));

            $twig->addFunction(new \Twig\TwigFunction('time', function () {
                return time();
            }));
            
            $twig->addFunction(new \Twig\TwigFunction('strrep', function ($string, $toRep, $repWith) {
                return str_replace($toRep, $repWith, $string);
            }));

            $twig->addFunction(new \Twig\TwigFunction('friendlyTitle', function ($title) {
                return Functions::friendlyTitle($title);
            }));

            $twig->addFunction(new \Twig\TwigFunction('elapsed', function ($int) {
                return Functions::elapsed($int);
            }));

            $twig->addFunction(new \Twig\TwigFunction('timeLeft', function ($int, $short = false) {
                return Functions::getTimeLeft($int, $short);
            }));
            
            return $twig->load($path . '.twig');
        } catch (/*LoaderError|RuntimeError|SyntaxError*/Exception $e) {
            return null;
        }
    }

    public function setCacheEnabled($val) {
        $this->cache_enabled = $val;
    }
}