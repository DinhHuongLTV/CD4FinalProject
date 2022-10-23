<?php
    require_once "connection.php";
    session_start();

    $id = $_GET["id"];
    var_dump($id);
    $sql_delete = "DELETE from students where id = $id";
    $is_deleted = mysqli_query($connection, $sql_delete);
    if ($is_deleted == true) {
        $_SESSION["success"] = "Deleted student $id successfully";
    } else {
        $_SESSION["error"] = "Student $id is not deleted yet";
    }
    header("Location: ./admin.php");
    exit();
    
?>