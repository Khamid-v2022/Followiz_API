<?php 

class Agent{
 
    // database connection and table name
    private $conn;
    private $table_name = "agent_ratings";
 
    // object properties
    public $id;
    public $user_id;
    public $msg_id;
    public $agent_id;
    public $ticket_id;
    public $rating;
    public $response_time;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read products
    function read(){
     
        // select all query
        
        $query = "SELECT
                    *
                  FROM
                    " . $this->table_name. " 
                WHERE
                    user_id = :user_id AND
                    ticket_id = :ticket_id";

     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":ticket_id", $this->ticket_id);
     
        // execute query
        $stmt->execute();    
     
        return $stmt;
    }


    // used when filling up the update product form
    function readOne(){
     
        // query to read single record
        $query = "SELECT * FROM
                    " . $this->table_name . " 
                WHERE
                    user_id = :user_id AND
                    ticket_id = :ticket_id AND
                    msg_id = :msg_id";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":ticket_id", $this->ticket_id);
        $stmt->bindParam(":msg_id", $this->msg_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->id = $row['id'];
        $this->user_id = $row['user_id'];
        $this->agent_id = $row['agent_id'];
        $this->ticket_id = $row['ticket_id'];
        $this->msg_id = $row['msg_id'];
        $this->rating = $row['rating'];
        $this->response_time = $row['response_time'];
    }

    

    // create product
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_id=:user_id, agent_id=:agent_id,  ticket_id=:ticket_id,  msg_id=:msg_id,rating=:rating, response_time=:response_time";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->agent_id=htmlspecialchars(strip_tags($this->agent_id));
        $this->ticket_id=htmlspecialchars(strip_tags($this->ticket_id));
        $this->msg_id=htmlspecialchars(strip_tags($this->msg_id));
        $this->rating=htmlspecialchars(strip_tags($this->rating));
        $this->response_time=htmlspecialchars(strip_tags($this->response_time));
       
     
        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":agent_id", $this->agent_id);
        $stmt->bindParam(":ticket_id", $this->ticket_id);
        $stmt->bindParam(":msg_id", $this->msg_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":response_time", $this->response_time);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
         
    }

    // update the product
    function update(){
     
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    rating = :rating,
                    response_time = :response_time
                WHERE
                    id = :id ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        //$this->agent_id=htmlspecialchars(strip_tags($this->agent_id));
        //$this->ticket_id=htmlspecialchars(strip_tags($this->ticket_id));
        //$this->msg_id=htmlspecialchars(strip_tags($this->msg_id));
        $this->rating=htmlspecialchars(strip_tags($this->rating));
        $this->response_time=htmlspecialchars(strip_tags($this->response_time));
       
     
        // bind values
        $stmt->bindParam(":id", $this->id);
        //$stmt->bindParam(":agent_id", $this->agent_id);
        //$stmt->bindParam(":ticket_id", $this->ticket_id);
        //$stmt->bindParam(":msg_id", $this->msg_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":response_time", $this->response_time);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }


}

?>