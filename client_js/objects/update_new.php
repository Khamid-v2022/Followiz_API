<?php 

class Update{
 
    // database connection and table name
    private $conn;
    private $table_name = "updates_new";
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read products
    function read(){

        $query = "SELECT * FROM " . $this->table_name . " ORDER BY date DESC, id limit 50";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();    
     
        return $stmt;
    }

}

?>


