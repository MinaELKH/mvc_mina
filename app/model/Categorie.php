<?php
namespace App\model;
use App\config\DataBaseManager ;
use App\config\Database ;


class Categorie {
    private  $db;
    private  $id_categorie;
    private $name;
    private  $description;
    private $archived;

public function __construct( $db , $id_categorie=0 , $name=null ,$description=null  , $archived=null)
{
    $this->db=$db ; 
    $this->id_categorie=$id_categorie;
    $this->name = $name ; 
    $this->description = $description ; 
    $this->archived = $archived ;
}

public function __get($att) {
    return $this->$att;
}
public function add(){
    $data = [
        "name"=>$this->name ,
        "description"=>$this->description ,
    ] ; 
  
    return $this->db->insert("categories" , $data) ;
}
public function delete():bool{
    $cond = "id_categorie" ; 
    $param = $this->id_categorie ;
    return $this->db->delete("categories" , $cond , $param) ;
}

public function update():bool{
    $data = [
        "name"=>$this->name , 
        "description"=>$this->description ,
    ] ; 
    $whereColumn = "id_categorie" ;
    $whereValue = $this->id_categorie ;
    return $this->db->update("categories" , $data , $whereColumn , $whereValue) ;
}

public function archived():bool{
    $data = [
        "archived"=>1
    ] ; 
    $whereColumn = "id_categorie" ;
    $whereValue = $this->id_categorie ;
    return $this->db->update("categories" , $data , $whereColumn , $whereValue) ;
}

public static  function getById($dbManager, $id_categorie)
{
    $result = $dbManager->selectBy("categories", ["id_categorie" => $id_categorie]);
    $row = $result[0] ;
    return $row;
}
// public function getAll():array
// {
//     return $this->db->selectAll("categories") ;
// }




public static function getAll(DataBaseManager $db): array {
    $results = $db->selectAll("categories");
    $categories = [];
    if ($results) {
        foreach ($results as $result) {
            $categories[] = new Categorie(null, $result->id_categorie, $result->name, $result->description, $result->archived);
        }
    }

    return $categories;
}

}