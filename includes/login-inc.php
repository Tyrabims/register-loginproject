<?php
if(isset($_POST['action']) && $_POST['action'] === 'login') {
    require 'database.php';
    $username = $_POST['username'];
    $username = $_POST['password'];
    die('die now');

    if(empty($username) || empty($password)) {
        header("Location: ../register.php?error=emptyfields");
        exit();
    }else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../register.php?error=sqlerror");
        exit();

        }else {
           mysqli_stmt_bind_param($stmt, "s", $username);
           mysqli_stmt_execute($stmt);
           $results = mysqli_stmt_get_result($stmt);
           
           if($row = mysqli_fetch_assoc($result)) {
            $passCheck = password_verify($password, $row['password']);
                if($passCheck === false) {
                    header("Location: ../register.php?error=wrongpass");
                    exit();
                }elseif($passCheck === true) {
                    session_start();
                    $_SESSION['sessionId'] = $row['id'];
                    $_SESSION['sessionUser'] = $row['username'];
                    header("Location: ../register.php?success=loggedin");
                    exit();
                }
           }else{
                header("Location: ../register.php?error=nouser");
                exit();
           }
        }
    }

}else {
    header("Location: ../index.php?error=accessforbidden");
        exit();
}

?>