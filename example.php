<?php
/**
 * example tine-client-php usage
 *
 * execute with:
 * $ php example.php
 *
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2026 Metaways Infosystems GmbH (https://www.metaways.de)
 */


require_once 'vendor/autoload.php';

$tineConnector = new TineClient();

// login, do stuff, logout
echo "login ... \n";
$tineConnector->login();

// example where we fetch some CRM leads ...
$method = 'Crm.searchLeads';
echo "$method ... \n";
$leads = $tineConnector->{$method}(filter: [], paging: ['start' => 0, 'limit' => 4]);
echo "Got " . count($leads['results']) . " leads\n";
// print_r($leads);

// now we add a lead
$method = 'Crm.saveLead';
echo "$method ... \n";
$result = $tineConnector->{$method}(recordData: [
    'lead_name' => 'My special lead',
    'leadstate_id' => 1,
    'leadtype_id' => 1,
    'leadsource_id' => 1,
]);
echo print_r($result, true);

echo "logout ... \n";
$tineConnector->logout();
