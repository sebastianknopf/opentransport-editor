<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Agency;
use App\Model\Entity\Service;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Agencies policy
 */
class AgencyPolicy
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
    public function before(IdentityInterface $user, Agency $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can add Agencies
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Agencies $agencies
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Agency $agency)
    {
        if($user->superuser) {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Agencies', 'create');
    }

    /**
     * Check if $user can edit Agencies
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Agencies $agencies
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Agency $agency)
    {
        if($user->superuser) {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Agencies', 'update') && $user->client_id == $agency->client_id;
    }

    /**
     * Check if $user can delete Agencies
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Agencies $agencies
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Agency $agency)
    {
        if($user->superuser) {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Agencies', 'delete') && $user->client_id == $agency->client_id;
    }

    /**
     * Check if $user can view Agencies
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Agencies $agencies
     * @return bool
     */
    public function canView(IdentityInterface $user, Agency $agency)
    {
        if($user->superuser) {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Agencies', 'read') && $user->client_id == $agency->client_id;
    }
}
