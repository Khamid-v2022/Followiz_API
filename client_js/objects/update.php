<?php 

class Update{
 
    // database connection and table name
    private $conn;
    private $table_name = "updates";
 
    // object properties
    public $id;
    public $service_id;
    public $price;
    public $old_price;
    public $update_message;
    public $service_details;
    public $created_at;
    public $updated_at;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read products
    function read(){
     
        // select all query
		 $page = 0;
         $offset = 0;
		 $rec_limit = 100;
        if( isset($_GET['page'] ) ) {
            $page = $_GET['page'] - 1;
            $offset = $rec_limit * $page ;
         }
		 $domain = "main";
		 if( isset($_GET['type'] ) ) {
			 $domain = $_GET['type'];
		 } 
            
      
        $query = "SELECT * FROM " . $this->table_name ." where domain='".$domain."' ORDER BY lasttime DESC limit ".$offset.",".$rec_limit; 
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

	function total(){
     
      $domain = "main";
		 if( isset($_GET['type'] ) ) {
			 $domain = $_GET['type'];
		 } 
        $query = "SELECT * FROM " . $this->table_name ." where domain='".$domain."'  ORDER BY lasttime DESC"; 
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
  
    

    // create product
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    service_id=:service_id, update_message=:update_message,  service_details=:service_details";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->service_id=htmlspecialchars(strip_tags($this->service_id));
        $this->update_message=htmlspecialchars(strip_tags($this->update_message));
        $this->service_details=htmlspecialchars(strip_tags($this->service_details));
       
       
     
        // bind values
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":update_message", $this->update_message);
        $stmt->bindParam(":service_details", $this->service_details);
      
     
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
                    update_message = :update_message,
                    rating = :rating,
                    service_details = :service_details
                WHERE
                    service_id = :service_id ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->service_id=htmlspecialchars(strip_tags($this->service_id));
        $this->update_message=htmlspecialchars(strip_tags($this->update_message));
        $this->service_details=htmlspecialchars(strip_tags($this->service_details));
       
     
        // bind values
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":update_message", $this->update_message);
        $stmt->bindParam(":service_details", $this->service_details);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }


}

?>


