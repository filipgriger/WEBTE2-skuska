<?php
session_start();
require_once "config.php";
require_once "pdo.php";
require_once "StudentController.php";
require_once "SubmissionController.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if ($_SESSION["role"] === 'teacher') {
        header("Location: teacher/teacherHome.php");
        exit();
    } else if (isset($_SESSION["studentId"]) && isset($_SESSION["testId"])) {
        $studentControl = new StudentController();
        $student_status = $studentControl->getStudentStatus($_SESSION["studentId"], $_SESSION["testId"]);
        if ($student_status == 0) {
            header("Location: test/viewTest.php");
            exit();
        }
    } else {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}

$username = $password = "";
$examID_err = $name_err = $sur_err = $studentID_err = "";

if (isset($_POST['studentForm'])) {
    if (empty(trim($_POST["examID"]))) {
        $hash_err = "Zadaj kód testu.";
    } else {
        $examID = trim($_POST["examID"]);
    }

    if (empty(trim($_POST["name"]))) {
        $name_err = "Zadaj tvoje meno.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["surname"]))) {
        $sur_err = "Zadaj tvoje priezvisko.";
    } else {
        $surname = trim($_POST["surname"]);
    }

    if (empty(trim($_POST["studentID"]))) {
        $studentID_err = "Zadaj tvoje ID.";
    } else {
        $studentCode = trim($_POST["studentID"]);
    }

    if (empty($name_err) && empty($sur_err) && empty($examID_err) && empty($studentID_err)) {
        $sql = "SELECT * FROM tests WHERE code = :code";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":code", $examID, PDO::PARAM_STR);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $test = $stmt->fetch();
                    $testId = $test['id'];
                    unset($stmt);
                    $studentController = new StudentController();
                    $studentId = $studentController->getOrCreateStudent($name, $surname, $studentCode);

                    $_SESSION["loggedin"] = true;
                    $_SESSION["role"] = 'student';
                    $_SESSION["testCode"] = $examID;
                    $_SESSION["studentId"] = $studentId;
                    $_SESSION["testId"] = $testId;

                    if (!($submission = (new SubmissionController())->getSubmissionByTestAndUser($testId, $studentId)) && $test["active"]) {
                        header("Location: test/viewTest.php");
                        exit();
                    } elseif ($submission) {
                        $_SESSION["submissionId"] = $submission['id'];
                        header("Location: test/showTestResults.php");
                        exit();
                    }
                }
            }
        }
        header('Location: templates/testDoesntExist.php?testCode=' . $examID);
        exit();
    }
    unset($pdo);
}

if (isset($_POST['teacherForm'])) {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Zadaj tvoj e-mail.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Zadaj tvoje heslo.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, email, password FROM teachers WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            $param_email = trim($_POST["email"]);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["role"] = 'teacher';
                            $_SESSION["email"] = $email;
                            $_SESSION["teacherId"] = $id;
                            header("location: teacher/teacherHome.php");
                        } else {
                            $login_err = "Nesprávne meno, heslo";
                        }
                    }
                } else {
                    $login_err = "Nesprávne meno, heslo";
                }
            } else {
                echo "Nastala neočakávaná chyba.";
            }

            unset($stmt);
        }
    }

    unset($pdo);
}
?>
<!doctype html>
<html lang="sk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prihlásenie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="css/loginForm.css" rel="stylesheet">
</head>

<body class="text-center bg-light">
<div class="cover-container pt-5 d-flex mx-auto flex-column">
    <main class="m-auto">
        <h1 class="h3 mb-3 fw-normal">Testy pre žiakov a učiteľov</h1>
        <hr>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="studentButton" href="#">Študent</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="teacherButton" href="#">Učiteľ</a>
            </li>
        </ul>

        <form id="studentForm" name="studentForm" action="index.php" method="post" class="mb-1" style="display: block;">
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($examID_err)) ? 'is-invalid' : ''; ?>"
                       id="examID" name="examID" placeholder="ABC123" required>
                <span class="invalid-feedback"><?php echo $examID_err; ?></span>
                <label for="examID">Kód testu</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" id="name"
                       name="name" placeholder="Ferko" required>
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                <label for="name">Meno</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>"
                       id="surname" name="surname" placeholder="Mrkvička" required>
                <span class="invalid-feedback"><?php echo $surname_err; ?></span>
                <label for="surname">Priezvisko</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($studentID_err)) ? 'is-invalid' : ''; ?>"
                       id="studentID" name="studentID" placeholder="12345" required>
                <span class="invalid-feedback"><?php echo $studentID_err; ?></span>
                <label for="studentID">Identifikačné číslo</label>
            </div>
            <input type="hidden" name="action" value="loginStudent">
            <button onclick="localStorage.clear();" class="w-100 btn btn-lg btn-primary my-3" name="studentForm" type="submit">Prihlásiť sa na test
            </button>

        </form>
        <?php
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>
        <form id="teacherForm" name="teacherForm" action="index.php" method="post" class="mb-1" style="display: none;">
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com">
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="password"
                       required>
                <label for="password">Heslo</label>
            </div>
            <input type="hidden" name="action" value="loginTeacher">
            <button class="w-100 btn btn-lg btn-primary my-3" name="teacherForm" type="submit">Prihlásiť sa</button>
            <p>Ak nemáte účet tak sa<a href="registration.php" class="link-secondary"> registrujte tu</a>.</p>
        </form>

    </main>
</div>



<footer class="navbar fixed-bottom">
<button type="button" class="btn btn-link" onclick="location.href='documentation.html'" >Technická dokumentácia </button>
</footer>


<script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
        crossorigin="anonymous"></script>
<script src="js/loginForm.js"></script>
</body>

</html>