<?php

namespace ride\library\geocode\coordinate;

/**
 * Generic implementation of a coordinate for geocoding
 */
class GenericGeocodeCoordinate implements GeocodeCoordinate {

    /**
     * Latitude value
     * @var float
     */
    protected $longitude;

    /**
     * Longitude value
     * @var float
     */
    protected $latitude;

    /**
     * Constructs a new coordinate
     * @param float $latitude Latitude value
     * @param float $longitude Longitude value
     * @return null
     */
    public function __construct($latitude, $longitude) {
        $this->latitude = (float) $latitude;
        $this->longitude = (float) $longitude;
    }

    /**
     * Creates a new generic geocode coordinate based on another implementation
     * @param GeocodeCoordinate $coordinate
     * @return GenericGeocodeCoordinate
     */
    public static function fromGeocodeCoordinate(GeocodeCoordinate $coordinate) {
        return new self($coordinate->getLatitude(), $coordinate->getLongitude());
    }

    /**
     * Gets a string representation of this coordinate
     * @return string
     */
    public function __toString() {
        return $this->latitude . ',' . $this->longitude;
    }

    /**
     * Gets the latitude value
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Gets the longitude value
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Calculates the distance with another coordinate
     * @param Coordinate $coordinate
     * @return float Distance in km
     */
    public function getDistance(Coordinate $coordinate) {
        $latitude = $coordinate->getLatitude();
        $longitude = $coordinate->getLongitude();

        return acos(sin(deg2rad($this->latitude)) * sin(deg2rad($latitude)) + cos(deg2rad($this->latitude)) * cos(deg2rad($latitude)) * cos(deg2rad($longitude - $this->longitude))) * 6371;
    }

}
