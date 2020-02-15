<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Agency;
use App\Model\Entity\Route;
use App\Model\Entity\Routes;
use App\Model\Entity\Service;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Routes policy
 */
class RoutePolicy
{
    public function __construct() {
        $registry = new ComponentRegistry();
        $this->Acl = new AclComponent($registry);
    }

    /**
     * Global checks - Only logged in users
     *
     * @param IdentityInterface $user The user.
     * @param Service $object
     * @param $action
     * @return bool
     */
    public function before(IdentityInterface $user, Route $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can create Routes
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Routes $routes
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Route $route)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Routes', 'create');
    }

    /**
     * Check if $user can update Routes
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Routes $routes
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Route $route)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Routes', 'update') && $user->client_id == $route->client_id;
    }

    /**
     * Check if $user can delete Routes
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Routes $routes
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Route $route)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Routes', 'delete') && $user->client_id == $route->client_id;
    }

    /**
     * Check if $user can view Routes
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Routes $routes
     * @return bool
     */
    public function canView(IdentityInterface $user, Route $route)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Routes', 'read') && $user->client_id == $route->client_id;
    }
}
