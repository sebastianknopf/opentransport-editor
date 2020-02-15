<?php
namespace App\Policy;

use Acl\Controller\Component\AclComponent;
use Acl\Controller\Component\SessionComponent;
use App\Model\Entity\Client;
use Authentication\Identifier\IdentifierInterface;
use Authorization\IdentityInterface;
use Cake\Controller\ComponentRegistry;

/**
 * Client policy
 */
class ClientPolicy
{
    public function __construct() {
        $registry = new ComponentRegistry();
        $this->Acl = new AclComponent($registry);
    }

    /**
     * Global check - Only logged in users
     *
     * @param IdentityInterface $user The user.
     * @param Client $object
     * @param $action
     * @return bool
     */
    public function before(IdentityInterface $user, Client $object, $action) {
        return $user != null;
    }

    /**
     * Check if $user can add Client
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Client $client
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Client $client)
    {
        return $this->Acl->check($user->bindNode(null), 'Clients', 'create');
    }

    /**
     * Check if $user can edit Client
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Client $client
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Client $client)
    {
        return $this->Acl->check($user->bindNode(null), 'Clients', 'update');
    }

    /**
     * Check if $user can delete Client
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Client $client
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Client $client)
    {
        return $this->Acl->check($user->bindNode(null), 'Clients', 'delete');
    }

    /**
     * Check if $user can view Client
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Client $client
     * @return bool
     */
    public function canView(IdentityInterface $user, Client $client)
    {
        return $this->Acl->check($user->bindNode(null), 'Clients', 'read');
    }

    /**
     * Check if a user can transfer the clientship of objects.
     *
     * @param IdentityInterface $user The user.
     * @param Client $client
     * @return bool
     */
    public function canTransfer(IdentityInterface $user, Client $client) {
        return $user->superuser == '1';
    }
}
