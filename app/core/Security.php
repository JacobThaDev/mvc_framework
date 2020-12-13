<?php
class Security {

    private static $roles;

    /**
     * @return AclList
     */
    public static function getAclList() {
        $acl = new AclList();

        self::$roles = [
            'owner'     => new Role('Owner'),
            'developer' => new Role('Developer'),
            'admin'     => new Role('Administrator'),
            'moderator' => new Role('Moderator'),
            'member'    => new Role('Member'),
            'guest'     => new Role('Guest'),
        ];

        foreach (self::$roles as $role) {
            $acl->addRole($role);
        }

        $public = [
            'index'     => ['index'],
            'errors'    => ['show401', 'show404', 'show500']
        ];

        $private = [
            
        ];

        $moderator = [
            
        ];

        foreach ($public as $controller => $actions) {
            $resource = new Resource($controller, $actions);
            $resource->allow([
                self::$roles['owner'],
                self::$roles['developer'],
                self::$roles['admin'],
                self::$roles['moderator'],
                self::$roles['member'],
                self::$roles['guest'],
            ]);
            $acl->addResource($controller, $resource);
        }

        foreach ($private as $controller => $actions) {
            $resource = new Resource($controller, $actions);
            $resource->allow([
                self::$roles['owner'],
                self::$roles['developer'],
                self::$roles['admin'],
                self::$roles['moderator'],
                self::$roles['member'],
            ]);
            $acl->addResource($controller, $resource);
        }

        foreach ($moderator as $controller => $actions) {
            $resource = new Resource($controller, $actions);
            $resource->allow([
                self::$roles['owner'],
                self::$roles['developer'],
                self::$roles['admin'],
                self::$roles['moderator']
            ]);
            $acl->addResource($controller, $resource);
        }

        return $acl;
    }

    public static function canAccess($controller, $action, $rank) {
        $accessList = self::getAclList();

        $userRole  = $accessList->getRole($rank);
        $resources = $accessList->getResources($controller);

        if (!$userRole) {
            return false;
        }

        foreach ($resources as $resource) {
            if ($resource->isAllowed($userRole, $action)) {
                return true;
            }
        }
        return false;
    }

    public static function isValidRole($name) {
        foreach (self::$roles as $role) {
            if (strtolower($role->getName()) == strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Role
     */
    public static function getRole($name) {
        return self::$roles[strtolower($name)];
    }

    public static function getRoles() {
        return self::$roles;
    }


}