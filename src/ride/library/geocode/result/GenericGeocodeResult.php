<?php

namespace ride\library\geocode\result;

/**
 * Data container for a geocode result
 */
class GenericGeocodeResult implements GeocodeResult {

    /**
     * Coordinate for this result
     * @var \ride\library\geocode\Coordinate
     */
    protected $coordinate;

    /**
     * Name of the street
     * @var string
     */
    protected $street;

    /**
     * Number in the street
     * @var string
     */
    protected $number;

    /**
     * Postal code of the city
     * @var string
     */
    protected $postalCode;

    /**
     * Name of the city
     * @var string
     */
    protected $city;

    /**
     * Name of the region
     * @var string
     */
    protected $region;

    /**
     * Code of the region
     * @var string
     */
    protected $regionCode;

    /**
     * Name of the country
     * @var string
     */
    protected $country;

    /**
     * Code of the country
     * @var string
     */
    protected $countryCode;

    /**
     * Constructs a new geocode result
     * @param array $values Values for the properties of this result
     * @return null
     */
    public function __construct(array $values) {
        foreach ($this as $property => $value) {
            if (isset($values[$property])) {
                $this->$property = $values[$property];
            }
        }
    }

    /**
     * Gets a string representation of this result
     * @return string
     */
    public function __toString() {
        return json_encode($this->toArray());
    }

    /**
     * Gets an array representation of this result
     * @return array
     */
    public function toArray() {
        $properties = array();

        foreach ($this as $property => $value) {
            if ($property == 'coordinate') {
                $properties[$property] = array(
                    'latitude' => $value->getLatitude(),
                    'longitude' => $value->getLongitude(),
                );
            } else {
                $properties[$property] = $value;
            }
        }

        return $properties;
    }

    /**
     * Gets the coordinate of this result
     * @return \ride\library\geocode\coordinate\GeocodeCoordinate
     */
    public function getCoordinate() {
        return $this->coordinate;
    }

    /**
     * Gets the name of the street
     * @return string|null
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * Gets the number/box in the street
     * @return string|null
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Gets the postal code of the city
     * @return string|null
     */
    public function getPostalCode() {
        return $this->postalCode;
    }

    /**
     * Gets the name of the city
     * @return string|null
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Gets the name of the region
     * @return string|null
     */
    public function getRegion() {
        return $this->region;
    }
    /**
     * Gets the code of the region
     * @return string|null
     */
    public function getRegionCode() {
        return $this->regionCode;
    }

    /**
     * Gets the name of the country
     * @return string|null
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Gets the code of the country
     * @return string|null
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

}
