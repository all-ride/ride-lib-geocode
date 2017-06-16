<?php

namespace ride\library\geocode\result;

/**
 * Data container for a Google geocode result
 */
class GoogleGeocodeResult extends GenericGeocodeResult {

    /**
     * Id of the geo location for Google
     * @var string
     */
    protected $placeId;

    /**
     * Gets the place id of this result
     * @return string
     */
    public function getPlaceId() {
        return $this->placeId;
    }

}
