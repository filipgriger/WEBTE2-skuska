<?php
include_once 'config.php';

class StudentController
{

    private $connection;

    /**
     * StudentController constructor.
     */
    public function __construct()
    {
        $this->connection = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    }

    public function getConnection()
    {
        if (!$this->connection) {
            $this->connection = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
        }
        return $this->connection;
    }

    public function getOrCreateStudent($name, $surname, $studentCode){
        $stmt = $this->getConnection()->prepare('select id from students where student_code = ?');
        $stmt->bind_param('s', $studentCode);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$res){
            $stmt = $this->getConnection()->prepare('insert into students (`name`, surname, student_code) value (?, ?, ?)');
            $stmt->bind_param('sss', $name, $surname, $studentCode);
            $stmt->execute();
            $stmt->close();
            return $this->getConnection()->insert_id;
        }
        return $res['id'];
    }

    public function getStudent($studentId){
        $stmt = $this->getConnection()->prepare('select * from students where id = ?');
        $stmt->bind_param('i', $studentId);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $student;
    }
}