<?php 

class Sort{
 
    // database connection and table name
    private $conn;
    private $table_name = "category_order";
 
    // object properties
    public $id;
    public $category_id;
    public $sort_order;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read products
    function read(){
     
        // select all query
        
        $query = "SELECT * FROM " . $this->table_name ; 
                /* WHERE  user_id = :user_id AND  ticket_id = :ticket_id"; */

     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        //$stmt->bindParam(":user_id", $this->user_id);
        //$stmt->bindParam(":ticket_id", $this->ticket_id);
     
        // execute query
        $stmt->execute();    
     
        return $stmt;
    }

}

?>


