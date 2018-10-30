<?php
require_once("restful.php");
require_once("../settings.php");
class API extends REST {
     
    public $data = "";
    private $db = NULL;
    
    // basic values from incoming device request
    public $device = '';
    
    // default readings fields to persist to DB
    public $readings = array (
        array('value1', null),
        array('value2', null),
        array('value3', null),
        array('value4', null),
        array('value5', null),
        array('value6', null),
        array('value7', null),
        array('value8', null),
        array('value9', null),
        array('value10', null)
    );
    
    /**
     * init parent contructor and initiate database connection
     */
    public function __construct() {
        parent::__construct();
        $this->dbConnect();
    }
         
    /**
     * connect to DB
     */
    private function dbConnect() {
        $this->db = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB);        
    }
     
    /*
     * public method for access api.
     *  this method dynmically call the method based on the query string
     */
    public function processApi() {
    
        (isset($_REQUEST['request'])) ? $request = $_REQUEST['request'] : $request = '';
        
        $func = strtolower(trim(str_replace("/","",$request)));
        if ( (int) method_exists($this,$func) > 0) $this->$func();
        
        // if the method doesn't exist with in this class, response would be "Page Not Found".
        else $this->response('Error code 404, Page not found',404);
    }

    /**
     * handle incoming device request
     */
    public function processRequest() {
    
        // get all POST params from device
        isset($this->_request['device']) ? $this->device = $this->_request['device'] : $this->device = '';
        
        // get all possible readings passed by HTTP request
        foreach ($this->readings as $readingKey => $readingValue) isset($this->_request[$readingValue[0]]) ? $this->readings[$readingKey][1] = $this->_request[$readingValue[0]] : $this->readings[$readingKey][1] = null;
    }

    /**
     * log current API values to DB
     */
    public function logRequestDB() {

        $sql = "INSERT INTO devices (`device`";
        
        // insert all the readings field values to the query
        foreach ($this->readings as $readingKey => $readingValue) $sql .= ",`" . $readingValue[0] . "`";
        $sql .= ") VALUES ( '".$this->device."'";
        
        // insert all the readings to the query
        foreach ($this->readings as $readingKey => $readingValue) $sql .= ",'" . $readingValue[1] . "'";
        $sql .= ")";

        // insert query
        if ($this->db->query($sql) === TRUE) {
            $this->response("Data logged successfully.", 200);
        } else {
            $this->response("Error: " . $sql . "<br>" . $this->db->error, 500);
        }
    }

    /**
     * weather clock device log current conditions
     */
    private function log () {
    
        // validate request is POST
        if ($this->get_request_method() != "POST") $this->response('', 406);
        
        $this->processRequest(); 
        $this->logRequestDB(); 
    }
     
    /**
     * weather clock device log current conditions
     */
    private function read () {
    
        // validate request is POST
        if ($this->get_request_method() != "GET") $this->response('', 406);
        
        // get device name
        isset($this->_request['device']) ? $this->device = $this->_request['device'] : $this->device = '';
        
        // get recent readings from device 
        $sql = "SELECT * FROM devices WHERE `device` = '" . $this->device ."' ORDER BY `entry` DESC LIMIT 100";
        $myResults = array();
        if ($result = $this->db->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) $myResults[] = $row;
            $this->response(json_encode($myResults, JSON_PRETTY_PRINT), 200);
        } else {
            $this->response("Error: No Records Found.", 500);
        }
    }
     
    /**
     * Encode array into JSON
     */
    private function json($data) {
        if (is_array($data)) return json_encode($data);
    }
}
 
// Initiate Library on API Request
$api = new API;
$api->processApi();
