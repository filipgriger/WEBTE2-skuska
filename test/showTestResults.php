<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student' || !isset($_SESSION['submissionId'])){
    header("Location: ../index.php");
    exit();
}

include_once '../SubmissionController.php';
$controller = new SubmissionController();
$submissionId = $_SESSION['submissionId'];
$results = $controller->getSubmissionResults($submissionId);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Výsledky</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/showResults.css">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
            crossorigin="anonymous"></script>

            <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3.0.1/es5/tex-mml-chtml.js"></script>
    <style>
        p {
            margin-top: 1rem !important;
        }

        .table td, .table th {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="row">
        <div class="col-10 text-left">
            <div class="h1 my-5">Tvoj výsledok:<span class="pl-4"><?=$results['total']?>b</span></div>
        </div>
        <div class="col-2">
            <a href="../logout.php" class="btn btn-dark px-5 my-5">Ukončiť</a>
        </div>
    </div>


    <hr class="border">

    <?php foreach ($results['simple'] as $simpleQuestion):
        $background = '';
        if($simpleQuestion['points'] == $simpleQuestion['max_points']) {
            $background = 'style="background: #28a74580"';
        } else if($simpleQuestion['points'] == 0) {
            $background = 'style="background: #dc354540"';
        } else {
            $background = 'style="background: #28a74520"';
        }
        ?>

        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="3" class="pl-5"><span class="pr-2">Otázka:</span><?=$simpleQuestion['question']?></th>
            </tr>
            <tr>
                <th>Tvoja odpoveď</th>
                <th>Správna odpoveď</th>
                <th>Body</th>
            </tr>
            <tr <?php echo $background;?>>
                <td class="w-33"><?=$simpleQuestion['user_answer']?></td>
                <td class="w-33"><?=$simpleQuestion['correct_answer']?></td>
                <td class="w-33"><?=$simpleQuestion['points']?> / <?=$simpleQuestion['max_points']?></td>
            </tr>
        </table>
        <hr class="border">
    <?php endforeach; ?>

    <?php foreach ($results['option'] as $optionQuestion):
        $background = '';
        if($optionQuestion['points'] == $optionQuestion['max_points']) {
            $background = 'style="background: #28a74580"';
        } else if($optionQuestion['points'] == 0) {
            $background = 'style="background: #dc354540"';
        } else {
            $background = 'style="background: #28a74520"';
        }
        ?>

        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="3" class="pl-5"><span class="pr-2">Otázka:</span><?=$optionQuestion['question']?></th>
            </tr>
            <tr>
                <th class="w-33">Tvoja odpoveď</th>
                <th class="w-33">Správna odpoveď</th>
                <th class="w-33">Body</th>
            </tr>
            <tr <?php echo $background;?>>
                <td><?=$optionQuestion['user_answer']?></td>
                <td><?=$optionQuestion['correct_answer']?></td>
                <td><?=$optionQuestion['points']?> / <?=$optionQuestion['max_points']?></td>
            </tr>
        </table>
        <hr class="border">
    <?php endforeach; ?>

    <?php foreach ($results['pair'] as $pairQuestion):?>

        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="4" class="pl-5"><span class="pr-2">Otázka:</span><?=$pairQuestion['question']?></th>
            </tr>
            <tr >
                <th class="w-25">Otázka</th>
                <th class="w-25">Tvoja odpoveď</th>
                <th class="w-25">Správna odpoveď</th>
                <th class="w-25">Body</th>
            </tr>
            <?php foreach (json_decode($pairQuestion['pairs'], true) as $pair):
                $background = '';
                if($pair['points'] == $pair['max_points']) {
                    $background = 'style="background: #28a74580"';
                } else if($pair['points'] == 0) {
                    $background = 'style="background: #dc354540"';
                } else {
                    $background = 'style="background: #28a74520"';
                }
                ?>
                <tr <?php echo $background;?>>
                    <td><?=$pair['left']?></td>
                    <td><?=$pair['user_answer']?></td>
                    <td><?=$pair['correct_answer']?></td>
                    <td><?=$pair['points']?> / <?=$pair['max_points']?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <hr class="border">
    <?php endforeach; ?>

    <?php foreach ($results['image'] as $imageQuestion):
        $text = '';
        $background = '';
        if(!$imageQuestion['points']) {
            $text = '<span style="color: #dc3545;">(Učiteľ zatiaľ nevyhodnotil otázku)</span>';
            $background = 'style="background: #FFFF0040"';
        } else if($imageQuestion['points'] == $imageQuestion['max_points']) {
            $background = 'style="background: #28a74580"';
        } else if($imageQuestion['points'] == 0) {
            $background = 'style="background: #dc354540"';
        } else {
            $background = 'style="background: #28a74520"';
        }
        ?>

        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="3" class="pl-5"><span class="pr-2">Otázka:</span><?=$imageQuestion['question']?> <?php echo $text;?></th>
            </tr>
            <tr >
                <th>Tvoja odpoveď</th>
                <th>Body</th>
            </tr>
            <tr <?php echo $background;?>>
                <td class="w-33"><button type="button" class="btn btn-success" onclick="toggleModal('<?=$imageQuestion['image_url']?>')">Obrázok</button></td>
                <td class="w-33"><?=$imageQuestion['points'] ?: '-'?> / <?=$imageQuestion['max_points']?></td>
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

        <script>
            function UrlExists(url)  {
                var http = new XMLHttpRequest();
                http.open('HEAD', url, false);
                http.send();
                return http.status !== 404;
            }

            function toggleModal(url) {
                $("#modal").modal('show');
                //$("#modalLabel").html("Obrázok");

                if(UrlExists("../"+url)) {
                    $(".modal-body").html("<img src='../" + url + "' alt='Obrázok'>");
                } else {
                    $(".modal-body").html("Obrázok nebol dodaný.");
                }
            }
        </script>
        <hr class="border">
    <?php endforeach; ?>

    <?php foreach ($results['expression'] as $expressionQuestion):
        $text = '';
        $background = '';
        if(!$expressionQuestion['points']) {
            $text = '<span style="color: #dc3545;">(Učiteľ zatiaľ nevyhodnotil otázku)</span>';
            $background = 'style="background: #FFFF0040"';
        } else if($expressionQuestion['points'] == $expressionQuestion['max_points']) {
            $background = 'style="background: #28a74580"';
        } else if($expressionQuestion['points'] == 0) {
            $background = 'style="background: #dc354540"';
        } else {
            $background = 'style="background: #28a74520"';
        }
        ?>
        
        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="3" class="pl-5"><span class="pr-2">Otázka:</span><?=$expressionQuestion['question']?> <?php echo $text;?></th>
            </tr>
            <tr >
                <th>Tvoja odpoveď</th>
                <th>Body</th>
            </tr>
            <tr <?php echo $background;?>>
                <td class="w-33"><?=$expressionQuestion['expression']; ?></td>
                <td class="w-33"><?=$expressionQuestion['points'] ?: '-'?> / <?=$expressionQuestion['max_points']?></td>
            </tr>
        </table>

        <hr class="border">
    <?php endforeach; ?>

</div>

</body>
</html>
