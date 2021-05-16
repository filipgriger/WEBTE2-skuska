<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student' || !isset($_SESSION['testCode'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../TestController.php';
$controller = new TestController();
$test = $controller->getTestByCode($_SESSION['testCode']);
$questions = $controller->getTestQuestions($test['id']);

if (!isset($_SESSION['start_time'])) {
    //Set the current timestamp.
    $_SESSION['start_time'] = time();
    $_SESSION['end_time'] = $_SESSION['start_time'] + ($test['time'] * 60);
    $duration = $_SESSION['end_time'] - $_SESSION['start_time'];
} else {
    $_SESSION['start_time'] = time();
    $duration = $_SESSION['end_time'] - $_SESSION['start_time'];
}

include '../templates/viewTestHead.html';
$controller->createStudentStatus($_SESSION['studentId'], $_SESSION['testCode']);
?>
<div id="exam_timer" data-timer="<?php echo $duration; ?>"></div>
<h1>Test <?= $test['code'] ?></h1>

<form action="../router.php" method="post">
    <input type="hidden" name="test-id" value="<?= $test['id'] ?>">
    <hr class="border">
    <input type="hidden" name="route" value="saveAnswers">

    <?php foreach ($questions as $index => $question) {
        switch ($question['type']) {
            case 'simple':
                $questionBody = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <div class="form-group">
                    <label class="font-weight-bold" for="question<?= $question['id'] ?>"><?= $question['question'] ?>
                        <span class="pl-2">[<?= $question['max_points'] ?>b]</span></label>
                    <input type="text" class="form-control" id="question<?= $question['id'] ?>"
                           name="answers[simple][<?= $question['id'] ?>]" placeholder="Tvoja odpoveď" onkeyup='saveValue(this);'>
                </div>
                <hr class="border">
                <?php break;
            case 'option':
                $options = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <div class="form-group">
                    <label class="font-weight-bold" for="question<?= $question['id'] ?>"><?= $question['question'] ?>
                        <span class="pl-2">[<?= $question['max_points'] ?>b]</span></label>
                    <select id="question<?= $question['id'] ?>" name="answers[option][<?= $question['id'] ?>]"
                            class="form-control" onchange='saveValue(this);'>
                        <option selected disabled>Vyber možnosť</option>
                        <?php foreach ($options as $option) : ?>
                            <option value="<?= $option['id'] ?>"><?= $option['value'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php break;
            case 'pair':
                $pairs = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <div class="form-group">
                    <div class="font-weight-bold mb-1"><?= $question['question'] ?><span
                                class="pl-2">[<?= $question['max_points'] ?>b]</span></div>
                    <?php foreach ($pairs['answers'] as $pair) : ?>
                        <div class="row py-1">
                            <div class="col-6">
                                <div class="form-control"><?= $pair['answer'] ?></div>
                            </div>
                            <div class="col-6">
                                <select id="question<?= $question['id'] ?>-<?= $pair['id'] ?>"
                                        name="answers[pair][<?= $question['id'] ?>][<?= $pair['id'] ?>]"
                                        class="form-control" aria-label="Select box" onchange='saveValue(this);'>
                                    <option selected disabled>Vyber správnu možnosť</option>
                                    <?php foreach ($pairs['options'] as $option) : ?>
                                        <option value="<?= $option['id'] ?>"><?= $option['value'] ?></option>
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
                    <div class="mb-3 font-weight-bold"><?= $question['question'] ?><span
                                class="pl-2">[<?= $question['max_points'] ?>b]</div>
                    <button type="button" class="btn btn-dark px-5" data-toggle="modal"
                            data-target="#question<?= $question['id'] ?>" data-backdrop="static" data-keyboard="false">
                        Kresli
                    </button>

                    <div class="modal fade image-question" id="question<?= $question['id'] ?>" tabindex="-1"
                         aria-labelledby="questionLabel<?= $question['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="questionLabel<?= $question['id'] ?>"><?= $question['question'] ?></h5>
                                </div>
                                <div class="modal-body">
                                    <div id="paint-app<?= $question['id'] ?>"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="delete<?= $question['id'] ?>"
                                            class="btn btn-danger mr-auto">Vymazať obrázok
                                    </button>
                                    <button type="button" id="save<?= $question['id'] ?>" class="btn btn-danger"
                                            data-dismiss="modal">Uložiť
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="question<?= $question['id'] ?>" name="answers[image][<?= $question['id'] ?>]"
                       value="<?= $question['id'] ?>">
                <hr class="border">
                <?php break;
            case 'expression':
                $expressions = $controller->getQuestion($question['id'], $question['type']);
                ?>
                <br>
                <div class="form-group">
                    <label class="font-weight-bold" for="question<?= $question['id'] ?>"><?= $question['question'] ?>
                        <span class="pl-2">[<?= $question['max_points'] ?>b]</span></label>
                    <textarea type="text" class="ckeditor" id="question<?= $question['id'] ?>"
                              name="answers[expression][<?= $question['id'] ?>]"></textarea>
                </div>
                <?php break;
        }
    } ?>

    <input type="submit" class="btn btn-block btn-primary mt-3" value="Odovzdať test">
</form>
<script>

    $(document).ready(function () {

        if (<?php echo $duration; ?>> 3600) {
            $("#exam_timer").TimeCircles({
                time: {
                    Days: {
                        show: false
                    }
                }
            });
        } else {
            $("#exam_timer").TimeCircles({
                time: {
                    Days: {
                        show: false
                    },
                    Hours: {
                        show: false
                    }
                }
            });
        }


        setInterval(function () {
            var remaining_second = $("#exam_timer").TimeCircles().getTime();
            //console.log(remaining_second);
            if (remaining_second < 1) {
                $("form").submit();
            }
        }, 1000);


    });

    var inputs = document.querySelectorAll('input');
    for (i = 0; i < inputs.length; i++) {
        if(inputs[i].id !== "") {
            inputs[i].value = getSavedValue(inputs[i].id);
        }
    }

    var selects = document.querySelectorAll('select');
    for (i = 0; i < selects.length; i++) {
        if(selects[i].id !== "") {
            selects[i].value = getSavedValue(selects[i].id);
        }
    }

    function saveValue(e) {
        var id = e.id;
        var val = e.value;
        localStorage.setItem(id, val);
    }

    function getSavedValue (v) {
        if (!localStorage.getItem(v)) {
            return "";
        }
        return localStorage.getItem(v);
    }
</script>

<?php include '../templates/viewTestFoot.html'; ?>

