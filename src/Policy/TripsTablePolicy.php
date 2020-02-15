<?php

namespace App\Policy;

/**
 * Trips policy
 */
class TripsTablePolicy
{
    /**
     * Applies a custom user scope while querying the trips table.
     *
     * @param type $user
     * @param type $query
     * @return type
     */
    public function scopeIndex($user, $query) {
        if(!$user->superuser) {
            $query = $query->where(['Trips.client_id' => $user->client_id]);
        }

        return $query;
    }
}