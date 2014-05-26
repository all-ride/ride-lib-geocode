<?php

namespace ride\library\geocode\service;

use ride\library\http\client\Client;

/**
 * Abstract implementation of a geocode service
 */
abstract class AbstractGeocodeService implements GeocodeService {

    /**
     * Instance of the HTTP client
     * @var \ride\library\http\client\Client
     */
    protected $httpClient;

    /**
     * Constructs a new abstract service
     * @param \ride\library\http\client\Client $httpClient Instance of a HTTP
     * client
     * @return null
     */
    public function __construct(Client $httpClient) {
        $this->httpClient = $httpClient;
    }

    /**
     * Gets the name of this service
     * @return string Name of this service
     */
    public function getName() {
        return static::NAME;
    }

    /**
     * Parses the results from the service into GeocodeResult objects
     * @param array $serviceResults Parsed results from the service
     * @return array Array of GeocodeResult objects
     * @see \ride\library\geocode\GeocodeResult
     */
    protected function parseServiceResults(array $serviceResults) {
        $geocodeResults = array();

        foreach ($serviceResults as $serviceResult) {
            $geocodeResults[] = $this->parseServiceResult($serviceResult);
        }

        return $geocodeResults;
    }

    /**
     * Parses a single result from the service into a GeocodeResult object
     * @param mixed $serviceResult Result data container
     * @return \ride\library\geocode\GeocodeResult
     */
    abstract protected function parseServiceResult($serviceResult);

}
