<?php
namespace App\Policy;

use App\Model\Entity\Messages;
use Authorization\IdentityInterface;

/**
 * Messages policy
 */
class MessagesPolicy
{
    /**
     * Check if $user can create Messages
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Messages $messages
     * @return bool
     */
    public function canCreate(IdentityInterface $user, Messages $messages)
    {
    }

    /**
     * Check if $user can update Messages
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Messages $messages
     * @return bool
     */
    public function canUpdate(IdentityInterface $user, Messages $messages)
    {
    }

    /**
     * Check if $user can delete Messages
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Messages $messages
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Messages $messages)
    {
    }

    /**
     * Check if $user can view Messages
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Messages $messages
     * @return bool
     */
    public function canView(IdentityInterface $user, Messages $messages)
    {
    }
}
