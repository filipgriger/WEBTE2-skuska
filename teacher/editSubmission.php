<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

include '../SubmissionController.php';
include '../TestController.php';
include '../StudentController.php';

$submissionController = new SubmissionController();
$testController = new TestController();
$studentController = new StudentController();

if (!($submission = $submissionController->getSubmission($_GET['submissionId']))) {
    header('Location teacherHome.php');
    exit();
}

$student = $studentController->getStudent($submission['student_id']);
$test = $testController->getTest($submission['test_id']);
$results = $submissionController->getSubmissionResults($submission['id']);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Úprava hodnotenia</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="../js/editSubmissions.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/showResults.css">

    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3.0.1/es5/tex-mml-chtml.js"></script>
</head>

<body>
    <div class="container py-5">

        <div class="d-flex justify-content-between">
            <div class="h1">Úprava hodnotenia</div>
            <div class="align-self-center">
                <a href="showTestSubmissions.php?testId=<?= $test['id'] ?>" class="btn btn-dark px-5 mx-2">Návrat</a>
                <a href="teacherHome.php" class="btn btn-dark px-5 mx-2">Domov</a>
            </div>
        </div>
        <hr class="border">

        <table class="table text-center">
            <thead>
                <tr>
                    <th class="w-25">Meno</th>
                    <th class="w-25">ID študenta</th>
                    <th class="w-25">Dátum odovzdania</th>
                    <th class="w-25">Body</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $student['name'] . ' ' . $student['surname'] ?></td>
                    <td><?= $student['student_code'] ?></td>
                    <td><?= $submission['created_at'] ?></td>
                    <td><?= ($submission['total_points'] ?: '-') . ' / ' . $test['total_points'] ?></td>
                </tr>
            </tbody>
        </table>
        <hr class="border">

        <form action="../router.php" method="post">
            <?php foreach ($results['simple'] as $simpleQuestion) : ?>

                <table class="table border text-center">
                    <tr class="text-left">
                        <th colspan="4" class="pl-5"><span class="pr-2">Otázka:</span><?= $simpleQuestion['question'] ?></th>
                    </tr>
                    <tr>
                        <th class="w-30">Študentova odpoveď</th>
                        <th class="w-30">Správna odpoveď</th>
                        <th class="w-20">Body</th>
                        <th class="w-20">Akcia</th>
                    </tr>
                    <tr>
                        <td><?= $simpleQuestion['user_answer'] ?></td>
                        <td><?= $simpleQuestion['correct_answer'] ?></td>
                        <td>
                            <div class="row">
                                <input disabled type="number" step="0.1" min="0" max="<?= $simpleQuestion['max_points'] ?>" class="col-8 text-center" aria-label="Answer points" name="modifications[notPair][<?= $simpleQuestion['answer_id'] ?>]" value="<?= $simpleQuestion['points'] ?>">
                                <span class="col-4 font-weight-bold">[<?= $simpleQuestion['max_points'] ?>]</span>
                            </div>
                        </td>
                        <td><button class="btn btn-dark px-5 enable-input">Upraviť</button></td>
                    </tr>
                </table>
                <hr class="border">
            <?php endforeach; ?>

            <?php foreach ($results['option'] as $optionQuestion) : ?>

                <table class="table border text-center">
                    <tr class="text-left">
                        <th colspan="3" class="pl-5"><span class="pr-2">Otázka:</span><?= $optionQuestion['question'] ?></th>
                    </tr>
                    <tr>
                        <th class="w-30">Študentova odpoveď</th>
                        <th class="w-30">Správna odpoveď</th>
                        <th class="w-20">Body</th>
                        <th class="w-20">Akcia</th>
                    </tr>
                    <tr>
                        <td><?= $optionQuestion['user_answer'] ?></td>
                        <td><?= $optionQuestion['correct_answer'] ?></td>
                        <td>
                            <div class="row">
                                <input disabled type="number" step="0.1" min="0" max="<?= $optionQuestion['max_points'] ?>" class="col-8 text-center" aria-label="Answer points" name="modifications[notPair][<?= $optionQuestion['answer_id'] ?>]" value="<?= $optionQuestion['points'] ?>">
                                <span class="col-4 font-weight-bold">[<?= $optionQuestion['max_points'] ?>]</span>
                            </div>
                        </td>
                        <td><button class="btn btn-dark px-5 enable-input">Upraviť</button></td>
                    </tr>
                </table>
                <hr class="border">
            <?php endforeach; ?>

            <?php foreach ($results['pair'] as $pairQuestion) : ?>

                <table class="table border text-center">
                    <tr class="text-left">
                        <th colspan="4" class="pl-5"><span class="pr-2">Otázka:</span><?= $pairQuestion['question'] ?></th>
                    </tr>
                    <tr>
                        <th class="w-20">Otázka</th>
                        <th class="w-20">Študentova odpoveď</th>
                        <th class="w-20">Správna odpoveď</th>
                        <th class="w-20">Body</th>
                        <th class="w-20">Akcia</th>
                    </tr>
                    <?php foreach (json_decode($pairQuestion['pairs'], true) as $pair) : ?>
                        <tr>
                            <td><?= $pair['left'] ?></td>
                            <td><?= $pair['user_answer'] ?></td>
                            <td><?= $pair['correct_answer'] ?></td>
                            <td>
                                <div class="row">
                                    <input disabled type="number" step="0.1" min="0" max="<?= $pair['max_points'] ?>" class="col-8 text-center" aria-label="Answer points" name="modifications[pair][<?= $pair['answer_id'] ?>][<?= $pair['answer_pair_id'] ?>]" value="<?= $pair['points'] ?>">
                                    <span class="col-4 font-weight-bold">[<?= $pair['max_points'] ?>]</span>
                                </div>
                            </td>
                            <td><button class="btn btn-dark px-5 enable-input">Upraviť</button></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <hr class="border">
            <?php endforeach; ?>

            <?php foreach ($results['image'] as $imageQuestion) : ?>
                <!-- TODO: či už učiteľ otázku vyhodnotil, alebo ešte nie, podľa toho zobraziť buď body alebo tento text -->

                <table class="table border text-center">
                    <tr class="text-left">
                        <th colspan="3" class="pl-5"><span class="pr-2">Otázka:</span><?= $imageQuestion['question'] ?> (Učiteľ zatiaľ nevyhodnotil otázku)</th>
                    </tr>
                    <tr>
                        <th class="w-33">Študentova odpoveď</th>
                        <th class="w-20">Body</th>
                        <th class="w-33">Akcia</th>
                    </tr>
                    <tr>
                        <td><button type="button" class="btn btn-success px-5" onclick="toggleModal('<?= $imageQuestion['image_url'] ?>')">Obrázok</button></td>
                        <td>
                            <div class="row">
                                <input class="col-8 text-center<?= ($imageQuestion['points'] ? '" disabled value="' . $imageQuestion['points'] : ' border-danger') ?>" type="number" step="0.1" min="0" max="<?= $imageQuestion['max_points'] ?>" aria-label="Answer points" name="modifications[notPair][<?= $imageQuestion['answer_id'] ?>]">
                                <span class="col-4 font-weight-bold">[<?= $imageQuestion['max_points'] ?>]</span>
                            </div>
                        </td>
                        <!--                    <td>--><? //=$imageQuestion['points']
                                                        ?>
                        <!-- / --><? //=$imageQuestion['max_points']
                                    ?>
                        <!--</td>-->
                        <td>
                            <?php if ($imageQuestion['points']) : ?>
                                <button class="btn btn-dark px-5 enable-input">Upraviť</button>
                            <?php else : ?>
                                <div class="text-danger font-weight-bold">Nutné manuálne vyhodnotenie</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Obrázok</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                 
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border">
            <?php endforeach; ?>

            <?php foreach ($results['expression'] as $expressionQuestion) : ?>

                <table class="table border text-center">
                    <tr class="text-left">
                        <th colspan="4" class="pl-5"><span class="pr-2">Q:</span><?= $expressionQuestion['question'] ?></th>
                    </tr>
                    <tr>
                        <th class="w-30">Tvoja odpoveď</th>
                        <th class="w-20">Body</th>
                        <th class="w-20">Akcia</th>
                    </tr>
                    <tr>
                        <td><?= $expressionQuestion['expression'] ?></td>
                        <td>
                            <div class="row">
                                <!--input disabled type="number" step="0.1" min="0" max="<?= $expressionQuestion['max_points'] ?>" class="col-8 text-center" aria-label="Answer points" name="modifications[notPair][<?= $expressionQuestion['answer_id'] ?>]" value="<?= $expressionQuestion['points'] ?>">
                                <span class="col-4 font-weight-bold">[<?= $expressionQuestion['max_points'] ?>]</span>-->
                                <input class="col-8 text-center<?= ($expressionQuestion['points'] ? '" disabled value="' . $expressionQuestion['points'] : ' border-danger') ?>" type="number" step="0.1" min="0" max="<?= $expressionQuestion['max_points'] ?>" aria-label="Answer points" name="modifications[notPair][<?= $expressionQuestion['answer_id'] ?>]">
                                <span class="col-4 font-weight-bold">[<?= $expressionQuestion['max_points'] ?>]</span>
                            </div>
                        </td>
                        <td>
                            <!--<button class="btn btn-dark px-5 enable-input">Edit</button>-->
                            <?php if ($expressionQuestion['points']) : ?>
                                <button class="btn btn-dark px-5 enable-input">Upraviť</button>
                            <?php else : ?>
                                <div class="text-danger font-weight-bold">Nutné manuálne vyhodnotenie</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <hr class="border">
            <?php endforeach; ?>

            <script>
                function toggleModal(url) {
                    $("#modal").modal('show');
                    $(".modal-body").html("<img src='../" + url + "' alt='Obrázok'>");
                }
            </script>

            <input type="hidden" name="submissionId" value="<?= $submission['id'] ?>">
            <input type="hidden" name="testId" value="<?= $test['id'] ?>">
            <input type="hidden" name="route" value="editSubmission">
            <input type="submit" class="btn btn-dark btn-block" value="Uložiť zmeny">
        </form>
    </div>

</body>

</html>