<?php

require_once 'Database.php';

class Course{

    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function createCourse($name,$durationInWeeks,$seats){
        try{
            $sql = "INSERT INTO courses(course_name,duration_weeks,max_seats) VALUES (?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sii",$name,$durationInWeeks,$seats);
            $stmt->execute();

            return $this->conn->insert_id;

        }catch(Exception $e){
            return ["status" => false , "message" => $e->getMessage()];
        }
    }

    public function assignInstructor($instructor_id,$course_id){
        try{
            $sql = "INSERT INTO course_instructor (instructor_id,course_id) values (?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii",$instructor_id,$course_id);
            $stmt->execute();

            return $this->conn->insert_id;

        }catch(Exception $e){
            return ["status" => false , "message" => $e->getMessage()];
        }
    }

    public function getCourses()
    {
        $sql = "SELECT id, course_name FROM courses";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function courseWithInstructor($page,$limit)
    {
        try{
        $offset = ($page-1) * $limit;
        $sql = "SELECT c.id,c.course_name,c.max_seats,ci.instructor_id,u.name,u.isActive FROM courses c
                INNER JOIN course_instructor ci ON c.id = ci.course_id
                INNER JOIN users u on u.id = ci.instructor_id LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bind_param("ii",$limit,$offset);
        $stmt -> execute();

        $result = $stmt->get_result();

        $data = [];

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return ["status" => true, "data" => $data];
        }catch(Exception $e){
            return ["status" => false , "mesage" => $e->getMessage()];
        }
    }

    public function countCourseWithInstructor()
    {
        try{
        $sql = "SELECT COUNT(*) as total FROM courses c
                INNER JOIN course_instructor ci ON c.id = ci.course_id
                INNER JOIN users u ON u.id = ci.instructor_id";
        $result = $this->conn->query($sql);
        return  ["status" => "true" , "count" => $result->fetch_assoc()['total']];
    }catch(Exception $e){
        return ["status" => false , "message" => $e->getMessage()];
    }
    }

    public function countCourses()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM courses";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function deleteCourseInstructor($course_id, $instructor_id)
    {
        try {
            $sql = "DELETE FROM course_instructor 
                WHERE course_id = ? AND instructor_id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $course_id, $instructor_id);
            $stmt->execute();

            return ["status" => true, "message" => "Instructor removed from course"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}

?>