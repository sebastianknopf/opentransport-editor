<?php


namespace App\Model\Entity\Route;


class Type
{
    const TRAM = 0;

    const SUBWAY = 1;

    const RAIL = 2;

    const BUS = 3;

    const FERRY = 4;

    const CABLE_CAR = 5;

    const GONDOLA = 6;

    const FUNICULAR = 7;



    /**
     * Returns an array with all currently available route types.
     *
     * @return array All currently available route types.
     */
    public static function getRouteTypes()
    {
        return [
            self::TRAM => __('Tram'),
            self::SUBWAY => __('Subway'),
            self::RAIL => __('Railway'),
            self::BUS => __('Bus'),
            self::FERRY => __('Ferry'),
            self::CABLE_CAR => __('Cable Car'),
            self::GONDOLA => __('Gondola'),
            self::FUNICULAR => __('Funicular')
        ];
    }
}