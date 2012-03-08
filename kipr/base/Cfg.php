<?php

// Класс, хранящий все рабочие настройки
class Cfg
{
    private static $instance;
    private $settings = array();
    private $objects = array();

    /**
     * Name of the connection to use by default.
     *
     * <code>
     * ActiveRecord\Config::initialize(function($cfg) {
     *   $cfg->set_model_directory('/your/app/models');
     *   $cfg->set_connections(array(
     *     'development' => 'mysql://user:pass@development.com/awesome_development',
     *     'production' => 'mysql://user:pass@production.com/awesome_production'));
     * });
     * </code>
     *
     * This is a singleton class so you can retrieve the {@link Singleton} instance by doing:
     *
     * <code>
     * $config = ActiveRecord\Config::instance();
     * </code>
     *
     * @var string
     */
    private $default_connection = 'development';
    /**
     * Contains the list of database connection strings.
     *
     * @var array
     */
    private $connections = array();
    /**
     * Contains a Logger object that must impelement a log() method.
     *
     * @var object
     */
    private $logger;
    
    private function __construct() {}
    
    public static function inst() {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    function get($key) {
        if(isset($this->settings[$key])) {
            return $this->settings[$key];
        }
        return NULL;
    }
    
    function set(array $values) {
        foreach($values as $key => $value) {
            if(is_object($value)) {
                $this->objects[$key] = $value;
            } else {
                $this->settings[$key] = $value;
            }
        }
    }
    
    function getobj($key) {
        if(isset($this->objects[$key])) {
            return $this->objects[$key];
        }
        return NULL;
    }
    
    public function __clone() {
        throw new Exception('Cloning the registry is not permitted');
    }

    /**
     * Sets the list of database connection strings.
     *
     * <code>
     * $config->set_connections(array(
     *     'development' => 'mysql://username:password@127.0.0.1/database_name'));
     * </code>
     *
     * @param array $connections Array of connections
     * @param string $default_connection Optionally specify the default_connection
     * @return void
     * @throws ActiveRecord\ConfigException
     */
    public function set_connections($connections, $default_connection = null) {
        if(!is_array($connections))
            throw new ConfigException("Connections must be an array");
        
        if($default_connection)
            $this->set_default_connection($default_connection);
        
        $this->connections = $connections;
    }
    
    /**
     * Returns the connection strings array.
     *
     * @return array
     */
    public function get_connections() {
        return $this->connections;
    }
    
    /**
     * Returns a connection string if found otherwise null.
     *
     * @param string $name Name of connection to retrieve
     * @return string connection info for specified connection name
     */
    public function get_connection($name) {
        if(array_key_exists($name, $this->connections))
            return $this->connections[$name];
        
        return null;
    }
    
    /**
     * Returns the default connection string or null if there is none.
     *
     * @return string
     */
    public function get_default_connection_string() {
        return array_key_exists($this->default_connection, $this->connections) ?
                  $this->connections[$this->default_connection] : null;
    }
    
    /**
     * Returns the name of the default connection.
     *
     * @return string
     */
    public function get_default_connection() {
        return $this->default_connection;
    }
    
    /**
     * Set the name of the default connection.
     *
     * @param string $name Name of a connection in the connections array
     * @return void
     */
    public function set_default_connection($name) {
        $this->default_connection = $name;
    }
    
    /**
     * Turn on/off logging
     *
     * @param boolean $bool
     * @return void
     */
    public function set_logging($bool) {
        $this->settings['sql_log'] = (bool)$bool;
    }
    
    /**
     * Sets the logger object for future SQL logging
     *
     * @param object $logger
     * @return void
     * @throws ConfigException if Logger objecct does not implement public log()
     */
    public function set_logger($logger) {
        $klass = new ReflectionClass($logger);
        
        if(!$klass->getMethod('log') || !$klass->getMethod('log')->isPublic())
            throw new ConfigException("Logger object must implement a public log method");
        
        $this->logger = new $logger;
    }
    
    /**
     * Return whether or not logging is on
     *
     * @return boolean
     */
    public function get_logging() {
       return $this->settings['sql_log'];
    }
    
    /**
     * Returns the logger
     *
     * @return object
     */
    public function get_logger() {
        return $this->logger;
    }
}