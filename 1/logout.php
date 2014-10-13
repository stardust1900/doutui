<?php
session_start();
unset($_SESSION['db_uid']);
header("Location:connect.php");