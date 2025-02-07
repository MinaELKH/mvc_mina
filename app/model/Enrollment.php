<?php

namespace App\Model;

use App\config\DataBaseManager;
use PDO;

class Enrollment
{
    private DatabaseManager $dbManager;
    private ?int $id_course;
    private ?int $id_student;
    private ?string $archive;

    public function __construct(
        DatabaseManager $dbManager,
        ?int $id_course = null,
        ?int $id_student = null,
        ?string $archive = '0'
    ) {
        $this->dbManager = $dbManager;
        $this->id_course = $id_course;
        $this->id_student = $id_student;
        $this->archive = $archive;
    }

    public function add(): bool
    {
        $data = [
            'id_course' => $this->id_course,
            'id_student' => $this->id_student,
            'archived' => $this->archive,
        ];
        return $this->dbManager->insert('enrollments', $data);
    }


    public function __set($name, $value)
    {
        $name  = $value;
    }


    public function inscrit()
    {
        $param = ['id_course' => $this->id_course, 'id_student' => $this->id_student];

        $result = $this->dbManager->selectBy('enrollments', $param);

        return (!empty($result)) ? true : false;
    }

   
    public function getStudentsByCourse(): array
    {
        $query = "select * from  users u
                 inner join enrollment e on e.id_student = u.id_user
                 where id_course = :id_course ";
        $db = $this->dbManager->getConnection();
        $stmt =  $db->prepare($query);
        $stmt->bindParam(":id_course", $this->id_course, PDO::PARAM_INT);
        //    var_dump($stmt->execute());
        //    die();
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {

            return false;
        }
    }
}
