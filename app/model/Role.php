<?php
namespace App\Model;
 use App\config\DataBaseManager ;
 use App\config\Database ;
 use PDO  ; 

 class Role {
    private int $id_role ; 
    private string $role ;


    function __construct($id_role , $role){
           $this->id_role = $id_role ;
           $this->role = $role ;
    }

 // Search user by name
    public static function get_IdRole_ByName($role)
    {
        $db = Database::getInstance()->getConnection();
        // var_dump($role);
        // exit ;
        $stmt = $db->prepare("SELECT id_role FROM roles WHERE role LIKE :role");
        $stmt->bindValue(':role', '%' . $role . '%', PDO::PARAM_STR);
        $stmt->execute();
        $result= $stmt->fetch(PDO::FETCH_ASSOC);
        return  $result['id_role'];
    }

    public static function get_NameRole_ById($id_role)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT role FROM roles WHERE id_role = :id_role");
        $stmt->bindValue(':id_role',  $id_role , PDO::PARAM_INT);
        $stmt->execute();
        $result= $stmt->fetch(PDO::FETCH_ASSOC);
        return  $result['role'];
    }




 }