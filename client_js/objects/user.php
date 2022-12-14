<?php 

class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $followiz_id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $other_name;
    public $other_phone;
    public $other_address;
    public $other_city;
    public $other_province;
    public $other_postal;
    public $other_country;
    public $other_detail;
    public $status;
 
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
                    " . $this->table_name . " 
                WHERE
                    followiz_id = :followiz_id";

     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(":followiz_id", $this->followiz_id);
     
        // execute query
        $stmt->execute();    
     
        return $stmt;
    }
    
    
    // used when filling up the update product form
    function getUserByUserName(){
        
        // query to read single record
        $query = "SELECT * FROM
                    " . $this->table_name . " 
                WHERE
                    username=:username ";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(":username", $this->username);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // set values to object properties
        $this->id = $row['id'];
        $this->username = $row['username'];
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->email = $row['email'];
        $this->followiz_id = $row['followiz_id'];
        $this->other_name = $row['other_name'];
        $this->other_phone = $row['other_phone'];
        $this->other_address = $row['other_address'];
        $this->other_city = $row['other_city'];
        $this->other_province = $row['other_province'];
        $this->other_postal = $row['other_postal'];
        $this->other_country = $row['other_country'];
        $this->other_detail = $row['other_detail'];
        $this->status = $row['status'];
    }


    // used when filling up the update product form
    function readOne(){
        
        // query to read single record
        $query = "SELECT * FROM
                    " . $this->table_name . " 
                WHERE
                    followiz_id=:followiz_id ";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(":followiz_id", $this->followiz_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // set values to object properties
        $this->id = $row['id'];
        $this->username = $row['username'];
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->email = $row['email'];
        $this->followiz_id = $row['followiz_id'];
        $this->other_name = $row['other_name'];
        $this->other_phone = $row['other_phone'];
        $this->other_address = $row['other_address'];
        $this->other_city = $row['other_city'];
        $this->other_province = $row['other_province'];
        $this->other_postal = $row['other_postal'];
        $this->other_country = $row['other_country'];
        $this->other_detail = $row['other_detail'];
        $this->status = $row['status'];
    }

    

    // create product
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    username=:username, first_name=:first_name, last_name=:last_name, email=:email, followiz_id=:followiz_id, other_name=:other_name, other_phone=:other_phone, other_address=:other_address, other_city=:other_city, other_province=:other_province, other_postal=:other_postal, other_country=:other_country, other_detail=:other_detail";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->first_name=htmlspecialchars(strip_tags($this->first_name));
        $this->last_name=htmlspecialchars(strip_tags($this->last_name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->followiz_id=htmlspecialchars(strip_tags($this->followiz_id));
        $this->other_name=htmlspecialchars(strip_tags($this->other_name));
        $this->other_phone=htmlspecialchars(strip_tags($this->other_phone));
        $this->other_address=htmlspecialchars(strip_tags($this->other_address));
        $this->other_city=htmlspecialchars(strip_tags($this->other_city));
        $this->other_province=htmlspecialchars(strip_tags($this->other_province));
        $this->other_postal=htmlspecialchars(strip_tags($this->other_postal));
        $this->other_country=htmlspecialchars(strip_tags($this->other_country));
        $this->other_detail=htmlspecialchars(strip_tags($this->other_detail));

       
     
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":followiz_id", $this->followiz_id);
        $stmt->bindParam(":other_name", $this->other_name);
        $stmt->bindParam(":other_phone", $this->other_phone);
        $stmt->bindParam(":other_address", $this->other_address);
        $stmt->bindParam(":other_city", $this->other_city);
        $stmt->bindParam(":other_province", $this->other_province);
        $stmt->bindParam(":other_postal", $this->other_postal);
        $stmt->bindParam(":other_country", $this->other_country);
        $stmt->bindParam(":other_detail", $this->other_detail);
     
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
                    username = :username,
                    first_name = :first_name,
                    last_name = :last_name,
                    followiz_id = :followiz_id,
                    email = :email, 
                    other_name=:other_name, 
                    other_phone=:other_phone, 
                    other_address=:other_address, 
                    other_city=:other_city, 
                    other_province=:other_province, 
                    other_postal=:other_postal, 
                    other_country=:other_country, 
                    other_detail=:other_detail
                WHERE
                    id = :id ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->first_name=htmlspecialchars(strip_tags($this->first_name));
        $this->last_name=htmlspecialchars(strip_tags($this->last_name));
        $this->followiz_id=htmlspecialchars(strip_tags($this->followiz_id));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->other_name=htmlspecialchars(strip_tags($this->other_name));
        $this->other_phone=htmlspecialchars(strip_tags($this->other_phone));
        $this->other_address=htmlspecialchars(strip_tags($this->other_address));
        $this->other_city=htmlspecialchars(strip_tags($this->other_city));
        $this->other_province=htmlspecialchars(strip_tags($this->other_province));
        $this->other_postal=htmlspecialchars(strip_tags($this->other_postal));
        $this->other_country=htmlspecialchars(strip_tags($this->other_country));
        $this->other_detail=htmlspecialchars(strip_tags($this->other_detail));
        
       
        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":followiz_id", $this->followiz_id);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":other_name", $this->other_name);
        $stmt->bindParam(":other_phone", $this->other_phone);
        $stmt->bindParam(":other_address", $this->other_address);
        $stmt->bindParam(":other_city", $this->other_city);
        $stmt->bindParam(":other_province", $this->other_province);
        $stmt->bindParam(":other_postal", $this->other_postal);
        $stmt->bindParam(":other_country", $this->other_country);
        $stmt->bindParam(":other_detail", $this->other_detail);

        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }


}

?>