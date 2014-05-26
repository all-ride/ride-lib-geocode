<?php

namespace ride\library\geocode\service;

use ride\library\geocode\coordinate\GeocodeCoordinate;

/**
 * Interface for a geocode service
 */
interface GeocodeService {

    /**
     * Gets the machine name of this service
     * @return string Machine name of this service
     */
    public function getName();

    /**
     * Performs geocoding on the provided address query
     * @param string $address Location or IP address
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     * @throws \ride\library\geocode\exception\GeocodeException when the
     * geocoding is not succesfull
     */
    public function geocode($address);

    /**
     * Performs reverse geocoding on the provided coordinates
     * @param \ride\library\geocode\coordinate\GeocodeCoordinate $coordinate
     * Coordinate to reverse lookup
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     * @throws \ride\library\geocode\exception\GeocodeException when the
     * geocoding is not succesfull
     */
    public function reverseGeocode(GeocodeCoordinate $coordinate);

}
