<?php
    require_once "connection.php";
    session_start();

    $sql_selectAll = "SELECT * from students order by id desc";
    $selectedData = mysqli_query($connection, $sql_selectAll);
    $dataAll = mysqli_fetch_all($selectedData, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./admin.css">
    <title>Danh Sach Sinh Vien</title>
</head>
<body>
    <div class="message">
        <h1>
            <?php 
            if (isset($_SESSION["success"])) {
                echo $_SESSION["success"];
                unset($_SESSION["success"]);
            }
            ?>
        </h1>

        <h1>
            <?php 
            if (isset($_SESSION["error"])) {
                echo $_SESSION["error"];
                unset($_SESSION["error"]);
            }
            ?>
        </h1>
    </div>
    <h1>Student list</h1>
    <div class="table__container">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Avatar</th>
                    <th>Description</th>
                    <th>Created at</th>
                    <th></th>
                </tr>
                <?php foreach($dataAll as $student):?>
                    <tr>
                        <td><?php echo $student["id"]?></td>
                        <td><?php echo $student["name"]?></td>
                        <td><?php echo $student["age"]?></td>
                        <td>
                            <img src="./avatars./<?php echo $student["avatar"]?>" alt="">
                        </td>
                        <td><?php echo $student["description"]?></td>
                        <td>
                            <?php
                                echo date("d/m/Y H:i:s", strtotime($student['created_at']));
                            ?>
                        </td>
                        <td>
                            <a href="update.php?id=<?php echo $student["id"]?>">Sửa</a>
                            |
                            <a href="delete.php?id=<?php echo $student["id"]?>" onclick="return confirm('Delete?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
    </div>
        <div>
            <a href="index.php" class="btn">Create New Student</a>
        </div>
    </body>
</html>
