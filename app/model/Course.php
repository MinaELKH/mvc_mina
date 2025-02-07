<?php

namespace App\model;

use App\config\DataBaseManager;
use stdClass, PDO , PDOException ;

class Course
{
    private ?DataBaseManager $db;
    private ?int $id_course;
    private ?string $title;
    private ?string $description;
    private ?string $picture;
    private ?int $id_teacher;
    private ?int $id_categorie;
    private ?string $status;
    private ?string $type;
    private ?int $archived;
    private ?float $prix;

    // Constantes pour le statut
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
 

    public function __construct(
        ?DataBaseManager $db,
        ?int $id_course = 0,
        ?string $title = null,
        ?string $description = null,
        ?string $picture = null,
        ?int $id_teacher = null,
        ?int $id_categorie = null,
        ?string $status = self::STATUS_PENDING,
        ?int $archived = 0,
        ?float $prix = null,
        ?string $type = null,
    ) {

        $this->db = $db;
        $this->id_course = $id_course;
        $this->title = $title;
        $this->description = $description;
        $this->picture = $picture;
        $this->id_teacher = $id_teacher;
        $this->id_categorie = $id_categorie;
        $this->status = $status;
        $this->archived = $archived;
        $this->prix = $prix;
        $this->type = $type;
    }
    public function __get($att)
    {
        return $this->$att;
    }
    public function __set($att, $value)
    {
        $this->$att = $value;
    }
    public function add(): bool
    {
        $data = [
            "title" => $this->title,
            "description" => $this->description,
            "picture" => $this->picture,
            "id_teacher" => $this->id_teacher,
            "id_categorie" => $this->id_categorie,
            "status" => self::STATUS_PENDING,
            "prix" => $this->prix,
            "type" => $this->type
        ];
        return $this->db->insert("courses", $data);
    }
    public function delete(): bool
    {
        $cond = "id_course";
        $param = $this->id_course;
        return $this->db->delete("courses", $cond, $param);
    }
    public function update(): bool
    {
        // Préparer les données pour la mise à jour
        $data = [
            "title" => $this->title,
            "description" => $this->description,
            "id_categorie" => $this->id_categorie,
            "prix" => $this->prix,
            "picture" => $this->picture,
            "status" => $this->status,
            "archived" => $this->archived,
            "id_teacher" => $this->id_teacher,
            "type" => $this->type,
        ];

        $whereColumn = "id_course";
        $whereValue = $this->id_course;

        return $this->db->update("courses", $data, $whereColumn, $whereValue);
    }

    public function convert_array_to_objet(array|stdClass $data): void
    {
        if (is_array($data)) {
            $data = (object) $data; // Convertir un tableau en objet
        }
        $this->id_course = $data->id_course;
        $this->title = $data->title;
        $this->description = $data->description;
        $this->picture = $data->picture;
        $this->id_teacher = $data->id_teacher;
        $this->id_categorie = $data->id_categorie;
        $this->status = $data->status;
        $this->archived = $data->archived;
        $this->prix = $data->prix;
        $this->type = $data->type;
    }

    public function getById()
    {
        $result = $this->db->selectBy("courses", ["id_course" => $this->id_course]);
        // var_dump($result);  
        if (!empty($result)) {
            $this->convert_array_to_objet($result[0]); // converti array to objet 
            return $this;  // je return l ojbet rempli 
        } else {
            return false;
        }
    }
    public static function getByCategorie( DataBaseManager $dbManager, $id_categorie) {
        $params = [
            "id_categorie" => $id_categorie,
            "archived" => 0
        ];
        return $dbManager->selectBy("viewcourses", $params);
    


    }

    public function getDetailCourse()
    {
        $result = $this->db->selectBy("viewcourses", ["id_course" => $this->id_course]);
        return (object)$result[0];
    }
    public function archive(): bool
    {
        $data = ["archived" => 1];
        $whereColumn = "id_course";
        $whereValue = $this->id_course;
        return $this->db->update("courses", $data, $whereColumn, $whereValue);
    }
    public function approve(string $status): bool
    {
        if (!in_array($status, [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED])) {
            throw new \InvalidArgumentException("Statut invalide.");
        }
        $data = ["status" => $status];
        $whereColumn = "id_course";
        $whereValue = $this->id_course;
        return $this->db->update("courses", $data, $whereColumn, $whereValue);
    }

    public function getAllPending(): array
    {
        $params = [
            "status" => self::STATUS_PENDING,
            "archived" => 0
        ];
        return $this->db->selectBy("viewcourses", $params);
    }

    public static function getAll(DataBaseManager $db): array
    {
        $params = [
            "status" => self::STATUS_APPROVED,
            "archived" => 0
        ];
        return $db->selectBy("viewcourses", $params);
    }
    public static function getAllCourseAdmin(DataBaseManager $db): array
    {
        $params = [
            "archived" => 0
        ];
        return $db->selectBy("viewcourses", $params);
    }
    // affiche course teacher
    public function getMyCoursesTeacher( $id_teacher): array
    {
        return $this->db->selectBy("viewcourses", ["id_teacher" => $id_teacher]);
    }
    public static function getSearch($db , $MotSearch): ?array
    {
        $query = "SELECT DISTINCT 
                            v.*
                        FROM viewcourses v
                        inner JOIN 
                            courses c ON c.id_course = v.id_course
                        JOIN 
                            users u ON u.id_user = c.id_teacher
                        JOIN 
                            categories ct ON ct.id_categorie = c.id_categorie
                        LEFT JOIN 
                            content cont ON c.id_course = cont.id_course
                        LEFT JOIN 
                            coursetags ctg ON c.id_course = ctg.id_course
                        LEFT JOIN 
                            tags tg ON tg.id_tag = ctg.id_tag
                        WHERE  
                        LOWER(c.status) = 'approved'
                        AND c.archived = 0
                        AND (
                            c.title LIKE :MotSearch OR 
                            c.description LIKE :MotSearch OR 
                            u.name_full LIKE :MotSearch OR 
                            tg.name_tag LIKE :MotSearch OR 
                            ct.name LIKE :MotSearch OR 
                            cont.title LIKE :MotSearch OR 
                            cont.content_text LIKE :MotSearch
                        )
                ";

        $db = $db->getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindValue(":MotSearch", '%' . $MotSearch . '%', PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $stmt->fetchALL(PDO::FETCH_OBJ);
        } else {
            return false;
        }
 
    }



    //pagination *



public static function showInCatalogue(DataBaseManager $db, int $page = 1, int $perPage = 10): array
{
    try {
        // Calculate the offset
        $offset = ($page - 1) * $perPage;

        // Prepare the SQL query with LIMIT and OFFSET
        $db= $db->getConnection(); 
        $stmt = $db->prepare("
            select * from viewcourses
            LIMIT :limit OFFSET :offset
        ");

        // Bind the pagination parameters
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);; // Return the fetched data
    } catch (PDOException $e) {
        error_log("Database error in showInCatalogue: " . $e->getMessage());
        return []; // Return an empty array if there's an error
    }





}

    public static function countCourses(DataBaseManager $db): int
    {
        try {
            $db= $db->getConnection();
            $stmt = $db->prepare("
                SELECT COUNT(*) AS total
                FROM courses
                WHERE status = 'approved' AND archived = '0'
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total']; // Return the total number of courses
        } catch (PDOException $e) {
            error_log("Database error in countCourses: " . $e->getMessage());
            return 0; // Return 0 if there's an error
        }
    }



    //affiche courses de l etudiant
    public function getMyCourses($id_student): array {
        return $this->db->selectBy("viewcourse_Student", ["id_student" => $id_student]);
    }

}
