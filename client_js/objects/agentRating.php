<?php 

class Agent{
 
    // database connection and table name
    private $conn;
    // object properties
    public $start_date;
    public $end_date;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function getAgentRating(){
     
        // select all query
       
        $query = "select agents.name as AgentName,  avg(rating) as AVGRating , agent_ratings.edited_at as ratingDate
                from agent_ratings
                INNER JOIN agents ON agents.id = agent_ratings.agent_id 
                WHERE agent_ratings.edited_at BETWEEN :start_date AND :end_date
                group by agent_ratings.agent_id";

        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);
     
        // execute query
        $stmt->execute(); 
       
        $agentsRating = [];
      
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
            extract($row);
        
            $agent_item=array(
                 "AgentName" => $AgentName,
                "AVGRating" => number_format((float)$AVGRating, 2, '.', '')
            );
            
            array_push($agentsRating, $agent_item);
        }
     
        return $agentsRating;
    }
}

?>