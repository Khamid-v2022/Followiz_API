<?php 
date_default_timezone_set('America/New_York');
class Update{
 
    // database connection and table name
    private $conn;
    private $table_name = "updates";
 
    // object properties
    public $id;
    public $service_id;
    public $update_message;
    public $service_details;
    public $price;
    public $old_price;
    public $status;
    public $created_at;
    public $updated_at;
    public $domain;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readOne(){
        
        // query to read single record
        $query = "SELECT * FROM
                    " . $this->table_name . " 
                WHERE
                    service_id=:service_id ";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(":service_id", $this->service_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
		//print_r($row);
        // set values to object properties
        $this->id = $row['id'];
        $this->service_id = $row['service_id'];
        $this->update_message = $row['update_message'];
        $this->service_details = $row['service_details'];
        $this->price = $row['price'];
        $this->old_price = $row['old_price'];
        $this->status = $row['status'];
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


  
    

    // create product
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    service_id=:service_id, update_message=:update_message,service_details=:service_details,old_price=:old_price,price=:price,status=:status,domain=:domain,lasttime=:lasttime";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
        $this->domain = !empty($this->domain) ? $this->domain : 'main';
        // sanitize
        $this->service_id=htmlspecialchars(strip_tags($this->service_id));
        $this->update_message=htmlspecialchars(strip_tags($this->update_message));
        $this->service_details=htmlspecialchars(strip_tags($this->service_details));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->old_price=htmlspecialchars(strip_tags($this->old_price));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->domain=htmlspecialchars(strip_tags($this->domain));
       
       
     
        // bind values
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":update_message", $this->update_message);
        $stmt->bindParam(":service_details", $this->service_details);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":old_price", $this->old_price);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":domain", $this->domain);
        $stmt->bindParam(":lasttime", $this->lasttime);
      
     
        // execute query
        if($stmt->execute()){
            return true;
        }else{
			 print_r($stmt->errorInfo());
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
                    service_details = :service_details,
                    price=:price,
                    old_price=:old_price,
                    status=:status,
					updated_at=:updated_at,
                    domain=:domain,
                    lasttime=:lasttime
                WHERE
                    service_id = :service_id ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $this->domain = !empty($this->domain) ? $this->domain : 'main';
        // sanitize
        $this->service_id=htmlspecialchars(strip_tags($this->service_id));
        $this->update_message=htmlspecialchars(strip_tags($this->update_message));
        $this->service_details=htmlspecialchars(strip_tags($this->service_details));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->old_price=htmlspecialchars(strip_tags($this->old_price));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->domain=htmlspecialchars(strip_tags($this->domain));
        //$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
       
     
        // bind values
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":update_message", $this->update_message);
        $stmt->bindParam(":service_details", $this->service_details);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":old_price", $this->old_price);
        $stmt->bindParam(":status", $this->status);
        $time = date("Y-m-d h:i:s");
        if($this->status == 1){
            //$time = date("Y-m-d h:i:s",strtotime("+2 minutes"));
            //$time = date('Y-m-d h:i:s', strtotime($time. ' +2 minutes'));
        }
        $stmt->bindParam(":updated_at",$time);
        $stmt->bindParam(":domain", $this->domain);
		$stmt->bindParam(":lasttime",time());
        // execute the query
        if($stmt->execute()){            
            return true;
        }
     
        return false;
    }

    function updateAll($ids){
     
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    update_message = :update_message,                    
                    status=:status,
                    updated_at=:updated_at,
					lasttime=:lasttime
                WHERE
                    service_id NOT IN (".$ids.") AND status=1
                ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        //$this->service_id=htmlspecialchars(strip_tags($this->service_id));
        $this->update_message=htmlspecialchars(strip_tags($this->update_message));
        
        $this->status=htmlspecialchars(strip_tags($this->status));       
        
        // bind values
        //$stmt->bindParam(":service_id", $this->service_id);
        //$stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":update_message", $this->update_message);
        //$stmt->bindParam(":service_details", $this->service_details);
        //$stmt->bindParam(":price", $this->price);
        //$stmt->bindParam(":old_price", $this->old_price);
        $stmt->bindParam(":status", $this->status);
        $time = date("Y-m-d h:i:s");
        //$time = date('Y-m-d h:i:s', strtotime($time. ' +2 minutes'));
        $stmt->bindParam(":updated_at",$time);
        $stmt->bindParam(":lasttime",time());
        // execute the query
        if($stmt->execute()){            
            return true;
        }
     
        return false;
    }
}

?>


