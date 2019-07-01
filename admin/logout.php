<?php session_start();
unset($_SESSION['Uprofile']);
session_destroy();

echo "<script language='javascript'>";
echo " top.window.location = '../index.php'; ";
echo "</script>";
