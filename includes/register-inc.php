<?php
if(isset($_POST['action']) && $_POST['action'] === 'register') {
    require 'database.php';
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmPassword'];

    if(empty($username) || empty($password) || empty($confirmPass)) {
        // header("Location: ../register.php?error=emptyfields&username=".$username);
        echo "empty fields";
        exit();
    }elseif(!preg_match("/^[a-zA-Z0-9]*/", $username)) {
        header("Location: ../register.php?invalidusername&username=".$username);
        exit();
    }elseif($password !== $confirmPass) {
        header("Location: ../register.php?error=passwordsdonotmatch&username=".$username);
        exit();
    }else {
        $sql = "SELECT username FROM users WHERE username = ?";
        $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../register.php?error=sqlerror");
                exit();
            }else {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $rowCount = mysqli_stmt_num_rows($stmt);

                if($rowCount > 0) {
                    header("Location: ../register.php?error=usernametaken"); 
                }else {
                    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../register.php?error=sqlerror");
                        exit();
                    } else {
                        $hashedPass = password_hash($password, PASSWORD_DEFAULT);

                        mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPass);
                        mysqli_stmt_execute($stmt);
                            header("Location: ../register.php?success=registered");
                            exit();
                    }
                }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>