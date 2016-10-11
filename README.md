# Ride: Geocode Library

Geocoding library of the PHP Ride framework.

## What's In This Library

### GeocodeService

The _GeocodeService_ interface is the main workhorse of this library.
The implementations of this class perform the actual looking up of addresses to other providers.

There are a couple of implementations included in this library:

#### ArcgisGeocodeService

This service uses Arcgis to perform geocoding of addresses. 
Check [https://www.arcgis.com](https://www.arcgis.com) for more information about this service.

#### FreeGeoIpGeocodeService

This service uses freegeoip.net to get the location of an IP address or domain names.
Check [https://www.freegeoip.net](https://www.freegeoip.net) for more information about this service.

#### GoogleGeocodeService

This service uses Google Maps to perform geocoding of addresses.
Check [https://developers.google.com/maps/documentation/geocoding/intro](https://developers.google.com/maps/documentation/geocoding/intro] for more information about this service.

#### ChainGeocodeService

Use the _ChainGeocodeService_ to chain simular services together.
When the first service can't handle the lookup, the following service will be polled and so on.

### GeocodeResult

The _GeocodeResult_ interface is used to return the result of a _GeocodeService_.
A default implementation is provided by the _GenericGeocodeResult_ class. 

### GeocodeCoordinate

The _GeocodeCoordinate_ interface is used to obtain the found coordinates from a _GeocodeResult_.
A default implementation is provided by the _GenericGeocodeCoordinate_ class.

### Geocoder

The _Geocoder_ is the facade to this library.
You can add different services and give them a name. 
This name is then used to lookup specific addresses.

## Code Sample

Check this code sample to see some possibilities of this library:

```php
<?php

use ride\library\http\client\Client;
use ride\library\geocode\service\ArcgisGeocodeService;
use ride\library\geocode\service\ChainGeocodeService;
use ride\library\geocode\service\FreeGeoIpGeocodeService;
use ride\library\geocode\service\GoogleGeocodeService;
use ride\library\geocode\Geocoder;

function createGeocoder(Client $httpClient) {
    // create a google service
    $googleService = new GoogleGeocodeService($httpClient);
    // optionally set a API key
    $googleService->setApiKey('your-api-key');
    
    // create a chain of address services
    $addressService = new ChainGeocodeService('address');
    $addressService->addService($googleService);
    $addressService->addService(new ArcgisGeocodeService($httpClient)); 
    
    // create a chain of ip services
    $ipService = new ChainGeocodeService('ip');
    $ipService->addService(new FreeGeoIpGeocodeService($httpClient));
    
    // create the geocoder and set our defined services to it
    $geocoder = new Geocoder();
    $geocoder->addService($addressService);
    $geocoder->addService($ipService);
    
    return $geocoder;
}

function geocodeStuff(Geocoder $geocoder) {
    try {
        $geocodeResult = $geocoder->geocode('address', 'Vital de costerstraat, Leuven'); 
        $geocodeResult = $geocoder->geocode('ip', 'github.com');
        $geocodeResult = $geocoder->geocode('ip', '8.8.8.8');
    } catch (GeocodeException $exception) {
        // could not find any result
    }
}
```

## Related Modules

- [ride/app-geocode](https://github.com/all-ride/ride-app-geocode)

## Installation

You can use [Composer](http://getcomposer.org) to install this library.

```
composer require ride/lib-geocode
```

