<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\User;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * User policy
 */
class UserPolicy
{
    public function __construct() {
        $registry = new ComponentRegistry();
        $this->Acl = new AclComponent($registry);
    }

    /**
     * Check if $user can add User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canAdd(IdentityInterface $user, User $resource)
    {
        $generic = $user != null;
        $acl = $this->Acl->check($user->bindNode(null), 'Users', 'create');

        return $generic && $acl;
    }

    /**
     * Check if $user can edit User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canEdit(IdentityInterface $user, User $resource)
    {
        $generic = $user != null;
        $acl = $this->Acl->check($user->bindNode(null), 'Users', 'update');

        return $generic && $acl;
    }

    /**
     * Check if $user can delete User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canDelete(IdentityInterface $user, User $resource)
    {
        $generic = $user != null;
        $acl = $this->Acl->check($user->bindNode(null), 'Users', 'delete');

        return $generic && $acl;
    }

    /**
     * Check if $user can view User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canView(IdentityInterface $user, User $resource)
    {
        $generic = $user != null;
        $acl = $this->Acl->check($user->bindNode(null), 'Users', 'read');

        return $generic && $acl;
    }

    /**
     * Check if $user can change a password
     *
     * @param IdentityInterface $user The user.
     * @param User $resource
     * @return bool
     */
    public function canChangePassword(IdentityInterface $user, User $resource) {
        $generic = $user->id == $resource->id;

        return $generic;
    }
}
