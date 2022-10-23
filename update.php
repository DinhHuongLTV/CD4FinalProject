<?php
    session_start();
    require_once "connection.php";
    
    
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        $_SESSION["error"] = "URL không hợp lệ";
        header("Location: ./admin.php");
        exit();
    }
    $id = $_GET["id"];
    $sql_select = "SELECT * from students where id = $id";
    $dataSelected = mysqli_query($connection, $sql_select);
    $dataStudent = mysqli_fetch_assoc($dataSelected);
    if (!isset($dataStudent)) {
        $_SESSION["error"] = "Student does not exists";
        header("Location: ./admin.php");
        exit();
    }
    $errorName = "";
    $errorAge = "";
    $errorAvatar = "";
    $filename = $dataStudent["avatar"];
    if (isset($_POST["submit"])) {
        $fullname = $_POST["fullname"];
        $age = $_POST["age"];
        $avatar = $_FILES["avatar"];
        $description = $_POST["description"];

        if (empty($fullname)) {
            $errorName = "Name is required";
        }
        if (empty($age)) {
            $errorAge = "Age is required";
        } else if (!is_numeric($age)) {
            $errorAge = "Age must be a number";
        }


        if (empty($errorAge) && empty($errorName)) {
            if ($avatar["error"] == 0) {
                $extension = pathinfo($avatar["name"], PATHINFO_EXTENSION);
                $allValidFileNameExtension = ["jpg", "jpeg", "png", "gif"];
                if (!in_array($extension, $allValidFileNameExtension)) {
                    $errorAvatar = "Filename is not in correct format";
                }
            }

            if (empty($errorAvatar)) {
                if ($avatar["error"] == 0 ) {
                    $dir_upload = "avatars";
                    if (!file_exists($dir_upload)) {
                        mkdir($dir_upload);
                    }
                    $filename = time() . $avatar["name"];

                    $is_moved = move_uploaded_file($avatar["tmp_name"], "$dir_upload/$filename");
                    if (!$is_moved) {
                        $errorAvatar = "Img error";
                    }
                }
            }
        }
        if (empty($errorAge) && empty($errorName) && empty($errorAvatar)) {
            $sql_update = "UPDATE students set name = '$fullname', age = $age, avatar = '$filename', description = '$description' where id = $id";
            $is_updated = mysqli_query($connection, $sql_update);
            if ($is_updated) {
                $_SESSION["success"] = "Student ". $id ." is modified successfully";
                header("Location: ./admin.php");
                exit();
            }
        }
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Sua sinh vien</title>
</head>
<body>

    <div class="container">
    <form action="" method="post" enctype = "multipart/form-data">
        <h1>Update Student</h1>
        <div class="form__container--grid">
            <div class="input__field col--second input__field--text">
                <input type="text" name="fullname" id="" value="<?php echo $dataStudent["name"]?>" required>
                <span>Fullname</span>
                <i></i>
                <p class="error"><?php echo $errorName?></p>
            </div>
            <div class="input__field col--second input__field--text">
                <input type="text" name="age" id="" value="<?php echo $dataStudent["age"]?>" required>
                <span>Age</span>
                <i></i>
                <p class="error"><?php echo $errorAge?></p>
            </div>
            <div class="input__field col--first input__field--img">
                <label for="">Avatar</label>
                <img src="./avatars./<?php echo $dataStudent["avatar"]?>" alt="">
                <input type="file" name="avatar" id="" value="<?php echo $dataStudent["avatar"]?>">
                <p class="error"><?php echo $errorAvatar?></p>
            </div>

            <div class="input__field col--second input__field--text textarea__field">
                <textarea name="description" id="" rows="10" required value=""><?php echo $dataStudent["description"]?></textarea>
                <span>Student's description</span>
                <i></i>
            </div>
            <div class="input__field col--second input__field--text submit__field">
                <input class="btn" type="submit" value="Update" name="submit">
                <input class="btn" type="reset" value="Reset" name = "reset">
            </div>
        </div>
    </form>
    </div>
</body>
</html>
