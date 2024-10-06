<?php
class Users
{
  public $user_name;
  public $user_email;
  public $user_id;
  public $user_password;
  public $project_name;
  public $project_status;
  public $project_description;

  private $conn; 
  private $users_tbl;
  private $project_tbl;


  public function __construct($db){
    $this->conn = $db;
    $this->users_tbl = 'tbl_users';
    $this->project_tbl = 'tbl_project';
  }

  public function create_user(){
    $sql_query = "INSERT INTO {$this->users_tbl} (user_name, user_email, user_password) VALUES ( ?,?,?)";
    
    $stmt = $this->conn->prepare($sql_query);

    //sanitize
    $this->user_name = htmlspecialchars(strip_tags($this->user_name));
    $this->user_email = htmlspecialchars(strip_tags($this->user_email));
    $this->user_password =  htmlspecialchars(strip_tags($this->user_password));

         // Bind parameters
         $stmt->bindValue(1, $this->user_name);
         $stmt->bindValue(2, $this->user_email);
         $stmt->bindValue(3, $this->user_password);
 
         if ($stmt->execute()) {
             return true;
         }
         return false;
     }
 
     public function check_email()
     {
         $sql_query = "SELECT * FROM " . $this->users_tbl . "WHERE email = ? ";
 
         $stmt = $this->conn->prepare($sql_query);
 
         $stmt->bindValue(1, $this->email);
 
         if ($stmt->execute()) {
             $data = $stmt->fetch(PDO::FETCH_ASSOC);
 
             return $data;
         }
 
         return [];
     }


  }








