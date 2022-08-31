<?php 

class Agent{
 
    // database connection and table name
    private $conn;
    private $table_name = "agents";
 
    // object properties
    public $id;
    public $name;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // used when filling up the update product form
    function readAll(){
     
        // query to read single record
        $query = "SELECT * FROM
                    " . $this->table_name ;
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
      
     
        // execute query
        $stmt->execute();
        return $stmt;
     }
     
}

?>