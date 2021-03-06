<?php
include_once 'config.php';

class TestController
{

    private $connection;

    /**
     * TestController constructor.
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

    public function createTest($teacherId, $code, $time, $questions)
    {
        if (count($questions) > 0) {

            $stmt = $this->getConnection()->prepare('insert into tests (code, teacher_id,time, total_points) value (?, ?, ?, 0)');
            $stmt->bind_param('sii', $code, $teacherId, $time);
            $stmt->execute();
            $stmt->close();
            $testId = $this->getConnection()->insert_id;

            foreach ($questions as $question) {
                $questionId = $this->createQuestion($testId, $question['question'], $question['type'], $question['points']);
                $this->updateTotalTestPoints($testId, $question['points']);
                $answer = key_exists('answer', $question) ? $question['answer'] : null;
                $options = key_exists('options', $question) ? $question['options'] : null;
                $pairs = key_exists('pairs', $question) ? $question['pairs'] : null;
                $this->createQuestionVariation($question['type'], $questionId, $answer, $options, $pairs);
            }
        }
    }

    private function createQuestionVariation($type, $questionId, $answer, $options, $pairs)
    {
        switch ($type) {
            case 'simple':
                $stmt = $this->getConnection()->prepare('insert into questions_simple (question_id, answer) value (?, ?)');
                $stmt->bind_param('is', $questionId, $answer);
                $stmt->execute();
                $stmt->close();
                break;
            case 'option':
                $stmt = $this->getConnection()->prepare('insert into options (question_id, `value`) value (?,?)');
                $stmt->bind_param('is', $questionId, $answer);
                $stmt->execute();
                $correctOptionId = $this->getConnection()->insert_id;
                foreach ($options as $option) {
                    $stmt->bind_param('is', $questionId, $option);
                    $stmt->execute();
                }
                $stmt = $this->getConnection()->prepare('insert into questions_option (question_id, option_id) value (?, ?)');
                $stmt->bind_param('ii', $questionId, $correctOptionId);
                $stmt->execute();
                $stmt->close();
                break;
            case 'pair':
                foreach ($pairs as $pair) {
                    $stmt = $this->getConnection()->prepare('insert into options (question_id, `value`) value (?,?)');
                    $stmt->bind_param('is', $questionId, $pair['right']);
                    $stmt->execute();
                    $optionId = $this->getConnection()->insert_id;
                    $stmt = $this->getConnection()->prepare('insert into questions_pair (question_id, answer, option_id) value (?,?,?)');
                    $stmt->bind_param('isi', $questionId, $pair['left'], $optionId);
                    $stmt->execute();
                }
                if (isset($stmt)) $stmt->close();
                break;
            case 'image':
                $stmt = $this->getConnection()->prepare('insert into questions_image (question_id) value (?)');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $stmt->close();
                break;
            case 'expression':
                $stmt = $this->getConnection()->prepare('insert into questions_expression (question_id) value (?)');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $stmt->close();
                break;
        }
    }

    public function getAllTests()
    {
        $res = $this->getConnection()->query('select * from tests');
        $tests = array();
        while ($a = $res->fetch_assoc()) {
            array_push($tests, $a);
        }
        return $tests;
    }

    public function getQuestion($questionId, $type)
    {
        switch ($type) {
            case 'simple':
                $stmt = $this->getConnection()->prepare('SELECT questions.* FROM questions join questions_simple on questions_simple.question_id = questions.id WHERE questions.id = ?;');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                return $res->fetch_assoc();
                break;
            case 'option':
                $stmt = $this->getConnection()->prepare('SELECT options.* FROM options join questions on questions.id = options.question_id where questions.id = ? order by RAND();');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                $options = array();
                while ($a = $res->fetch_assoc()) {
                    array_push($options, $a);
                }
                return $options;
                break;
            case 'pair':
                $stmt = $this->getConnection()->prepare('select questions_pair.* from questions_pair join questions on questions.id = questions_pair.question_id where questions.id = ?;');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $res = $stmt->get_result();
                $answers = array();
                while ($a = $res->fetch_assoc()) {
                    array_push($answers, $a);
                }
                $stmt = $this->getConnection()->prepare('SELECT options.* FROM options join questions on questions.id = options.question_id where questions.id = ? order by RAND();');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $res = $stmt->get_result();
                $options = array();
                while ($a = $res->fetch_assoc()) {
                    array_push($options, $a);
                }
                return ['answers' => $answers, 'options' => $options];
            case 'image':
                $stmt = $this->getConnection()->prepare('SELECT questions.* FROM questions join questions_image on questions_image.question_id = questions.id WHERE questions.id = ?;');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                return $res->fetch_assoc();
                break;
            default:
                return null;
            case 'expression':
                $stmt = $this->getConnection()->prepare('SELECT questions.* FROM questions join questions_expression on questions_expression.question_id = questions.id WHERE questions.id = ?;');
                $stmt->bind_param('i', $questionId);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                return $res->fetch_assoc();
                break;
        }
    }

    public function getTestByCode($code)
    {
        $stmt = $this->getConnection()->prepare('select * from tests where code = ?');
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $test = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $test;
    }

    public function getAnswerByStudentAndSub($code1, $code2, $type)
    {
        $sql = "SELECT id from answers where submission_id = $code1 && question_id = $code2";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute();
        $test = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $code = $test['id'];

        switch ($type) {
            case 'simple':
                $stmt = $this->getConnection()->prepare('select answer from answers_simple where answer_id = ?');
                $stmt->bind_param('i', $code);
                $stmt->execute();
                $answer = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $answer = $answer['answer'];
                return $answer;
                break;
            case 'option':
                $stmt = $this->getConnection()->prepare('select option_id from answers_option where answer_id = ?');
                $stmt->bind_param('i', $code);
                $stmt->execute();
                $answer = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $answer = $answer['option_id'];
                return $answer;
                break;
            case 'pair':
                $stmt = $this->getConnection()->prepare('select option_id from answers_pair where answer_id = ?');
                $stmt->bind_param('i', $code);
                $stmt->execute();
                $answer = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $answer = $answer['option_id'];
                return $answer;
                break;
            case 'image':
                $stmt = $this->getConnection()->prepare('select image_url from answers_image where answer_id = ?');
                $stmt->bind_param('i', $code);
                $stmt->execute();
                $answer = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $answer = $answer['image_url'];
                return $answer;
                break;
            case 'expression':
                $stmt = $this->getConnection()->prepare('select MathML_expression from answers_expression where answer_id = ?');
                $stmt->bind_param('i', $code);
                $stmt->execute();
                $answer = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $answer = $answer['MathML_expression'];
                return $answer;
                break;
            default:
                return null;
        }
    }

    public function getTest($testId)
    {
        $stmt = $this->getConnection()->prepare('select * from tests where id = ?');
        $stmt->bind_param('s', $testId);
        $stmt->execute();
        $test = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $test;
    }

    public function getTestQuestions($testId)
    {
        $stmt = $this->getConnection()->prepare('select * from questions where test_id = ?');
        $stmt->bind_param('i', $testId);
        $stmt->execute();
        $res = $stmt->get_result();
        $questions = array();
        while ($a = $res->fetch_assoc()) {
            array_push($questions, $a);
        }
        $stmt->close();
        return $questions;
    }

    private function createQuestion($testId, $question, $type, $points)
    {
        $stmt = $this->getConnection()->prepare('insert into questions (test_id, question, `type`, max_points) value (?, ?, ?, ?)');
        $stmt->bind_param('issi', $testId, $question, $type, $points);
        $stmt->execute();
        $stmt->close();

        return $this->getConnection()->insert_id;
    }

    private function updateTotalTestPoints($testId, $questionPoints)
    {
        $stmt = $this->getConnection()->prepare('update tests set total_points = (total_points + ?) where tests.id = ?');
        $stmt->bind_param('ii', $questionPoints, $testId);
        $stmt->execute();
        $stmt->close();
    }

    public function saveAnswers($studentId, $submissionId, $answers)
    {
        foreach (array_keys($answers) as $type) {
            foreach ($answers[$type] as $questionId => $answer) {
                $answerId = $this->createAnswer($submissionId, $questionId);
                if ($type == 'image') {
                    $answer = '/images/submissions/students/' . $studentId . '/question-' . $questionId . '.png';
                }
                $this->createAnswerVariation($answerId, $type, $answer);
            }
        }
    }

    private function createAnswer($submissionId, $questionId)
    {
        $stmt = $this->getConnection()->prepare('insert into answers (submission_id, question_id) value (?, ?)');
        $stmt->bind_param('ii', $submissionId, $questionId);
        $stmt->execute();
        $stmt->close();

        return $this->getConnection()->insert_id;
    }

    private function createAnswerVariation($answerId, $type, $answer)
    {
        switch ($type) {
            case 'simple':
                $stmt = $this->getConnection()->prepare('insert into answers_simple (answer_id, answer) value (?, ?)');
                $stmt->bind_param('is', $answerId, $answer);
                $stmt->execute();
                $stmt->close();
                break;
            case 'option':
                $stmt = $this->getConnection()->prepare('insert into answers_option (answer_id, option_id) value (?, ?)');
                $optionId = intval($answer);
                $stmt->bind_param('ii', $answerId, $optionId);
                $stmt->execute();
                $stmt->close();
                break;
            case 'pair':
                $stmt = $this->getConnection()->prepare('insert into answers_pair (answer_id, question_pair_id, option_id) value (?, ?, ?)');
                foreach ($answer as $questionPairId => $optionId) {
                    $optionId = intval($optionId);
                    $stmt->bind_param('iii', $answerId, $questionPairId, $optionId);
                    $stmt->execute();
                }
                $stmt->close();
                break;
            case 'image':
                $stmt = $this->getConnection()->prepare('insert into answers_image (answer_id, image_url) value (?, ?)');
                $stmt->bind_param('is', $answerId, $answer);
                $stmt->execute();
                $stmt->close();
                break;
            case 'expression':
                $stmt = $this->getConnection()->prepare('insert into answers_expression (answer_id, MathML_expression) value (?, ?)');
                $stmt->bind_param('is', $answerId, $answer);
                $stmt->execute();
                $stmt->close();
                break;
        }
    }

    public function updateTestStatus($testId, $newStatus)
    {
        $newStatus = intval($newStatus);
        $stmt = $this->getConnection()->prepare('update tests set active = ? where tests.id = ?');
        $stmt->bind_param('ii', $newStatus, $testId);
        $stmt->execute();
        $stmt->close();
        return $newStatus;
    }

    public function createStudentStatus($studentId, $testCode)
    {
        $stmt = $this->getConnection()->prepare('insert ignore into students_status (student_id, test_id, status) value (?, ?, 1)');
        $testId = $this->getTestByCode($testCode);
        $stmt->bind_param('ii', $studentId, $testId["id"]);
        $stmt->execute();
        $stmt->close();
    }

    public function changeStudentStatus($studentId, $testCode, $status)
    {
        $stmt = $this->getConnection()->prepare('update students_status set status = (?) where student_id = ? and test_id = ?');
        $testId = $this->getTestByCode($testCode);
        $stmt->bind_param('iii', $status, $studentId, $testId["id"]);
        $stmt->execute();
        $stmt->close();
    }

    public function studentStatusChanged($testId)
    {
        $q = 'SELECT
                CONCAT(name, " ", surname) AS `name`,
                students.student_code,
                `status`,
                submitted,
                submissions.created_at AS submitted_at,
                submissions.id as submission_id
            FROM
                students_status
            JOIN students ON students.id = students_status.student_id
            JOIN tests ON students_status.test_id = tests.id
            LEFT JOIN submissions ON submissions.student_id = students.id AND submissions.test_id = tests.id
            WHERE
                tests.id = ?';
        $stmt = $this->getConnection()->prepare($q);
        $stmt->bind_param('i', $testId);
        $stmt->execute();

        $res = $stmt->get_result();

        $submitted = array();
        $not_submitted = array();

        while ($a = $res->fetch_assoc()) {
            if ($a["submitted"] == 0) {
                array_push($not_submitted, $a);
            } else {
                array_push($submitted, $a);
            }
        }
        $status = array('not_submitted' => $not_submitted, 'submitted' => $submitted);
        $stmt->close();

        return $status ?? null;
    }

    public function generateRandomHash($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $stmt = $this->getConnection()->prepare("SELECT * from tests WHERE code = ?");
        $stmt->bind_param('s', $randomString);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows >= 1) {
            return $this->generateRandomHash();
        } else {
            return $randomString;
        }
    }
}