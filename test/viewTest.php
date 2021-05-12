<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student' || !isset($_SESSION['testCode'])){
    header("Location: ../index.php");
    exit();
}

include_once '../TestController.php';
$controller = new TestController();
$test = $controller->getTest($_SESSION['testCode']);
$questions = $controller->getTestQuestions($test['id']);

include '../templates/viewTestHead.html';
?>

<h1>Test <?=$test['code']?></h1>

<form action="../router.php" method="post">
    <input type="hidden" name="test-id" value="<?=$test['id']?>">
    <hr class="border">
    <input type="hidden" name="route" value="saveAnswers">

    <?php foreach ($questions as $index => $question){
        switch ($question['type']){
            case 'simple':
                $questionBody = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <div class="form-group">
                    <label class="font-weight-bold" for="question<?=$question['id']?>"><?=$question['question']?><span class="pl-2">[<?=$question['max_points']?>b]</span></label>
                    <input type="text" class="form-control" id="question<?=$question['id']?>" name="answers[simple][<?=$question['id']?>]" placeholder="Answer">
                </div>
                <hr class="border">
            <?php break;
            case 'option':
                $options = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <div class="form-group">
                    <label class="font-weight-bold" for="question<?=$question['id']?>"><?=$question['question']?><span class="pl-2">[<?=$question['max_points']?>b]</span></label>
                    <select id="question<?=$question['id']?>" name="answers[option][<?=$question['id']?>]" class="form-control">
                        <option selected disabled>Choose option</option>
                        <?php foreach ($options as $option): ?>
                          <option value="<?=$option['id']?>"><?=$option['value']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php break;
            case 'pair':
                $pairs = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <div class="form-group">
                    <div class="font-weight-bold mb-1"><?=$question['question']?><span class="pl-2">[<?=$question['max_points']?>b]</span></div>
                    <?php foreach ($pairs['answers'] as $pair): ?>
                        <div class="row py-1">
                            <div class="col-6">
                                <div class="form-control"><?=$pair['answer']?></div>
                            </div>
                            <div class="col-6">
                                <select id="question<?=$question['id']?>-<?=$pair['id']?>" name="answers[pair][<?=$question['id']?>][<?=$pair['id']?>]" class="form-control" aria-label="Select box">
                                    <option selected disabled>Choose corresponding option</option>
                                    <?php foreach ($pairs['options'] as $option): ?>
                                    <option value="<?=$option['id']?>"><?=$option['value']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <hr class="border">
            <?php break;
        }
    } ?>

    <input type="submit" class="btn btn-block btn-primary mt-3" value="Submit">
</form>

<?php include '../templates/viewTestFoot.html'; ?>

