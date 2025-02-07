SELECT * FROM teachers
SELECT * FROM courses  WHERE id_course =64
SELECT * FROM content  WHERE id_course = 64
SELECT * FROM reviews WHERE id_course = 60
SELECT * FROM  enrollments WHERE id_course = 60
SELECT * FROM coursetags WHERE id_course = 60
SELECT * FROM viewcourses WHERE id_course = 60
SELECT * FROM teachers 
SELECT * FROM users 
SELECT * FROM tags 
SELECT * FROM viewteacher;


DELETE FROM courses WHERE id_course = 71
UPDATE courses SET  type='texte' WHERE id_course = 65 
up
SELECT COUNT(*) AS total
                FROM courses
                WHERE status = 'approved' AND archived = '0'
                
                 select * from viewcourses
            LIMIT 12 OFFSET 5