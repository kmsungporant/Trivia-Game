<?php
include('./lib/password.php');
ini_set('display_errors', 1);
require_once('./config.php');

if (!isset($_POST['username'], $_POST['password'])) {
    echo "<script>alert('Please fill both the username and password fields!')</script>";
    include('index.html');
}

if ($stmt = $db->prepare('SELECT id, password FROM Users WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        if (password_verify($_POST['password'], $password)) {
            setcookie("username", $_POST['username'], time() + (86400 * 30));
            setcookie("password", password_hash($_POST['password'], PASSWORD_DEFAULT), time() + (86400 * 30));
            header('Location: home.php');
        } else {
            echo "here";
            echo "<script>alert('Username exists, please choose another!')</script>";
            header('Location: login.html');
        }
    } else {
        if ($stmt2 = $db->prepare('INSERT INTO Users (username, password) VALUES (?, ?)')) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt2->bind_param('ss', $_POST['username'], $password);
            setcookie("username", $_POST['username'], time() + (86400 * 30));
            setcookie("password", $password, time() + (86400 * 30));
            $stmt2->execute();
            header('Location: home.php');
        }
    }
    $stmt->close();
}