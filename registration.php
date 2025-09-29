<?php
// Sessão automatica 
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <?php
        // Validação dos campos e coleta dos dados dos campos
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullName"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $rep_password = $_POST["repeat_password"];

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();
            // Validação de cada campo
            if (empty($fullName) or empty($email) or empty($password) or empty($rep_password)) {
                array_push($errors, "All fields are requiered");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "The password must be at least 8 character long");
            }
            if ($password !== $rep_password) {
                array_push($errors, "Password does not match");
            }
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Email already exist!");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else { //Conexão com o banco de Dados

                $sql = "INSERT INTO users (full_name, email, password) VALUES (?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmnt = mysqli_stmt_prepare($stmt, $sql);
                if ($prepareStmnt) {
                    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $password_hash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registred successfully.</div>";
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>
        <form action="registration.php" method="post">

            <div class="form-group">
                <input type="text" class="form-control" name="fullName" placeholder="Full Name">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="E-mail">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
            </div>

            <div class="form-btn">
                <input type="submit" name="submit" class="btn btn-primary" value="Register">
            </div>
        </form>
        <div>
            <p>Already registered <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>

</html>