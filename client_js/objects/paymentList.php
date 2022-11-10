<?php 

class PaymentList {
 
    // database connection and table name
    private $conn;
    private $table_name = "payment_list";
 
    // object properties
    public $id;
    public $user_id;
    public $payment_id;
    public $payment_date;
    public $amount;
    public $type;
    public $original_amount;
    public $original_currency;
    public $invoice_path;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read 
    function read(){
     
        // select all query
        $query = "SELECT
                    *
                  FROM
                    " . $this->table_name . " 
                WHERE
                    user_id = :user_id";

     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(":user_id", $this->user_id);
     
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
                    user_id = :user_id AND payment_date = :payment_date";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":payment_date", $this->payment_date);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // set values to object properties
        $this->id = $row['id'];
        $this->user_id = $row['user_id'];
        $this->payment_id = $row['payment_id'];
        $this->payment_date = $row['payment_date'];
        $this->amount = $row['amount'];
        $this->type = $row['type'];
        $this->original_amount = $row['original_amount'];
        $this->original_currency = $row['original_currency'];
        $this->invoice_path = $row['invoice_path'];

        if($row){
            return true;
        }
     
        return false;
    }

    // create product
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_id=:user_id, payment_id=:payment_id, payment_date=:payment_date, amount=:amount, type=:type, original_amount=:original_amount, original_currency=:original_currency";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
  
        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":payment_id", $this->payment_id);
        $stmt->bindParam(":payment_date", $this->payment_date);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":original_amount", $this->original_amount);
        $stmt->bindParam(":original_currency", $this->original_currency);
     
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
                    invoice_path = :invoice_path
                WHERE
                    user_id = :user_id AND payment_date = :payment_date ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     

        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":payment_date", $this->payment_date);
        $stmt->bindParam(":invoice_path", $this->invoice_path);
      
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }



}

?>