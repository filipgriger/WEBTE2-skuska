<?php
require_once "config.php";
require_once "pdo.php";

$email = $password = "";
$email_err = $password_err = "";

if (isset($_POST['teacherRegister'])) {

    if (empty(trim($_POST["email"]))) {
        $email_err = "Prosím zadaj tvoj e-mail.";
    } else {
        $sql = "SELECT id FROM teachers WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

            $param_email = trim($_POST["email"]);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $email_err = "Užívateľ v databáze už existuje.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Nastala neočakávaná chyba.";
            }

            unset($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Prosím zadaj heslo.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Heslo musí mať aspoň 8 znakov.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Prosím potvrď heslo.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Heslá sa nezhodujú.";
        }
    }


    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        $sql = "INSERT INTO teachers(name,surname,email,password) VALUES (:name,:surname,:email,:password)";

        if ($stmt = $pdo->prepare($sql)) {
            $param_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash
            $param_name = trim($_POST["name"]);
            $param_surname = trim($_POST["surname"]);
            $param_email = trim($_POST["email"]);
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":surname", $param_surname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo '<span style="color:#00ff00;text-align:center;"><b>Úspešne ste sa zaregistrovali!</b></span>';
            } else {
                echo "Nastala neočakávaná chyba.";
            }

            unset($stmt);
        }
    } else {
        echo '<span style="color:#FF0000;text-align:center;"><b>Chyba pri registrácii!</b></span>';
    }

    unset($pdo);
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrácia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="css/loginForm.css" rel="stylesheet">
    <style type="text/css">
        #toggle_pwd, #toggle_cpwd {
            cursor: pointer;
        }

        .field_icon {
            float: right;
            margin-left: -60px;
            margin-top: -33px;
            position: relative;
            z-index: 4;
            width: 50px;
        }
    </style>
</head>
<body class="text-center bg-light">
<div class="cover-container pt-5 d-flex mx-auto flex-column">
    <main role="main" class="m-auto w-25">
        <h1 class="h3 mb-3 fw-normal">Registrácia účtu pre učiteľa</h1>

        <form id="teacherRegister" action="registration.php" method="post" class="mb-1">
            <div class="form-floating">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                       value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['name']) echo $_POST['name']; ?>"
                       required>
                <label for="name">Meno</label>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname"
                       value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['surname']) echo $_POST['surname']; ?>"
                       required>
                <label for="surname">Priezvisko</label>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" id="email"
                       name="email" placeholder="email@example.com"
                       value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['email']) echo $_POST['email']; ?>"
                       required>
                <label for="email">E-mail</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" id="password"
                       name="password" placeholder="password" title="aspoň 8 znakov, číslo, veľké a malé písmeno + znak"
                       required>
                <span id="toggle_pwd" class="fa fa-fw fa-eye field_icon"></span>
                <label for="password">Heslo</label>
            </div>

            <div class="form-floating">
                <input type="password"
                       class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" id="confirm_password" name="confirm_password"
                       placeholder="password" title="aspoň 8 znakov, číslo, veľké a malé písmeno + znak" required>
                <span id="toggle_cpwd" class="fa fa-fw fa-eye field_icon"></span>
                <label for="confirm_password">Potvrdenie hesla</label>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
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
<script type="text/javascript">
    $(function () {
        $("#toggle_pwd").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
            $("#password").attr("type", type);
        });
    });
    $(function () {
        $("#toggle_cpwd").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
            $("#confirm_password").attr("type", type);
        });
    });
</script>
</html>