<?php

namespace ride\library\geocode;

use ride\library\geocode\coordinate\GenericGeocodeCoordinate;
use ride\library\geocode\service\GeocodeServiceContainer;

/**
 * Geocode facade
 */
class Geocoder extends GeocodeServiceContainer {

    /**
     * Performs geocoding on the provided query
     * @param string $service Name of the service
     * @param string $address Location or IP address
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     */
    public function geocode($service, $address) {
        $service = $this->getService($service);

        return $service->geocode($address);
    }

    /**
     * Performs reverse geocoding on the provided coordinate
     * @param string $service Name of the service
     * @param float $latitude Latitude
     * @param float $longitude Longitude
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     */
    public function reverseGeocode($service, $latitude, $longitude) {
        $service = $this->getService($service);

        return $service->reverseGeocode(new GenericGeocodeCoordinate($latitude, $longitude));
    }

}
