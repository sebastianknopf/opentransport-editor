<?php

namespace App\Policy;

/**
 * Agencies policy
 */
class AgenciesTablePolicy
{
    /**
     * Applies a custom user scope while querying the agencies table.
     *
     * @param type $user
     * @param type $query
     * @return type
     */
    public function scopeIndex($user, $query) {
        if(!$user->superuser) {
            $query = $query->where(['Agencies.client_id' => $user->client_id]);
        }

        return $query;
    }
}