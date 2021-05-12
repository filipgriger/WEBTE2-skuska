<?php
include_once 'config.php';

class SubmissionController
{

    private $connection;

    /**
     * SubmissionController constructor.
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

    public function createSubmission($testId, $studentId){
        $stmt = $this->getConnection()->prepare('insert into submissions (student_id, test_id) value (?, ?)');
        $stmt->bind_param('ii', $studentId, $testId);
        $stmt->execute();
        $stmt->close();

        return $this->getConnection()->insert_id;
    }

    public function evaluateSubmission($submissionId){
        $answers = $this->getSubmissionQuestions($submissionId);
        $totalPoints = 0;
        foreach ($answers as $answer){
            $totalPoints += $this->evaluateAnswer($answer);
        }
        $this->updateSubmissionPoints($submissionId, $totalPoints);
    }

    public function getSubmissionQuestions($submissionId){
        $q = 'SELECT
            answers.id as answer_id,
            questions.id as question_id,
            questions.type
            FROM
                answers
            JOIN submissions ON submission_id = submissions.id
            JOIN questions ON question_id = questions.id
            WHERE
            submissions.id = ?;';

        $stmt = $this->getConnection()->prepare($q);
        $stmt->bind_param('i', $submissionId);
        $stmt->execute();
        $res = $stmt->get_result();
        $questions = [];

        while ($a = $res->fetch_assoc()){
            array_push($questions, $a);
        }
        return $questions;
    }

    public function evaluateAnswer($answer) {
        $q = '';
        $points = 0.0;
        switch ($answer['type']){
            case 'simple':
                $q = 'SELECT 
                            qs.answer AS correct_answer,
                            ans.answer AS user_answer,
                            questions.max_points
                        FROM
                            questions
                        JOIN questions_simple qs ON qs.question_id = questions.id
                        JOIN answers ON answers.question_id = questions.id
                        JOIN answers_simple ans ON ans.answer_id = answers.id
                        WHERE
                            questions.id = ? AND answers.id = ?;';
                break;
            case 'option':
                $q = 'SELECT
                            option_q.id AS correct_answer,
                            option_a.id AS user_answer,
                            questions.max_points
                        FROM
                            questions
                        JOIN questions_option qs ON qs.question_id = questions.id
                        join options option_q on option_q.id = qs.option_id
                        JOIN answers ON answers.question_id = questions.id
                        JOIN answers_option ans ON ans.answer_id = answers.id
                        join options option_a on option_a.id = ans.option_id
                        WHERE
                            questions.id = ? AND answers.id = ?;';
                break;
            case 'pair':
                $q = 'SELECT 
                            option_q.id AS correct_answer,
                            option_a.id AS user_answer,
                            questions.max_points,
                            ans.id as answer_pair_id
                        FROM
                            questions
                        JOIN questions_pair qs ON qs.question_id = questions.id
                        join options option_q on option_q.id = qs.option_id
                        JOIN answers ON answers.question_id = questions.id
                        JOIN answers_pair ans ON ans.answer_id = answers.id and ans.question_pair_id = qs.id
                        join options option_a on option_a.id = ans.option_id
                        WHERE
                            questions.id = ? AND answers.id = ?;';
                break;
            case 'image':
                $q = 'SELECT
                            ans.answer AS user_answer,
                            questions.max_points
                        FROM
                            questions
                        JOIN answers ON answers.question_id = questions.id
                        JOIN answers_image ans ON ans.answer_id = answers.id
                        WHERE
                            questions.id = ? AND answers.id = ?;';
                break;
        }

        $stmt = $this->getConnection()->prepare($q);
        $stmt->bind_param('ii', $answer['question_id'], $answer['answer_id']);
        $stmt->execute();
        $res = $stmt->get_result();
        $answers = [];
        while($a = $res->fetch_assoc()){
            array_push($answers, $a);
        }
        if($answers) {
            $pointFraction = $answers[0]['max_points'] / count($answers);
            foreach ($answers as $toCompare) {
                if ($answer['type'] == 'simple') {
                    if (strcasecmp($toCompare['correct_answer'], $toCompare['user_answer']) == 0) {
                        $points += $pointFraction;
                    }
                } else {
                    if ($toCompare['correct_answer'] == $toCompare['user_answer']) {
                        $points += $pointFraction;
                        if ($answer['type'] == 'pair') {
                            $this->updatePairFractionalPoints($toCompare['answer_pair_id'], $pointFraction);
                        }
                    } else {
                        if ($answer['type'] == 'pair') {
                            $this->updatePairFractionalPoints($toCompare['answer_pair_id'], 0.0);
                        }
                    }
                }
            }

            $this->updatePoints($answer['answer_id'], $points);
        }

        return $points;
    }

    public function updatePoints($answerId, $points){
        $stmt = $this->getConnection()->prepare('update answers set points = ? where id = ?');
        $stmt->bind_param('ii', $points, $answerId);
        $stmt->execute();
        $stmt->close();
    }

    public function updatePairFractionalPoints($answerPairId, $pointFraction){
        $stmt = $this->getConnection()->prepare('update answers_pair set partial_points = ? where id = ?');
        $stmt->bind_param('di', $pointFraction, $answerPairId);
        $stmt->execute();
        $stmt->close();
    }

    public function updateSubmissionPoints($submissionId, $totalPoints){
        $stmt = $this->getConnection()->prepare('update submissions set total_points = ? where id = ?');
        $stmt->bind_param('di', $totalPoints, $submissionId);
        $stmt->execute();
        $stmt->close();
    }

    public function getSubmissionResults($submissionId){

        $qSimple = 'SELECT
                        questions.question,
                        qs.answer AS correct_answer,
                        ans.answer AS user_answer,
                        CONCAT(answers.points, "/", questions.max_points) AS points
                    FROM
                        submissions
                    JOIN tests ON tests.id = submissions.test_id
                    JOIN questions ON questions.test_id = tests.id
                    JOIN questions_simple qs ON qs.question_id = questions.id
                    JOIN answers ON answers.submission_id = submissions.id AND answers.question_id = questions.id
                    JOIN answers_simple ans ON ans.answer_id = answers.id
                    WHERE
                        submissions.id = ?';
        $qOption = 'SELECT
                        questions.question,
                        option_q.value AS correct_answer,
                        option_a.value AS user_answer,
                        CONCAT(answers.points, "/", questions.max_points) AS points
                    FROM
                        submissions
                    JOIN tests ON tests.id = submissions.test_id
                    JOIN questions ON questions.test_id = tests.id
                    JOIN questions_option qs ON qs.question_id = questions.id
                    JOIN options option_q ON option_q.id = qs.option_id
                    JOIN answers ON answers.submission_id = submissions.id AND answers.question_id = questions.id
                    JOIN answers_option ans ON ans.answer_id = answers.id
                    JOIN options option_a ON option_a.id = ans.option_id
                    WHERE
                        submissions.id = ?';
        $qPair = 'SELECT
                    questions.question,
                    CONCAT(\'[\',
                        GROUP_CONCAT(
                            CONCAT(\'{\', 
                                   \'"left":"\', qs.answer,
                                   \'", "user_answer":"\', option_a.value, 
                                   \'", "correct_answer":"\', option_q.value, 
                                   \'", "points":"\', concat(ans.partial_points, "/", round(questions.max_points/pairs,2)), 
                                   \'"}\')),
                           \']\') as pairs
                    FROM
                        submissions
                        join tests on tests.id = submissions.test_id
                        join questions on questions.test_id = tests.id
                        join (select questions.id as question_id, count(questions_pair.id) as pairs from questions join questions_pair on questions_pair.question_id = questions.id GROUP BY questions.id) 									pair_count on pair_count.question_id = questions.id
                        JOIN questions_pair qs ON qs.question_id = questions.id
                        join options option_q on option_q.id = qs.option_id
                        JOIN answers ON answers.submission_id = submissions.id and answers.question_id = questions.id
                        JOIN answers_pair ans ON ans.answer_id = answers.id and ans.question_pair_id = qs.id
                        join options option_a on option_a.id = ans.option_id
                    WHERE
                        submissions.id = ?
                        GROUP BY questions.id';
        $qImage = 'SELECT
                        questions.question,
                        ans.answer AS user_answer,
                        CONCAT(answers.points, "/", questions.max_points) AS points
                    FROM
                        submissions
                    JOIN tests ON tests.id = submissions.test_id
                    JOIN questions ON questions.test_id = tests.id
                    JOIN answers ON answers.submission_id = submissions.id AND answers.question_id = questions.id
                    JOIN answers_image ans ON ans.answer_id = answers.id
                    WHERE
                        submissions.id = ?';

        $questionResults = ['total' => '', 'simple' => array(), 'option' => array(), 'pair' => array(), 'image' => array()];

        $stmt = $this->getConnection()->prepare('select concat(submissions.total_points,"/",tests.total_points) as total_points from submissions join tests on tests.id = submissions.test_id where submissions.id = ?');
        $stmt->bind_param('i', $submissionId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $questionResults['total'] = $res['total_points'];

        $stmt = $this->getConnection()->prepare($qSimple);
        $stmt->bind_param('i', $submissionId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($a = $res->fetch_assoc()){
            array_push($questionResults['simple'], $a);
        }
        $stmt->close();

        $stmt = $this->getConnection()->prepare($qOption);
        $stmt->bind_param('i', $submissionId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($a = $res->fetch_assoc()){
            array_push($questionResults['option'], $a);
        }
        $stmt->close();

        $stmt = $this->getConnection()->prepare($qPair);
        $stmt->bind_param('i', $submissionId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($a = $res->fetch_assoc()){
            array_push($questionResults['pair'], $a);
        }
        $stmt->close();

        $stmt = $this->getConnection()->prepare($qImage);
        $stmt->bind_param('i', $submissionId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($a = $res->fetch_assoc()){
            array_push($questionResults['image'], $a);
        }
        $stmt->close();

        return $questionResults;
    }

    public function submissionExists($testId, $studentId){
        $stmt = $this->getConnection()->prepare('select submissions.id from submissions where test_id = ? and student_id = ?');
        $stmt->bind_param('ii', $testId, $studentId);
        $stmt->execute();
        $submissionId = $stmt->get_result()->fetch_assoc();
        return $submissionId;
    }


}