<?php


namespace App\Policy;


class RoutesTablePolicy
{
    /**
     * Applies a custom user scope while querying the routes table.
     *
     * @param type $user
     * @param type $query
     * @return type
     */
    public function scopeIndex($user, $query) {
        if($user->superuser != '1') {
            $query = $query->where(['Routes.client_id' => $user->client_id]);
        }

        return $query;
    }
}