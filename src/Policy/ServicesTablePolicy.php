<?php


namespace App\Policy;

/**
 * Posts policy
 */
class ServicesTablePolicy
{
    /**
     * Applies a custom user scope while querying the posts table.
     *
     * @param type $user
     * @param type $query
     * @return type
     */
    public function scopeIndex($user, $query) {
        if(!$user->superuser) {
            $query = $query->where(['Services.client_id' => $user->client_id]);
        }

        return $query;
    }
}