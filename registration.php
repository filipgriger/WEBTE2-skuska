<?php
// Include config file
require_once "config.php";
require_once "pdo.php";

// Define variables and initialize with empty values
$email = $password =  "";
$email_err = $password_err =  "";

// Processing form data when form is submitted
if(isset($_POST['teacherRegister'])){

    // Validate username
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM teachers WHERE email = :email";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $email_err = "This user already exists.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }


    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO teachers(name,surname,email,password) VALUES (:name,:surname,:email,:password)";

        if($stmt = $pdo->prepare($sql)){
            $param_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash
            $param_name = trim($_POST["name"]);
            $param_surname = trim($_POST["surname"]);
            $param_email = trim($_POST["email"]);
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":surname", $param_surname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Set parameters



            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                echo '<span style="color:#00ff00;text-align:center;"><b>Úspešne ste sa zaregistrovali!</b></span>';
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    else{
        echo '<span style="color:#FF0000;text-align:center;"><b>Email sa už používa!</b></span>';
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
    <title>Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <link href="css/loginForm.css" rel="stylesheet">
</head>
<body class="text-center bg-light">
<div class="cover-container pt-5 d-flex mx-auto flex-column">
    <main role="main" class="m-auto w-25">
        <h1 class="h3 mb-3 fw-normal">Registrácia účtu pre učiteľa</h1>

        <form id="teacherRegister" action="registration.php" method="post" class="mb-1">
            <div class="form-floating">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                <label for="name">Meno</label>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required>
                <label for="surname">Priezvisko</label>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" id="email" name="email" placeholder="email@example.com" required>
                <label for="email">E-mail</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" id="password" name="password" placeholder="password" title="aspoň 8 znakov, číslo, veľké a malé písmeno + znak" required>
                <label for="password">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary my-3" name="teacherRegister" type="submit">Registruj sa</button>
            <p>Späť na <a href="index.php" class="link-secondary">prihlásenie</a>.</p>
        </form>

    </main>
</div>


</body>
<script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
        crossorigin="anonymous"></script>
</html>