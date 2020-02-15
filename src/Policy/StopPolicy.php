<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Stop;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Stops policy
 */
class StopPolicy
{
    public function __construct() {
        $registry = new ComponentRegistry();
        $this->Acl = new AclComponent($registry);
    }

    /**
     * Global check - Only logged in users
     *
     * @param IdentityInterface $user The user.
     * @param Shape $object
     * @param $action
     */
    public function before(IdentityInterface $user, Shape $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can create Stops
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Stops $stops
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Stop $stops)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Stops', 'create');
    }

    /**
     * Check if $user can update Stops
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Stops $stops
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Stop $stops)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Stops', 'update');
    }

    /**
     * Check if $user can delete Stops
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Stops $stops
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Stop $stops)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Stops', 'delete');
    }

    /**
     * Check if $user can view Stops
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Stops $stops
     * @return bool
     */
    public function canView(IdentityInterface $user, Stop $stops)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Stops', 'read');
    }
}
