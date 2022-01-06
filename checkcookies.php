<?php
include('./lib/password.php');
ini_set('display_errors', 1);
require_once('./config.php');

if (isset($_COOKIE['username']) && isset($_COOKIE['password'])){
    if ($stmt = $db->prepare('SELECT id, password FROM Users WHERE username = ?')) {
        $stmt->bind_param('s', $_COOKIE['username']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $password);
            $stmt->fetch();
            if (password_verify($_COOKIE['password'], $password)) {
                setcookie("username", $_COOKIE['username'], time() + (86400 * 30));
                setcookie("password", $password, time() + (86400 * 30));
                header('Location: home.php');
            } 
        } 
        $stmt->close();
    }
} else {
    echo 'Please log in before accessing Trivia Game';
    header('Location: login.html');
}
?>