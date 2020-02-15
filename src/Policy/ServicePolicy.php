<?php


namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Service;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Services policy
 */
class ServicePolicy
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
    public function before(IdentityInterface $user, Service $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can create Services
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Service $stops
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Service $service)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Services', 'create');
    }

    /**
     * Check if $user can update Services
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Service $service
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Service $service)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Services', 'update') && $user->client_id == $service->client_id;
    }

    /**
     * Check if $user can delete Services
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Service $service
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Service $service)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Services', 'delete') && $user->client_id == $service->client_id;
    }

    /**
     * Check if $user can view Services
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Service $service
     * @return bool
     */
    public function canView(IdentityInterface $user, Service $service)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Services', 'read') && $user->client_id == $service->client_id;
    }
}