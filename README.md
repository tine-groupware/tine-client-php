# tine-client-php

## setup

    composer install

## usage

    php example.php

## example that saves a lead

~~~php
$tineConnector->login();
$method = 'Crm.saveLead';
$result = $tineConnector->{$method}(recordData: [
    'lead_name' => 'My special lead',
    'leadstate_id' => 1,
    'leadtype_id' => 1,
    'leadsource_id' => 1,
]);
$tineConnector->logout();
~~~
