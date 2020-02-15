<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Shape;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Shape policy
 */
class ShapePolicy
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
     * @return bool
     */
    public function before(IdentityInterface $user, Shape $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can create Shape
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Shape $shape
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Shape $shape)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Shapes', 'create');
    }

    /**
     * Check if $user can update Shape
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Shape $shape
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Shape $shape)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Shapes', 'update');
    }

    /**
     * Check if $user can delete Shape
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Shape $shape
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Shape $shape)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Shapes', 'delete');
    }

    /**
     * Check if $user can view Shape
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Shape $shape
     * @return bool
     */
    public function canView(IdentityInterface $user, Shape $shape)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Shapes', 'read');
    }
}
