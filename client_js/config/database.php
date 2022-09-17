<?php 
// database connection will be here

class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "followiz_db";
    private $username = "FollowiZ";
    private $password = "@followiz_@2022DBpw!";
    public $conn, $details;
    public $per_page_record = 50;
    public $page, $start_from, $total_pages;
    public $date_range;
    public $number_of_records = 3; // setting default value so, it will show result all time of filter not selected

    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            // $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn = new PDO("mysql:charset=utf8mb4;host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }

    
    
    public function get_all_records(){
        
        $this->details = null;
        if($this->conn){

             // $this->per_page_record = 4;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
        if (isset($_GET["page"])) {    
            $this->page  = $_GET["page"];    
        }    
        else {    
          $this->page=1;    
        }    

        $this->start_from = ($this->page-1) * $this->per_page_record;   

        if(isset($_POST['daterange'])){
            $this->date_range = explode(' - ',$_POST['daterange']);
            //$this->date_range[] = substr($_POST['daterange'],0,10);
            //$this->date_range[] = substr($_POST['daterange'],13,strlen($_POST['daterange']));
            $start_date = $this->date_range[0];
            $end_date = $this->date_range[1]; 
			
            $stm = $this->conn->query("SELECT * FROM invoices WHERE created_at  >= '".$start_date."' and created_at < '".$end_date."' order by id desc");
        // $stm = $this->conn->query("select * from invoices where created_at Like '%$start_date%' ");
            // $stm = $this->conn->query("SELECT * from invoices where
            // (created_at BETWEEN '$start_date'AND '$end_date') ");
        
         // getting number of records        
         $this->number_of_records = $stm->rowCount();
          // return $this->number_of_records;

        }else{
             $stm = $this->conn->query("select * from invoices order by id desc LIMIT $this->start_from, $this->per_page_record ");
        }

            $this->details = $stm->fetchAll(PDO::FETCH_ASSOC);
        }

        return $this->details;
    }
    
    public function get_all_users(){
        
        $this->details = null;
        if($this->conn){

             // $this->per_page_record = 4;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
        if (isset($_GET["page"])) {    
            $this->page  = $_GET["page"];    
        }    
        else {    
          $this->page=1;    
        }    

        $this->start_from = ($this->page-1) * $this->per_page_record;   

            $stm = $this->conn->query("select * from users LIMIT $this->start_from, $this->per_page_record");

            $this->details = $stm->fetchAll(PDO::FETCH_ASSOC);
        }

        return $this->details;
    }

    public function store_details($data){
        // $current_date_time = Date("Y-m-d H:i:s");
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO invoices (user_id, user_name, invoice_name,payment_id)
            VALUES ('".$data["user_id"]."', '".$data["user_name"]."', '".$data["invoice_name"]."','".$data['payment_id']."')";
            // use exec() because no results are returned
            $this->conn->exec($sql);
            return true;
          } catch(PDOException $e) {
            // echo $sql . "<br>" . $e->getMessage();
            return false;
          }

    }
    
    public function update_status($status,$recordId){
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE users SET status='$status' WHERE id='$recordId'";
            $this->conn->exec($sql);
            return true;
          } catch(PDOException $e) {
            return false;
          }

    }

    public function pagination(){

            $dsn = "mysql:host=localhost;dbname=".$this->db_name;
            $user = $this->username;
            $passwd = $this->password;

            $pdo = new PDO($dsn, $user, $passwd);

            $stm = $pdo->query("SELECT COUNT(*) FROM invoices");

            $rows = $stm->fetchAll(PDO::FETCH_NUM);


             $total_records = $rows[0][0];
             $this->total_pages = ceil($total_records / $this->per_page_record);

            return $this->total_pages;

    }
    
    public function users_pagination(){

            $dsn = "mysql:host=localhost;dbname=".$this->db_name;
            $user = $this->username;
            $passwd = $this->password;

            $pdo = new PDO($dsn, $user, $passwd);

            $stm = $pdo->query("SELECT COUNT(*) FROM users");

            $rows = $stm->fetchAll(PDO::FETCH_NUM);


             $total_records = $rows[0][0];
             $this->total_pages = ceil($total_records / $this->per_page_record);

            return $this->total_pages;

    }
    
    public function find_a_record($ticket_id,$user_id){

            $dsn = "mysql:host=localhost;dbname=".$this->db_name;
            $user = $this->username;
            $passwd = $this->password;

            $pdo = new PDO($dsn, $user, $passwd);

            $stm = $pdo->query("SELECT * FROM agent_ratings WHERE user_id = ".$userid." and ticket_id = ".ticket_id." ");

            $this->details = $stm->fetchAll(PDO::FETCH_ASSOC);

            return $this->details;

    }


}

?>