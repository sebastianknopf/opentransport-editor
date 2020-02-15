<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Route;
use App\Model\Entity\Service;
use App\Model\Entity\Trip;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Trips policy
 */
class TripPolicy
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
     * Check if $user can create Trips
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Trips $trips
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Trip $trip)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Trips', 'create');
    }

    /**
     * Check if $user can update Trips
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Trips $trips
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Trip $trip)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Trips', 'update') && $user->client_id == $trip->client_id;
    }

    /**
     * Check if $user can delete Trips
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Trips $trips
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Trip $trip)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Trips', 'delete') && $user->client_id == $trip->client_id;
    }

    /**
     * Check if $user can view Trips
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Trips $trips
     * @return bool
     */
    public function canView(IdentityInterface $user, Trip $trip)
    {
        if($user->superuser == '1') {
            return true;
        }

        return $this->Acl->check($user->bindNode(null), 'Trips', 'read') && $user->client_id == $trip->client_id;
    }
}
