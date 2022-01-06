<?php
include('./lib/password.php');
ini_set('display_errors', 1);
require_once('./config.php');
include('./checkcookies.php');

if ($stmt = $db->prepare('SELECT name FROM Topics')) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name);
}

if ($stmt2 = $db->prepare('SELECT points, last_topic FROM Users WHERE username=?;')) {
    $stmt2->bind_param('s', $_COOKIE['username']);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $row = $result->fetch_assoc();
    $points = $row['points'];
    $topic = $row['last_topic'];
}

setcookie('past_questions', '[]', time() + (86400 * 30), '/');

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/home.css">
        <meta name="author" content="Min Woo Kim, Ryan Pope">
        <meta name="description" content="Trivia Game">

        <title>Welcome to Trivia Game</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
        <div>
            <nav class="navbar navbar-light bg-light justify-content-between">
                <a class="navbar-brand" href="home.php">Trivia Game</a>
                <form class="form-inline">
                <a class="nav-item nav-link" href="logout.php">Logout</a>
                </form>
            </nav>
        </div>
        <div class="row">
            <div class="col-xs-8 mx-auto">
                <div class="h-100 p-5 bg-light border rounded-3">
                    <h1>Welcome to Trivia Game</h1>
                    <?php
                        echo '<h2>Welcome, '. $_COOKIE['username'] . '.</h2>';
                        echo '<h2>Your Total Points: '. $points . '</h2>';
                        echo '<h2>Your Last Played Category: '. $topic . '</h2>';
                    ?>
                    <h4>Please select a category listed below to play Trivia Game! Questions vary in difficulty.</h2>
                    <h5>Hard = 5, Medium = 3, Easy = 1</h3>
                    <div class="h-10 p-5 mb-3">
                    <?php
                        while ($stmt->fetch()) {
                            echo '<a href="questions.php?category=' . $name . '" class="block">';
                            echo  $name;
                            echo '</a>';
                        }
                    ?>
                    </div>
                </div>
            </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
