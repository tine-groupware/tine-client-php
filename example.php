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
echo "Crm.searchLeads ... \n";
$method = 'Crm.searchLeads';
$leads = $tineConnector->{$method}(filter: [], paging: ['start' => 0, 'limit' => 4]);
echo "Got " . count($leads['results']) . " leads\n";
// print_r($leads);

echo "logout ... \n";
$tineConnector->logout();
