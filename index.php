<?php
    session_start();
    require_once "connection.php";

    $errorName = "";
    $errorAge = "";
    $errorAvatar = "";
    $filename = "placeholder.jpg";
    if (isset($_POST["submit"])) {
        $fullname = $_POST["fullname"];
        $age = $_POST["age"];
        $avatar = $_FILES["avatar"];

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
            $sql_insert = "INSERT into students(name, age, avatar, description)
                values ('$fullname', '$age', '$filename', " . "'".$_POST["description"]. "'".")";
            

            $is_insert = mysqli_query($connection, $sql_insert);
            if ($is_insert) {
                $_SESSION["success"] = "Student is added successfully";
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
    <title>Them moi sinh vien</title>
</head>
<body>

    <div class="container">
    <form action="" method="post" enctype = "multipart/form-data">
        <h1>Create Student</h1>
        <div class="input__field input__field--text">
            <input type="text" name="fullname" id="" required autocomplete="off">
            <span>Fullname</span>
            <i></i>
            <p class="error"><?php echo $errorName?></p>
        </div>
        <div class="input__field input__field--text">
            <input type="text" name="age" id="" required autocomplete="off">
            <span>Age</span>
            <i></i>
            <p class="error"><?php echo $errorAge?></p>
        </div>
        <div class="input__field">
            <label for="">Avatar</label>
            <input type="file" name="avatar" id="">
            <p class="error"><?php echo $errorAvatar?></p>
        </div>

        <div class="input__field input__field--text textarea__field">
            <textarea name="description" id="" rows="10" required autocomplete="off"></textarea>
            <span>Student's description</span>
            <i></i>
        </div>
        <div class="input__field input__field--text submit__field">
            <input class="btn" type="submit" value="Create" name="submit">
            <input class="btn" type="reset" value="Reset" name = "reset">
            <a href="./admin.php" class="btn">To admin page</a>
        </div>
    </form>
    </div>
</body>
</html>