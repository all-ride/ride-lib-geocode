<?php

namespace ride\library\geocode\coordinate;

/**
 * Coordinate for geocoding
 */
interface GeocodeCoordinate {

    /**
     * Gets the latitude value
     * @return float
     */
    public function getLatitude();

    /**
     * Gets the longitude value
     * @return float
     */
    public function getLongitude();

}
