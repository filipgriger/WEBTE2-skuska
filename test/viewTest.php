<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student' || !isset($_SESSION['testCode'])){
    header("Location: ../index.php");
    exit();
}

include_once '../TestController.php';
$controller = new TestController();
$test = $controller->getTestByCode($_SESSION['testCode']);
$questions = $controller->getTestQuestions($test['id']);

include '../templates/viewTestHead.html';

$controller->createStudentStatus($_SESSION['studentId'], $_SESSION['testCode']);
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
            case 'image':
                $images = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <div class="form-group">
                    <div class="mb-3 font-weight-bold"><?=$question['question']?><span class="pl-2">[<?=$question['max_points']?>b]</div>
<!--                    <label class="font-weight-bold" for="question--><?//=$question['id']?><!--">--><?//=$question['question']?><!--<span class="pl-2">[--><?//=$question['max_points']?><!--b]</span></label>-->
<!--                    <input type="text" class="form-control" id="question--><?//=$question['id']?><!--" name="answers[simple][--><?//=$question['id']?><!--]" placeholder="Answer">-->
                    <button type="button" class="btn btn-dark px-5" data-toggle="modal" data-target="#question<?=$question['id']?>" data-backdrop="static" data-keyboard="false">
                        Draw
                    </button>

                    <div class="modal fade image-question" id="question<?=$question['id']?>" tabindex="-1" aria-labelledby="questionLabel<?=$question['id']?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="questionLabel<?=$question['id']?>"><?=$question['question']?></h5>
                                    <!--<button type="button" id="close<?=$question['id']?>" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>-->
                                </div>
                                <div class="modal-body">
                                    <div id="paint-app<?=$question['id']?>"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="delete<?=$question['id']?>" class="btn btn-danger mr-auto">Vymazať obrázok</button>
                                    <button type="button" id="save<?=$question['id']?>" class="btn btn-danger" data-dismiss="modal">Uložiť</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="question<?=$question['id']?>" name="answers[image][<?=$question['id']?>]" value="<?=$question['id']?>">
                <hr class="border">
            <?php break;
        }
    } ?>

    <input type="submit" class="btn btn-block btn-primary mt-3" value="Submit">
</form>

<?php include '../templates/viewTestFoot.html'; ?>

