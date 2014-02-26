<?php

namespace Centreon\Core;

class Acl
{
    const ADD = 1;
    const DELETE = 2;
    const UPDATE = 4;
    const VIEW = 8;
    const ADVANCED = 16;

    private $routes;
    private $isAdmin;

    /**
     * Constructor
     *
     */
    public function __construct($userId)
    {
/*        $userId = Di::getDefault()->get('user')->getId();
        $sql = "SELECT route, permission 
            FROM acl_routes ar, acl_groups g, acl_group_contacts_relations r
            WHERE ar.acl_group_id = g.acl_group_id
            AND g.acl_group_id = r.acl_group_id
            AND r.contact_contact_id = ?";
        $db = Di::getDefault()->get('db_centreon');
        $stmt = $db->prepare($sql);
        $stmt->execute(array($userId));
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);*/
    }

    /**
     * Checks whether or not a flag is set
     *
     * @param int $values
     * @param int $flag
     * @return bool
     */
    public static function isFlagSet($values, $flag)
    {
        return (($values & $flag) === $flag);
    }

    /**
     * Check whether user is allowed to access route
     *
     * @param string $route
     * @param int $requiredAccess
     * @return bool
     */
    public function routeAllowed($route, $requiredAccess)
    {
        return true;
        if (Di::getDefault()->get('user')->isAdmin()) {
            return true;
        }
        if (isset($this->routes[$route])) {
            return self::isFlagSet($this->routes[$route], $requiredAccess);
        }
        return false;
    }

    /**
     * Convert ACL flags
     *
     * @return int
     */
    public static function convertAclFlags($aclFlags)
    {
        $flag = 0;
        foreach ($aclFlags as $flag) {
            switch (strtolower($flag)) {
                case "add": 
                    $f = self::ADD;
                    break;
                case "delete":
                    $f = self::DELETE;
                    break;
                case "update":
                    $f = self::UPDATE;
                    break;
                case "view":
                    $f = self::VIEW;
                    break;
            }
            $flag = $flag | $f;
        }
        return $flag;
    }
}