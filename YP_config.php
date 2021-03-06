<?php
// YP_config.php
// Here are two database connections and other settings
// Set here your mysql database connections
//Leads DB:
//$db2_name = "testYP_LEADS";
$db2_name = "testYP";
$db2_user = "root";
$db2_passwd = "shirker123";
$db2_host = "localhost";
$db2_USA_BUZ_TABLE = "usa_bus_leads_yp";

// Define, where you going to save cookies from sites
define('_CURL_COOKIES_FILE_',"/tmp/YP_downloader/cookies.txt");
// Log file path:
define('_BG_RUN_LOG_FILE_', dirname(__FILE__) . "/background_run.log");

// Main DB:
class DB1 extends mysqli { 
    // Main MYSQL database set here
    protected $db_name = "testYP"; // Database
    protected $db_user = "root"; // DB login
    protected $db_pass = "shirker123"; // DB passwd
    protected $db_host = "localhost"; // host
    protected static $_instance;  //экземпляр объекта
    public static function getInstance() { // получить экземпляр данного класса 
            if (self::$_instance === null) { // если экземпляр данного класса  не создан
                self::$_instance = new self;  // создаем экземпляр данного класса 
            } 
            return self::$_instance; // возвращаем экземпляр данного класса
      }
    private function __construct() {
        @parent::__construct($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        } 
    }
    private function __clone() { 
        }
    private function __wakeup() { 
        }
}
define('_LEADS_DB_',$db2_name);
define('_LEADS_DB_USER_',$db2_user);
define('_LEADS_DB_PASS_',$db2_passwd);
define('_LEADS_DB_HOST_',$db2_host);
define('_LEADS_DB_USA_BUZ_TABLE_',$db2_USA_BUZ_TABLE);

class DB2 extends mysqli { 
    // LEADS Database set here
    // this class is handling LEADS database connection
    // I think for convenience is better to use separate BD for stored leads
    
    protected $db_name = _LEADS_DB_; // Database
    protected $db_user = _LEADS_DB_USER_; // DB login
    protected $db_pass = _LEADS_DB_PASS_; // DB passwd
    protected $db_host = _LEADS_DB_HOST_; // host
    protected static $_instance;  //экземпляр объекта
  
    public static function getInstance() { // получить экземпляр данного класса 
            if (self::$_instance === null) { // если экземпляр данного класса  не создан
                self::$_instance = new self;  // создаем экземпляр данного класса 
            } 
            return self::$_instance; // возвращаем экземпляр данного класса
      }

    private function __construct() {
        @parent::__construct($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        } 
    }
    private function __clone() { 
        }
        
    private function __wakeup() { 
        }
}

?>
