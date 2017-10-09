Omni Lead PHP Client
====================

A simple PHP client to use the Omni Lead API.

## Requirements

* PHP >= 5.6
* Composer 

## Installation

We use HTTPPlug as the HTTP client abstraction layer.
In this example, we will use [Guzzle](https://github.com/guzzle/guzzle) v6 as the HTTP client implementation.

`lead-php-client` uses [Composer](http://getcomposer.org).
The first step to use `lead-php-client` is to download composer:

```bash
$ curl -s http://getcomposer.org/installer | php
```

Then, run the following command to require the library:
```bash
$ php composer.phar require omnisell/lead-php-client php-http/guzzle6-adapter
```

If you want to use another HTTP client implementation, you can check [here](https://packagist.org/providers/php-http/client-implementation) the full list of HTTP client implementations. 

## Getting started

### Initialise the client
You first need to initialise the client with your credentials: currently simple API token authorization is enough.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$clientBuilder = new \Omni\Lead\LeadClientBuilder('http://localhost/');
$client = $clientBuilder->buildAuthenticatedByToken('', '', 'token', '');
```

### Additional error information

```php
try {
    $clientBuilder = new \Omni\Lead\LeadClientBuilder($url);
    $client = $clientBuilder->buildAuthenticatedByToken('', '', $token, '');
    $client->getLeadApi()->create($data);
} catch (\Omni\Lead\Exception\HttpException $e) {
    $body = $e->getResponse()->getBody();
    $decodedBody = json_decode($body->getContents(), true);
}
```

Note: if `$body->getContents()` is empty perform `$body->rewind()` before decoding json string

### Lead API

```php
// Create
$newLead = $client->getLeadApi()->create([
    'title' => 'New lead',
    'message' => 'Message',
    'contacts' => [
        [
            'firstName' => 'First contact first name',
            'lastName' => 'First contact last name',
            'emails' => [
                ['email' => 'email one'],
                ['email' => 'email two'],
            ],
        ],
        [
            'firstName' => 'Second contact first name',
            'lastName' => 'Second contact last name',
            'phones' => [
                ['phone' => 'phone one'],
                ['phone' => 'phone two'],
            ],
        ],
    ],
    'source' => 'source type of lead',
    'metas' => [
        ['key' => 'meta key 1', 'value' => 'meta value 1'],
        ['key' => 'meta key 2', 'value' => 'meta value 2'],
    ],
]);
echo $newLead['id']; // display "some-new-id"
 
// Update
$updatedLead = $client->getLeadApi()->upsert('some-new-id', ['title' => 'Updated lead']);
echo $updatedLead['title']; // display "Updated lead"
```

### Offer API

```php
// Get offer
$offer = $client->getOfferApi()->get('uuid-of-offer');
echo $offer['status']; // display "shared"
 
// Mark offer as completed (converted)
$offer = $client->getOfferApi()->complete('uuid-of-offer');
echo $updatedLead['status']; // display "converted"
```
