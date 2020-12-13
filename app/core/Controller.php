<?php
use Fox\Request;
use Fox\Cookies;
use Fox\CSRF;
use Fox\Session;

class Controller {

    protected $view;
    protected $viewVars = array();
    protected $actionName;

    private $disableView;
    private $json_output;
    private $purifier;

    protected $router;
	protected $request;
    protected $cookies;
    protected $csrf;

    public $user;
    public $userRole;

    /**
     * Executes before the controller is executed. Can be used
     * for permissions
     */
    public function beforeExecute() {
        $this->request  = Request::getInstance();
        $this->cookies  = Cookies::getInstance();

        $controller = $this->router->getController();
        $action     = $this->router->getMethod();
        $userRole   = "Guest";

        // do some sort of login here

        $access = Security::canAccess($controller, $action, $userRole);

        if (!$access) {
            $this->setView("errors/show401");
            return false;
        }

        $this->set("controller", $controller);
        $this->set("action", $action);
        return true;
    }

    /**
     * Displays the necessary template using Twig
     */
	public function show() {
	    if ($this->disableView) {
	        return;
        }

	    $loader = new Template('app/views');
        $loader->setCacheEnabled(false);

        if (!file_exists($loader->path.'/'.$this->view.".twig")) {
            $this->view = "errors/missing";
        }

	    try {
            $template = $loader->load($this->view);
            echo $template->render($this->viewVars);
        } catch (Exception $e) {
            
        }
	}

    /**
     * Gets the name of the action
     * @return mixed
     */
	public function getActionName() {
		return $this->actionName;
	}

    /**
     * Sets the action to be used.
     * @param $name
     */
	public function setActionName($name) {
		$this->actionName = $name;
	}

    /**
     * Sets a specific variable for the view with a value
     * @param $variableName
     * @param $value
     */
	public function set($variableName, $value) {
		$this->viewVars[$variableName] = $value;
	}

    /**
     * Sets variables to be used in the view
     * @param $params
     */
	public function setVars($params) {
		$this->viewVars = $params;
	}

    /**
     * Sets which view to use.
     * @param $view
     */
	public function setView($view) {
		$this->view = $view;
    }
    
    /**
     * @return string the view path
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @return PageRouter
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * @param $router PageRouter
     */
	public function setRouter(PageRouter $router) {
	    $this->router = $router;
    }

    /**
     * Disables the view from rendering
     * @var bool $is_json
     */
    public function disableView($is_json = false) {
        $this->disableView = true;
        $this->json_output = $is_json;
    }

    /**
     * @return bool true if output should be json format
     */
    public function isJson() {
        return $this->json_output;
    }

    /**
     * @return Cookies
     */
    public function getCookies() {
        return $this->cookies;
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return EasyCSRF\EasyCSRF
     */
    public function getCsrf() {
        return $this->csrf;
    }

    /**
     * gets the contents of a template file
     * @return string 
     */
    public function getViewContents($view, $vars = []) {
        $loader = new Template('app/views');
        $loader->setCacheEnabled(false);

        try {
            $template = $loader->load($view);
            return $template->render($vars);
        } catch (Exception $e) {
            return null;
        }
    }

}
