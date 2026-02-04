<?php
include 'connection.php';
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

   $delete = "DELETE FROM tbl_student WHERE id = $id";
   $result =$conn->query($delete);
   if ($result ){
    header("Location: table.php");
   }
}