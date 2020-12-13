<?php
class Resource {

    private $name;
    private $actions;
    public $allowed;

    /**
     * Class constructor
     */
    public function __construct($name, $actions) {
        $this->name = $name;
        $this->actions = $actions;
    }

    /**
     * @param Role $role list of allowed roles
     */
    public function allow($role) {
        if (is_array($role)) {
            $this->allowed = $role;
            return;
        }
        
        $this->allowed[] = $role;
    }

    /**
     * @return array list of actions
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * @return string the name of resource
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return bool true if rank is allowed to this resource
     */
    public function isAllowed($role, $action) {
        return in_array($role, $this->allowed) && in_array($action, $this->actions);
    }

}