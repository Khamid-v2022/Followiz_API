<?php 

class Vote{
 
    // database connection and table name
    private $conn;
    private $table_name = "rating";
 
    // object properties
    public $id;
    public $user_id;
    public $service_id;
    public $vote;
 
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
    function read(){
     
        // select all query
        /*$query = "SELECT
                    id,user_id,service_id, vote
                  FROM
                    " . $this->table_name . " 
                WHERE
                    user_id = :user_id
                ORDER BY
                    service_id ASC";*/

        $query = "SELECT
                    id,user_id,service_id, ROUND(AVG(vote_new),1) AS voteavg
                  FROM
                    " . $this->table_name . " 
                GROUP BY service_id, id
                ORDER BY
                    service_id ASC";

     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        //$stmt->bindParam(":user_id", $this->user_id);
     
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
                    user_id=:user_id, service_id=:service_id, vote_new=:vote";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->service_id=htmlspecialchars(strip_tags($this->service_id));
        $this->vote=htmlspecialchars(strip_tags($this->vote));
       
     
        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":vote", $this->vote);
     
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
                    vote_new = :vote
                WHERE
                    user_id = :user_id AND
                    service_id = :service_id";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->service_id=htmlspecialchars(strip_tags($this->service_id));
        $this->vote=htmlspecialchars(strip_tags($this->vote));
     
        // bind new values
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':vote', $this->vote);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // reset vote of disabled product
    function reset(){      

        if (!empty($this->service_id)) {

            try 
            { 
               // for ($i=0; $i<=count($this->service_id); $i++) {


                    $service_id = $this->service_id;


                    $query = "DELETE FROM " . $this->table_name . " WHERE service_id = '".$service_id."'";  

                    //echo $query; die;                     
     
                    // prepare query statement
                    $stmt = $this->conn->prepare($query);            
               
                    // bind new values
                   // $stmt->bindParam(':service_id', $service_id);
                    // execute the query
                    if($stmt->execute()){
                        return true;
                    }
     

                                

                //}

            } 
            catch(PDOException $ex) 
            { 
                die("Failed to run query: " . $ex->getMessage()); 
            } 

            // $query = "UPDATE
            //         " . $this->table_name . "
            //     SET
            //         vote = '2'
            //     WHERE                   
            //         service_id IN (".$service_ids.")";      
                
            // bind new values                   

            //$service_ids = implode(',', $this->service_id);  

            //$service_ids = '('.$service_ids.')'; 
            
            // execute the query            

        }
        
     
        return true;
    }


}

?>
