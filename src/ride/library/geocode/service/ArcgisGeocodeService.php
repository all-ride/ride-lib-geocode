<?php

namespace ride\library\geocode\service;

use ride\library\geocode\coordinate\GenericGeocodeCoordinate;
use ride\library\geocode\coordinate\GeocodeCoordinate;
use ride\library\geocode\exception\GeocodeException;
use ride\library\geocode\result\GenericGeocodeResult;
use ride\library\http\client\Client;
use ride\library\http\Response;

use \Exception;

/**
 * Arcgis implementation of a geocode service
 */
class ArcgisGeocodeService extends AbstractGeocodeService {

    /**
     * Name of this service
     * @var string
     */
    const NAME = 'arcgis';

    /**
     * URL to query for coordinates
     * @var string
     */
    const URL_GEOCODE = 'http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/find?text=%address%&f=pjson&outFields=*';

    /**
     * URL to query coordinates
     * @var string
     */
    const URL_GEOCODE_REVERSE = 'http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/find?location=%longitude%,%latitude%&f=pjson&outFields=*';

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

            return $this->parseServiceResults($data->locations);
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
        try {
            $url = str_replace('%latitude%', $coordinate->getLatitude(), self::URL_GEOCODE_REVERSE);
            $url = str_replace('%longitude%', $coordinate->getLongitude(), $url);

            $response = $this->httpClient->get($url);
            if ($response->getStatusCode() != Response::STATUS_CODE_OK) {
                throw new GeocodeException('Server replied with HTTP status code ' . $response->getStatusCode());
            }

            $body = $response->getBody();
            $data = json_decode($body);

            return $this->parseServiceResults($data->locations);
        } catch (Exception $exception) {
            throw new GeocodeException('Could not reverse geocode ' . $coordinate . ': ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Parses a single result from the service into a GeocodeResult object
     * @param mixed $serviceResult Result data container
     * @return \ride\library\geocode\GeocodeResult
     */
    protected function parseServiceResult($serviceResult) {
        $properties = array(
            'coordinate' => new GenericGeocodeCoordinate($serviceResult->feature->geometry->y, $serviceResult->feature->geometry->x),
        );

        if (isset($serviceResult->feature->attributes->StName) && isset($serviceResult->feature->attributes->StType)) {
            $properties['street'] = $serviceResult->feature->attributes->StName . $serviceResult->feature->attributes->StType;
        }
        if (isset($serviceResult->feature->attributes->Postal)) {
            $properties['postalCode'] = $serviceResult->feature->attributes->Postal;
        }
        if (isset($serviceResult->feature->attributes->City)) {
            $properties['city'] = $serviceResult->feature->attributes->City;
        }
        if (isset($serviceResult->feature->attributes->Region)) {
            $properties['region'] = $serviceResult->feature->attributes->Region;
        }
        if (isset($serviceResult->feature->attributes->Country)) {
            $properties['countryCode'] = substr($serviceResult->feature->attributes->Country, 0, 2);
        }

        return new GenericGeocodeResult($properties);
    }

}
