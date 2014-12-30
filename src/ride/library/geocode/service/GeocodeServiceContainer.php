<?php

namespace ride\library\geocode\service;

use ride\library\geocode\exception\GeocodeException;

/**
 * Container of geocode services
 */
class GeocodeServiceContainer {

    /**
     * Geocode services used by this geocoder
     * @var array
     */
    protected $services;

    /**
     * Constructs a new geocode service container
     * @param array $services Services for the chain
     * @return null
     */
    public function __construct(array $services = null) {
        $this->services = array();

        if ($services) {
            foreach ($services as $service) {
                $this->addService($service);
            }
        }
    }

    /**
     * Adds a geocode service
     * @param GeocodeService $service Service to use when geocoding
     * @param boolean $prepend Set to true to prepend this service
     * @return null
     */
    public function addService(GeocodeService $service, $prepend = false) {
        if ($prepend) {
            $this->services = array($service->getName() => $service) + $this->services;
        } else {
            $this->services[$service->getName()] = $service;
        }
    }

    /**
     * Removes a geocode service
     * @param string $name Name of the service
     * @return boolean True when the service is removed, false otherwise
     */
    public function removeService($name) {
        if (!isset($this->services[$name])) {
            return false;
        }

        unset($this->services[$name]);

        return true;
    }

    /**
     * Gets a geocode service
     * @param string $name Name of the service
     * @return GeocodeService
     * @throws \ride\library\geocode\exception\GeocodeException
     */
    public function getService($name) {
        if (!isset($this->services[$name])) {
            throw new GeocodeException('Could not get service: ' . $name . ' is not set in this service container');
        }

        return $this->services[$name];
    }

    /**
     * Gets the geocode services which are used by this geocoder
     * @return array
     */
    public function getServices() {
        return $this->services;
    }

}
