<?php 
/*
 * 
 *
 * logout.php
 *
 * @author: Jamal
 * @version: 1.0 - created
*/

session_start();
session_destroy();
// unset($_COOKIE['PHPSESSID']);
// unset($_COOKIE['session_id']);
// setcookie('PHPSESSID', null, -1, '/');
// setcookie('session_id', null, -1, '/');
header('location: login.php');

?>