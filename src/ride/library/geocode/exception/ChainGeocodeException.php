<?php

namespace ride\library\geocode\exception;

/**
 * Exception thrown by the chain geocode service
 */
class ChainGeocodeException extends GeocodeException {

    /**
     * Exceptions of the chained services
     * @var array
     */
    protected $serviceExceptions;

    /**
     * Sets the exceptions of the chained services
     * @param array $serviceExceptions Array with the name of the service as key
     * and the exception as value
     * @return null
     */
    public function setServiceExceptions(array $serviceExceptions) {
        $this->serviceExceptions = $serviceExceptions;
    }

    /**
     * Gets the exceptions of the chained services
     * @return array Array with the name of the service as key and the exception
     * as value
     */
    public function getServiceExceptions() {
        return $this->serviceExceptions;
    }

}
