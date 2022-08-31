<?php 

class Vote{
 
    // database connection and table name
    private $conn;
    private $table_name = "agent_ratings";
 
    // object properties
    public $id;
    public $user_id;
    public $ticket_id;
    public $rating;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

  

    // used when filling up the update product form
   function readOne(){
     
        // query to read single record
        $query = "SELECT * FROM
                    " . $this->table_name . " 
                WHERE
                    user_id = :user_id AND
                    service_id = :service_id";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":service_id", $this->service_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->user_id = $row['user_id'];
        $this->service_id = $row['service_id'];
        $this->vote = $row['vote_new'];
    }

    // read products
    function read($uid,$tid){
     
        // select all query
        /*$query = "SELECT
                    id,user_id,service_id, vote
                  FROM
                    " . $this->table_name . " 
                WHERE
                    user_id = :user_id
                ORDER BY
                    service_id ASC";*/

        $query = "SELECT * FROM ".$this->table_name." WHERE user_id = ".$uid." and ticket_id = ".$tid." ";

     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        //$stmt->bindParam(":user_id", $this->user_id);
     
        // execute query
        $stmt->execute();    
     
        return $stmt;
    }


}

?>
