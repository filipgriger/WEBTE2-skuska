<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($_SESSION["role"] === 'teacher'){
        header("Location: teacher/teacherHome.php");
        exit();
    } else {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}


require_once "config.php";
require_once "pdo.php";
require_once "StudentController.php";
require_once "SubmissionController.php";

// Define variables and initialize with empty values
$username = $password = "";
$examID_err = $name_err = $sur_err = $studentID_err = "";

// Processing form data when form is submitted
if(isset($_POST['studentForm'])){
    // Check if username is empty
    if (empty(trim($_POST["examID"]))) {
        $hash_err = "Please enter examID.";
    } else {
        $examID = trim($_POST["examID"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["surname"]))) {
        $sur_err = "Please enter your surname.";
    } else {
        $surname = trim($_POST["surname"]);
    }

    if (empty(trim($_POST["studentID"]))) {
        $studentID_err = "Please enter your studentID.";
    } else {
        $studentCode = trim($_POST["studentID"]);
    }

    // Validate credentials
    if (empty($name_err) && empty($sur_err) && empty($examID_err) && empty($studentID_err)) {
        // Prepare a select statement
        $sql = "SELECT * FROM tests WHERE code = :code";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":code", $examID, PDO::PARAM_STR);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $test = $stmt->fetch();
                    $testId = $test['id'];
                    // Close statement
                    unset($stmt);
                    $studentController = new StudentController();
                    $studentId = $studentController->getOrCreateStudent($name, $surname, $studentCode);

                    $_SESSION["loggedin"] = true;
                    $_SESSION["role"] = 'student';
                    $_SESSION["testCode"] = $examID;
                    $_SESSION["studentId"] = $studentId;

//                    submission doesnt exist yet and test is active
                    if (!($submissionId = (new SubmissionController())->submissionExists($testId, $studentId)) && $test["active"]) {
                        // Redirect user to test page
                        header("Location: test/viewTest.php");
                        exit();
                    } // test was submitted already
                    elseif ($submissionId) {
                        $_SESSION["submissionId"] = $submissionId;
//                        redirect to show results page
                        header("Location: test/showTestResults.php");
                        exit();
                    }
                }
            }
        }
        header('Location: templates/testDoesntExist.php?testCode=' . $examID);
        exit();
    }
    // Close connection
    unset($pdo);
}

// Processing form data when form is submitted
if(isset($_POST['teacherForm'])){
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM teachers WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Check if username exists, if yes then verify password
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
//                            session_start();
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["role"] = 'teacher';
                            $_SESSION["email"] = $email;
                            $_SESSION["teacherId"] = $id;
                            header("location: teacher/teacherHome.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username, password1";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username, password2";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>
<!doctype html>
<html lang="sk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">


    <link href="css/loginForm.css" rel="stylesheet">
</head>

<body class="text-center bg-light">
<div class="cover-container pt-5 d-flex mx-auto flex-column">
    <main role="main" class="m-auto w-25">
        <h1 class="h3 mb-3 fw-normal">Testy pre žiakov a učiteľov</h1>
        <hr>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="studentButton" href="#">Student</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="teacherButton" href="#">Teacher</a>
            </li>
        </ul>

        <form id="studentForm" name="studentForm" action="index.php" method="post" class="mb-1" style="display: block;">
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($examID_err)) ? 'is-invalid' : ''; ?>" id="examID" name="examID" placeholder="ABC123" required>
                <span class="invalid-feedback"><?php echo $examID_err; ?></span>
                <label for="examID">Kód testu</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" id="name" name="name" placeholder="Ferko" required>
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                <label for="name">Meno</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" id="surname" name="surname" placeholder="Mrkvička" required>
                <span class="invalid-feedback"><?php echo $surname_err; ?></span>
                <label for="surname">Priezvisko</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control <?php echo (!empty($studentID_err)) ? 'is-invalid' : ''; ?>" id="studentID" name="studentID" placeholder="12345" required>
                <span class="invalid-feedback"><?php echo $studentID_err; ?></span>
                <label for="studentID">Identifikačné číslo</label>
            </div>
            <input type="hidden" name="action" value="loginStudent">
            <button class="w-100 btn btn-lg btn-primary my-3" name="studentForm" type="submit">Prihlásiť sa na test</button>

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
                <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                <label for="password">Password</label>
            </div>
            <input type="hidden" name="action" value="loginTeacher">
            <button class="w-100 btn btn-lg btn-primary my-3" name="teacherForm" type="submit">Prihlásiť sa</button>
            <p>Ak nemáte účet tak sa<a href="registration.php" class="link-secondary"> registrujte tu</a>.</p>
        </form>

    </main>
</div>


</body>
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
<script src="js/loginForm.js"></script>

</html>