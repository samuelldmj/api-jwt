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
         $sql_query = "SELECT * FROM {$this->users_tbl} WHERE user_email = ? ";
 
         $stmt = $this->conn->prepare($sql_query);
 
         $stmt->bindValue(1, $this->user_email);
 
         if ($stmt->execute()) {
             $data = $stmt->fetch(PDO::FETCH_ASSOC);
 
             return $data;
         }
 
         return [];
     }

     public function check_login(){
      $sql_query = "SELECT * FROM {$this->users_tbl} WHERE user_email = ? ";
 
      $stmt = $this->conn->prepare($sql_query);

      $stmt->bindValue(1, $this->user_email);

      if ($stmt->execute()) {
          $data = $stmt->fetch(PDO::FETCH_ASSOC);

          return $data;
      }

      return []; 
     }

     //creating projects
     public function create_project(){
        $sql_query = "INSERT INTO {$this->project_tbl}
         (user_id, project_name, project_description, project_status) VALUES (?,?,?,?) ";

         $stmt = $this->conn->prepare($sql_query);
         
         //sanitize
         $this->project_name = htmlspecialchars(string: strip_tags($this->project_name));
         $this->project_description = htmlspecialchars(string: strip_tags($this->project_description));
         $this->project_status = htmlspecialchars(string: strip_tags($this->project_status));

         //bind value
         $stmt->bindValue(2, $this->project_name);
         $stmt->bindValue(3, $this->project_description);
         $stmt->bindValue(4, $this->project_status);
         $stmt->bindValue(1, $this->user_id, PDO::PARAM_INT);

        if( $stmt->execute() ){
            return true;
        }

            return false;
     }

     public function display_all_projects(){
        $sql_query = "SELECT * FROM {$this->project_tbl} ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function display_single_user_project(){
        $sql_query = "SELECT * {$this->project_tbl} WHERE project_name = ? ";   
        $stmt = $this->conn->prepare($sql_query);
    }

    public function get_user_all_projects(){
        $sql_query = "SELECT * FROM {$this->project_tbl} WHERE user_id = ? ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql_query);

        $stmt->bindValue(1, $this->user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

  }



}




