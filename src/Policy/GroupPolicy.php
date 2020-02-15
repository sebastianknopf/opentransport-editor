<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Group;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Group policy
 */
class GroupPolicy
{
    public function __construct() {
        $registry = new ComponentRegistry();
        $this->Acl = new AclComponent($registry);
    }

    /**
     * Global check - Only logged in users
     *
     * @param IdentityInterface $user The user.
     * @param Group $object
     * @param $action
     * @return bool
     */
    public function before(IdentityInterface $user, Group $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can add Group
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Group $group
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Group $group)
    {
        return $this->Acl->check($user->bindNode(null), 'Groups', 'create');
    }

    /**
     * Check if $user can edit Group
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Group $group
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Group $group)
    {
        return $this->Acl->check($user->bindNode(null), 'Groups', 'update');
    }

    /**
     * Check if $user can delete Group
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Group $group
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Group $group)
    {
        return $this->Acl->check($user->bindNode(null), 'Groups', 'delete');
    }

    /**
     * Check if $user can view Group
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Group $group
     * @return bool
     */
    public function canView(IdentityInterface $user, Group $group)
    {
        return $this->Acl->check($user->bindNode(null), 'Groups', 'read');
    }
}
