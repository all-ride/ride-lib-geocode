<?php

namespace ride\library\geocode\result;

/**
 * Data container for a geocode result
 */
interface GeocodeResult {

    /**
     * Gets the coordinate of this result
     * @return \ride\library\geocode\coordinate\GeocodeCoordinate
     */
    public function getCoordinate();

    /**
     * Gets the name of the street
     * @return string|null
     */
    public function getStreet();

    /**
     * Gets the number/box in the street
     * @return string|null
     */
    public function getNumber();

    /**
     * Gets the postal code of the city
     * @return string|null
     */
    public function getPostalCode();

    /**
     * Gets the name of the city
     * @return string|null
     */
    public function getCity();

    /**
     * Gets the name of the region
     * @return string|null
     */
    public function getRegion();

    /**
     * Gets the code of the region
     * @return string|null
     */
    public function getRegionCode();

    /**
     * Gets the name of the country
     * @return string|null
     */
    public function getCountry();

    /**
     * Gets the code of the country
     * @return string|null
     */
    public function getCountryCode();

}
