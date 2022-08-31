<?php 

class AgentRatings{
 
    // database connection and table name
    private $conn;
    private $table_name = "agent_ratings";
 
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read($dateRange = false){
     

         $query = "SELECT agent_ratings.agent_id as agent_id, ROUND(AVG(agent_ratings.rating),1) AS agent_ratings , ROUND(AVG(agent_ratings.response_time),1) AS response_time FROM agent_ratings ";

         if($dateRange){
            $query .= " where created_at  between '".$dateRange[0]." 00:00:00' and '".$dateRange[1]." 23:59:00' ";
         }
         $query .= "GROUP BY agent_id   ORDER BY `agent_ratings`.`agent_id` ASC";
        /*$query = "SELECT 
                    agent_ratings.id as id,
                    agents.name as agaent_name,
                    agent_ratings.rating as agent_rating,
                    agent_ratings.response_time as response_time,
                    agent_ratings.created_at as response_date 
                FROM agent_ratings 
                LEFT JOIN agents on agents.id = agent_ratings.agent_id
                ORDER BY agent_ratings.created_at DESC";*/
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();    
     
        return $stmt;
    }

    function getAgents(){
     

        $query = "SELECT * FROM `agents`";

     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();    
     
        return $stmt;
    }

}

?>
