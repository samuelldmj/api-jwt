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












}