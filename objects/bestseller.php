<?php 

class Bestseller {
 
    // database connection and table name
    private $conn;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create product
    public function create($table_name, $item){
        // query to insert record
        $query = "INSERT INTO
                    " . $table_name . "
                SET
                    id=:id, category_name=:category_name, service_ids=:service_ids, service_count=:service_count, best_ids=:best_ids, best_count=:best_count";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
   
        // bind values
        $stmt->bindParam(":id", $item['id']);
        $stmt->bindParam(":category_name", $item['category_name']);
        $stmt->bindParam(":service_ids", $item['service_ids']);
        $stmt->bindParam(":service_count", $item['service_count']);
        $stmt->bindParam(":best_ids", $item['best_ids']);
        $stmt->bindParam(":best_count", $item['best_count']);

        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
         
    }

    // reset vote of disabled product
    public function delete_all($table_name){ 
        try 
        { 
            $query = "DELETE FROM " . $table_name;  

            // prepare query statement
            $stmt = $this->conn->prepare($query);            

            // execute the query
            if($stmt->execute()){
                return true;
            }
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
   
        return true;
    }
}

?>
