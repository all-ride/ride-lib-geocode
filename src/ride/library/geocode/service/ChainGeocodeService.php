<?php

namespace ride\library\geocode\service;

use ride\library\geocode\coordinate\GeocodeCoordinate;
use ride\library\geocode\exception\ChainGeocodeException;
use ride\library\geocode\exception\GeocodeException;

/**
 * Geocode service implementation to chain different services
 */
class ChainGeocodeService extends GeocodeServiceContainer implements GeocodeService {

    /**
     * Name of this chain
     * @var string
     */
    protected $name;

    /**
     * Constructs a new geocode service chain
     * @param string $name Machine name of the chain
     * @param array $services Services for the chain
     * @return null
     */
    public function __construct($name, array $services = null) {
        parent::__construct($services);

        if (!is_string($name) || !$name) {
            throw new GeocodeException('Could not construct geocode service chain: empty or invalid name provided');
        }

        $this->name = $name;
    }

    /**
     * Gets the machine name of this service
     * @return string Machine name of this service
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Performs geocoding on the provided query
     * @param string $address Location or IP address
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     * @throws \ride\library\geocode\exception\GeocodeException when the
     * geocoding is not succesfull
     */
    public function geocode($address) {
        $exceptions = array();

        foreach ($this->services as $service) {
            try {
                return $service->geocode($address);
            } catch (GeocodeException $exception) {
                $exceptions[$service->getName()] = $exception;
            }
        }

        $exception = new ChainGeocodeException('Could not geocode ' . $address);
        $exception->setServiceExceptions($exceptions);

        throw $exception;
    }

    /**
     * Performs reverse geocoding on the provided coordinate
     * @param \ride\library\geocode\coordinate\GeocodeCoordinate $coordinate
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     * @throws \ride\library\geocode\exception\GeocodeException when the
     * geocoding is not succesfull
     */
    public function reverseGeocode(GeocodeCoordinate $coordinate) {
        $exceptions = array();

        foreach ($this->services as $service) {
            try {
                return $service->reverseGeocode($coordinate);
            } catch (GeocodeException $exception) {
                $exceptions[$service->getName()] = $exception;
            }
        }

        $exception = new ChainGeocodeException('Could not reverse geocode ' . $coordinate);
        $exception->setServiceExceptions($exceptions);

        throw $exception;
    }

}
