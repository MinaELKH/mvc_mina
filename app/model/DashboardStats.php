<?php

namespace App\Model;

use App\config\DataBaseManager;
use PDO;

class DashboardStats
{
    private ?DataBaseManager $db;
    public function __construct(?DataBaseManager $db)
    {
        $this->db = $db;
    }

    public function getTopTeachers()
    {
        $query = "SELECT name_full, COUNT(id_course) as nbCourse 
                FROM users u
                INNER JOIN courses c ON u.id_user = c.id_teacher 
                GROUP BY name_full
                ORDER BY nbCourse desc
                LIMIT 3";
        $db = $this->db->getConnection();

        return  $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopCategories()
    {
        $query = "SELECT NAME , COUNT(id_course) as nbCourse FROM categories  cat
                INNER JOIN  courses c  ON  c.id_categorie = cat.id_categorie 
                GROUP BY NAME 
                ORDER BY nbCourse desc
                LIMIT 3";
        $db = $this->db->getConnection();
        return $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopCourses()
    {
        $query = "SELECT c.*, COUNT(e.id_student) AS inscrits 
                FROM courses c
                INNER JOIN enrollments e ON e.id_course = c.id_course
                GROUP BY id_course
                HAVING COUNT(e.id_student) > 5";
        $db = $this->db->getConnection();
        return $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopTeachersEnrol()
    {
        $query = "SELECT name_full, count(id_student) as nb 
                FROM users u
                INNER JOIN courses c ON u.id_user = c.id_teacher 
                INNER JOIN enrollments e ON e.id_course = c.id_course 
                GROUP BY name_full 
                ORDER BY nb DESC";
        $db = $this->db->getConnection();
        return $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingCourse_Teacher()
    {
        $db = $this->db->getConnection();
        $pending_courses = $db->query("SELECT COUNT(*) FROM courses WHERE status = 'pending'")->fetchColumn();
        $pending_teachers = $db->query("SELECT COUNT(*) FROM teachers WHERE approved = 'pending'")->fetchColumn();
        return ['courses' => $pending_courses, 'teachers' => $pending_teachers];
    }
}
