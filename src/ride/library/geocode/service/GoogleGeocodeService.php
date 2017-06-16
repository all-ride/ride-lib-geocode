<?php

namespace ride\library\geocode\service;

use ride\library\geocode\coordinate\GenericGeocodeCoordinate;
use ride\library\geocode\coordinate\GeocodeCoordinate;
use ride\library\geocode\exception\GeocodeException;
use ride\library\geocode\result\GoogleGeocodeResult;
use ride\library\http\client\Client;
use ride\library\http\Response;

use \Exception;

/**
 * Google implementation of a geocode service
 */
class GoogleGeocodeService extends AbstractGeocodeService {

    /**
     * Name of this service
     * @var string
     */
    const NAME = 'google';

    /**
     * URL to query for coordinates
     * @var string
     */
    const URL_GEOCODE = 'https://maps.google.com/maps/api/geocode/json?address=%address%&sensor=false';

    /**
     * API key for Google services
     * @var string
     */
    private $apiKey;

    /**
     * Sets the Google API key
     * @param string $apiKey API key
     * @return null
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
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
        try {
            $url = str_replace('%address%', urlencode($address), self::URL_GEOCODE);
            if ($this->apiKey) {
                $url .= '&key=' . $this->apiKey;
            }

            $response = $this->httpClient->get($url);
            if ($response->getStatusCode() != Response::STATUS_CODE_OK) {
                throw new GeocodeException('Server replied with HTTP status code ' . $response->getStatusCode());
            }

            $body = $response->getBody();
            $data = json_decode($body);

            if (!isset($data->status)) {
                throw new GeocodeException('Unexpected response');
            }

            switch ($data->status) {
                case 'OK':
                    // response is ok, parse the results
                    return $this->parseServiceResults($data->results);
                case 'OVER_QUERY_LIMIT':
                    if (strpos($data->error_message, 'You have exceeded your daily request quota for this API.') !== 0) {
                        // we're over the query limit per second, delay and try again
                        sleep(1);

                        return $this->geocode($address);
                    }

                    // we're over daily limit, throw exception from default
                default:
                    // something else, let's crash
                    throw new GeocodeException('Server replied with status ' . $data->status . ': ' . $data->error_message);
            }
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
        return $this->geocode($coordinate->getLatitude() . ',' . $coordinate->getLongitude());
    }

    /**
     * Parses a single result from the service into a GeocodeResult object
     * @param mixed $serviceResult Result data container
     * @return \ride\library\geocode\GeocodeResult
     */
    protected function parseServiceResult($serviceResult) {
        if (is_array($serviceResult)) {
            $serviceResult = array_shift($serviceResult);
        }

        $properties = array(
            'coordinate' => new GenericGeocodeCoordinate($serviceResult->geometry->location->lat, $serviceResult->geometry->location->lng),
        );

        if (isset($serviceResult->place_id)) {
            $properties['placeId'] = $serviceResult->place_id;
        }

        foreach ($serviceResult->address_components as $component) {
            foreach ($component->types as $type) {
                switch ($type) {
                    case 'street_number':
                        $properties['number'] = $component->long_name;

                        break;
                    case 'route':
                        $properties['street'] = $component->long_name;

                        break;
                    case 'postal_code':
                        $properties['postalCode'] = $component->long_name;

                        break;
                    case 'locality':
                        $properties['city'] = $component->long_name;

                        break;
                    case 'administrative_area_level_1':
                        $properties['region'] = $component->long_name;
                        $properties['regionCode'] = $component->short_name;

                        break;
                    case 'country':
                        $properties['country'] = $component->long_name;
                        $properties['countryCode'] = $component->short_name;

                        break;
                }
            }
        }

        return new GoogleGeocodeResult($properties);
    }

}
