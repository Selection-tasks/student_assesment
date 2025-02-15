<?php
session_start();
session_destroy();
header("Location: index.php"); // Change this to your login page if needed
exit();
?>
