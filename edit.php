<?php
require 'conn.php';
$id = $_POST['id'];
$name = $_POST['username'];
$gender = $_POST['gender'];
if (!empty($_FILES['file']['name'])) {

    $file = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $path = 'profile/' . time() . '_' . $file;
    move_uploaded_file($tmp_name, $path);
    $update = "UPDATE tbl_student SET name='$name',gender='$gender',
            profile='$path' WHERE id='$id'";
} else {
    $update = "UPDATE tbl_student SET name='$name',gender='$gender' WHERE id='$id'";
}
$ex = $conn->query($update);