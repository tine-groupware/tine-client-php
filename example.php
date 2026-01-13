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

echo "do something ... \n";
// TODO implement

echo "logout ... \n";
$tineConnector->logout();
