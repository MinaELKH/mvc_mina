<?php
namespace App\Model;
use App\config\DataBaseManager;
use Exception ;
class student extends Member
{

    
    public function __construct(
        ?DataBaseManager $db ,
        ?int $id_user = null,
        ?string $name_full = null, 
        ?string $email= null ,
        ?string $role = 'student',
        ?string $avatar = null,
        int $suspended = 0,
        int $archived = 0,
      
    ) {
        parent::__construct($db , $id_user, $name_full, $email, $role, $avatar ,$suspended , $archived ); 
    }
    // public function getAll():array
    // {
    //     return $this->db->selectAll("users") ;
    // }
    public function getAll(): array {
        $param=[
            "id_role" => Role::get_IdRole_ByName($this->role) ,
            "archived" => 0
        ] ;
        $results = $this->db->selectBy("users" , $param);
        $students = [];
        if ($results) {
            foreach ($results as $result) {
                                   // `u`.`id_user` AS `id_user`,`u`.`email` AS `email`,`u`.`password` AS `password`,`u`.`name_full` AS `name_full`,`u`.`avatar` AS `avatar`,`u`.`id_role` AS `id_role`,`u`.`created_at` AS `created_at`,`u`.`archived` AS `archived`,`u`.`suspended` AS `suspended`,`t`.`approved` AS `approved`,`t`.`message` AS `message`
                $students[] = new student
                (null,
                 $result->id_user,
                $result->name_full, 
                $result->email,  
                'student' , 
                $result->avatar, 
                $result->suspended , 
                $result->archived
                 );
            }
        }
        return $students ;
    }
     







}
