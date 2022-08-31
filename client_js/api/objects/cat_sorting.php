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
    
    function readOne(){
        
        // query to read single record
        $query = "SELECT * FROM
                    " . $this->table_name . " 
                WHERE
                    category_id=:category_id ";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(":category_id", $this->category_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // set values to object properties
        $this->id = $row['id'];
        $this->category_id = $row['category_id'];
        $this->sort_order = $row['sort_order'];
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
                    category_id=:category_id, sort_order=:sort_order";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->sort_order=htmlspecialchars(strip_tags($this->sort_order));
     
        // bind values
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":sort_order", $this->sort_order);
      
     
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
                    sort_order = :sort_order
                WHERE
                    category_id = :category_id";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->sort_order=htmlspecialchars(strip_tags($this->sort_order));
       
     
        // bind values
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":sort_order", $this->sort_order);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }
}

?>


