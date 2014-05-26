<?php

namespace ride\library\geocode\service;

use ride\library\geocode\coordinate\GenericGeocodeCoordinate;
use ride\library\geocode\coordinate\GeocodeCoordinate;
use ride\library\geocode\exception\GeocodeException;
use ride\library\geocode\result\GenericGeocodeResult;
use ride\library\http\Response;

use \Exception;

/**
 * Google implementation of a geocode service
 */
class FreeGeoIpGeocodeService extends AbstractGeocodeService {

    /**
     * Name of this service
     * @var string
     */
    const NAME = 'freegeoip';

    /**
     * URL to query for coordinates
     * @var string
     */
    const URL_GEOCODE = 'http://freegeoip.net/json/%address%';

    /**
     * Performs geocoding on the provided query
     * @param string $address Location or IP address
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     * @throws \ride\library\geocode\exception\GeocodeException when the
     * geocoding is not succesfull
     */
    public function geocode($address) {
        try {
            $url = str_replace('%address%', urlencode($address), self::URL_GEOCODE);

            $response = $this->httpClient->get($url);
            if ($response->getStatusCode() != Response::STATUS_CODE_OK) {
                throw new GeocodeException('Server replied with HTTP status code ' . $response->getStatusCode());
            }

            $body = $response->getBody();
            $data = json_decode($body);

            return array($this->parseServiceResult($data));
        } catch (Exception $exception) {
            throw new GeocodeException('Could not geocode ' . $address . ': ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Performs reverse geocoding on the provided coordinates
     * @param \ride\library\geocode\coordinate\GeocodeCoordinate $coordinate
     * Coordinate to reverse lookup
     * @return array Array of geocode results
     * @see \ride\library\geocode\GeocodeResult
     * @throws \ride\library\geocode\exception\GeocodeException when the
     * geocoding is not succesfull
     */
    public function reverseGeocode(GeocodeCoordinate $coordinate) {
        throw new GeocodeException('Could not reverse geocode ' . $coordinate . ': not supported by this service');
    }

    /**
     * Parses a single result from the service into a GeocodeResult object
     * @param mixed $serviceResult Result data container
     * @return \ride\library\geocode\GeocodeResult
     */
    protected function parseServiceResult($serviceResult) {
        $properties = array(
            'coordinate' => new GenericGeocodeCoordinate($serviceResult->latitude, $serviceResult->longitude),
            'postalCode' => $serviceResult->zipcode,
            'city' => $serviceResult->city,
            'region' => $serviceResult->region_name,
            'country' => $serviceResult->country_name,
            'countryCode' => $serviceResult->country_code,
        );

        return new GenericGeocodeResult($properties);
    }

}
