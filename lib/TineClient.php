<?php

/**
 * tine Groupware php client
 *
 * @package     Tine Client
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2026 Metaways Infosystems GmbH (https://www.metaways.de)
 * @link        https://github.com/tine-groupware/tine-client-php
 */

use \Datto\JsonRpc\Http\Client;
use \Laminas\Log\Logger;

/**
 * class connects to the tine Groupware
 */
class TineClient
{
    /**
     * configuration
     * 
     * @var ?\Laminas\Config\Config
     */
    protected $_config = null;

    /**
     * tine 2.0 service
     * 
     * @var ?Client
     */
    protected $_tine = null;

    /**
     * logger
     * 
     * @var ?Logger
     */
    protected $_logger = null;
    
    /**
     * the constructor
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->_initSettings();
        $this->_initConfig();
        $this->_initLogger();

        $this->_tine = new Client($this->_config->tineurl);
        $this->_tine->setHeader('X-TINE20-REQUEST-TYPE', 'JSON');
        
        $this->_config->setReadOnly();
        $this->_logger->info(__METHOD__ . '::' . __LINE__ . ' init complete');
    }
    
    /**
     * init php settings
     */
    protected function _initSettings(): void
    {
        // TODO make this configurable
        error_reporting(E_COMPILE_ERROR | E_CORE_ERROR | E_ERROR | E_PARSE);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        ini_set('iconv.internal_encoding', 'utf-8');
    }
    
    /**
     * init config
     */
    protected function _initConfig(): void
    {
        $configData = include('conf.php');
        if($configData === false) {
            die ('central configuration file conf.php not found in includepath: ' . get_include_path());
        }
        $this->_config = new \Laminas\Config\Config($configData, true);

        $this->_initCustomConfig();
    }

    protected function _initCustomConfig(): void
    {
        // add some custom config
    }

    /**
     * init config
     */
    protected function _initLogger(): void
    {
        $this->_logger = new Logger();
        if ($this->_config->logfile) {
            $writer = new \Laminas\Log\Writer\Stream($this->_config->logfile);
        } else {
            $writer = new \Laminas\Log\Writer\Noop();
        }
        $this->_logger->addWriter($writer);
    }

    /**
     * returns config
     * 
     * @return \Laminas\Config\Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * login
     */
    public function login()
    {
        if ($this->_config->username && $this->_config->password) {
            $this->_logger->info(__METHOD__ . '::' . __LINE__ . ' logging in ...');

            try {
                $this->_tine->query('Tinebase.login', [
                    'username' => $this->_config->username,
                    'password' => $this->_config->password
                ], $data)->send();

                if (!isset($data['success']) || !$data['success']) {
                    throw new Exception('login not successful');
                }
                if (!isset($data['jsonKey'])) {
                    throw new Exception('response is missing jsonKey');
                }
                $this->_tine->setHeader('X-Tine20-JsonKey', $data['jsonKey']);
                if (!isset($data['sessionId'])) {
                    throw new Exception('response is missing sessionId');
                }
                $this->_tine->setHeader('Cookie', 'TINE20SESSID=' . $data['sessionId']);

            } catch (Exception $e) {
                $this->_logger->err('login failed: ' . get_class($e) . ': ' . $e->getMessage());
                die('Could not login! (' . $e->getMessage() . ')');
            }
            
            $this->_logger->info(__METHOD__ . '::' . __LINE__ . ' login successful');
            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($data, TRUE));
        } else {
            die('Username and password missing');
        }
    }

    /**
     * logout
     *
     * @throws \Datto\JsonRpc\Http\HttpException
     * @throws ErrorException
     */
    public function logout(): bool
    {
        $this->_tine->query('Tinebase.logout', [], $response)->send();

        if (!isset($response['success']) || !$response['success']) {
            $this->_logger->err(__METHOD__ . '::' . __LINE__ . ' logout failure: ' . $response->getError()->getMessage());
            return false;
        } else {
            $this->_logger->info(__METHOD__ . '::' . __LINE__ . ' logout successful');
            return true;
        }
    }

    public function __call(string $method, array $args): array
    {
        $this->_tine->query($method, $args, $response)->send();
        $this->_logger->debug(__METHOD__ . '::' . __LINE__ . ' Response: ' . print_r($response, true));
        return $response;
    }
}
