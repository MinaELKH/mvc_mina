<?php
namespace App\Model;

use App\config\DataBaseManager;
use Exception, PDO;

class Teacher extends Member
{

    private ?string $approved;
    public function __construct(
        ?DataBaseManager $db,
        ?int $id_user = null,
        ?string $name_full = null,
        ?string $email = null,
        ?string $role = 'teacher',
        ?string $avatar = null,
        int $suspended = 0,
        int $archived = 0,
        ?string $approved = null
    ) {
        parent::__construct($db, $id_user, $name_full, $email, $role, $avatar, $suspended, $archived);
        $this->approved = $approved;
    }
    public function hasStatut(): ?object
    {
        $result = $this->db->selectBy("teachers", ["id_user" => $this->id_user]);
        if (empty($result)) {
            return null;
        }
        $row = $result[0];
        return (object) $row;
    }

    public function getStatut()
    {
        return $this->approved;
    }
    public function approved($statut): bool
    {
        $data = [
            "approved" => $statut
        ];
        $whereColumn = "id_user";
        $whereValue = $this->id_user;
        // j ai ajouté un table teacher qui recois la modification de l approuvation 
        return $this->db->update("teachers", $data, $whereColumn, $whereValue);
    }

    public function getAll(): array
    {
        $results = $this->db->selectAll("viewteacher");
        $teachers = [];
        if ($results) {

            foreach ($results as $result) {
                // `u`.`id_user` AS `id_user`,`u`.`email` AS `email`,`u`.`password` AS `password`,`u`.`name_full` AS `name_full`,`u`.`avatar` AS `avatar`,`u`.`id_role` AS `id_role`,`u`.`created_at` AS `created_at`,`u`.`archived` AS `archived`,`u`.`suspended` AS `suspended`,`t`.`approved` AS `approved`,`t`.`message` AS `message`
                $teachers[] = new teacher(
                        null,
                        $result->id_user,
                        $result->name_full,
                        $result->email,
                        'teacher',
                        $result->avatar,
                        $result->suspended,
                        $result->archived,
                        $result->approved
                    );
            }
        }
        return $teachers;
    }


    public function getAll_Pending(): array
    {
        $params = [
            "approved" => 'pending',
            "archived" => 0
        ];
        $teachers = [];
        $results = $this->db->selectBy("viewteacher", $params);
        if ($results) {
            foreach ($results as $result) {
                // `u`.`id_user` AS `id_user`,`u`.`email` AS `email`,`u`.`password` AS `password`,`u`.`name_full` AS `name_full`,`u`.`avatar` AS `avatar`,`u`.`id_role` AS `id_role`,`u`.`created_at` AS `created_at`,`u`.`archived` AS `archived`,`u`.`suspended` AS `suspended`,`t`.`approved` AS `approved`,`t`.`message` AS `message`
                $teachers[] = new teacher(
                        null,
                        $result->id_user,
                        $result->name_full,
                        $result->email,
                        'teacher',
                        $result->avatar,
                        $result->suspended,
                        $result->archived,
                        $result->approved
                    );
            }
        }

        return $teachers;
    }

//    public function getMyCourses(): array
//    {
//        return $this->db->selectBy("viewcourses", ["id_teacher" => $this->id_user, 'archived' => 0]);
//    }

    public function getCountCoursesByTeacher(): int
    {
        $query = "SELECT COUNT(*) as count_course FROM courses WHERE id_teacher = :id_teacher";
        $dataBase = $this->db;
        $cnx = $dataBase->getConnection();
        $stmt = $cnx->prepare($query);
        $stmt->bindValue(':id_teacher', $this->id_user, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        // Vérification de la clé et conversion en entier
        return isset($result['count_course']) ? (int) $result['count_course'] : 0;
    }

    /* nb total des inscrit  */
    public function getCountInscritByTeacher(): int
    {
        $query = "SELECT COUNT(*)  as inscrits FROM enrollments e INNER JOIN courses c  ON   e.id_course = c.id_course 
          WHERE id_teacher = :id_teacher";
        $dataBase = $this->db;
        $cnx = $dataBase->getConnection();
        $stmt = $cnx->prepare($query);
        $stmt->bindValue(':id_teacher', $this->id_user, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        // Vérification de la clé et conversion en entier
        return isset($result['inscrits']) ? (int) $result['inscrits'] : 0;
    }

        /* CA  */
        public function getCAByTeacher(): int
        {
            $query = "SELECT   sum(prix) as CA FROM courses c INNER JOIN enrollments e ON e.id_course = c.id_course WHERE id_teacher = :id_teacher";
            $dataBase = $this->db;
            $cnx = $dataBase->getConnection();
            $stmt = $cnx->prepare($query);
            $stmt->bindValue(':id_teacher', $this->id_user, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
    
        
            return isset($result['CA']) ? (int) $result['CA'] : 0;
        }

             /* les etudiants fidele   */
             public function getTopStudentbyTeacher(): string
             {
                 $query = "SELECT u.name_full AS nom, COUNT(e.id_student) AS nb
                           FROM courses c
                           INNER JOIN enrollments e ON e.id_course = c.id_course
                           INNER JOIN users u ON u.id_user = e.id_student
                           WHERE c.id_teacher = :id_teacher
                           GROUP BY u.name_full
                           ORDER BY nb DESC
                           LIMIT 1";
                 
                 $dataBase = $this->db;
                 $cnx = $dataBase->getConnection();
                 $stmt = $cnx->prepare($query);
                 $stmt->bindValue(':id_teacher', $this->id_user, PDO::PARAM_INT);
                 $stmt->execute();
                 $result = $stmt->fetch();
             
                 if ($result && isset($result['nom'], $result['nb'])) {
                     return $result['nom'] . ' avec ' . $result['nb'] . ' inscriptions';
                 }
                 return "Aucun étudiant trouvé.";
             }
             
}
