<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\ApiLog;
use App\Model\Entity\Route;
use App\Model\Entity\Service;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Log policy
 */
class ApiLogPolicy
{
    public function __construct() {
        $registry = new ComponentRegistry();
        $this->Acl = new AclComponent($registry);
    }

    /**
    * Global checks - Only logged in users
    *
    * @param IdentityInterface $user The user.
    * @param ApiLog $object
    * @param $action
    * @return bool
    */
    public function before(IdentityInterface $user, ApiLog $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can create Log
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Log $restLog
     * @return bool
     */
    public function canAdd(IdentityInterface $user, ApiLog $restLog)
    {
        return false; // rest api logs can not be added manually
    }

    /**
     * Check if $user can update Log
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Log $restLog
     * @return bool
     */
    public function canEdit(IdentityInterface $user, ApiLog $restLog)
    {
        return false; // rest api logs can not be edited
    }

    /**
     * Check if $user can delete Log
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Log $restLog
     * @return bool
     */
    public function canDelete(IdentityInterface $user, ApiLog $restLog)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'RESTAPI', 'delete');
    }

    /**
     * Check if $user can view Log
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Log $restLog
     * @return bool
     */
    public function canView(IdentityInterface $user, ApiLog $restLog)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'RESTAPI', 'read');
    }
}
