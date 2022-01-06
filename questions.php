<?php
    include('./lib/password.php');
    ini_set('display_errors', 1);
    require_once('./config.php');
    include('./checkcookies.php');

    if ($_GET['category']){
        if ($stmt = $db->prepare("select id, question from Questions where category = ? order by rand() limit 1;")){
            $stmt->bind_param('s', $_GET['category']);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_all(MYSQLI_ASSOC);
        } else {
            die("MySQL failed");
        }
        $question = $data[0];
    }

    if (isset($_POST["questionid"])){
        $qid = $_POST["questionid"];
        $answer = $_POST["answer"];

        $stmt = $db->prepare("select * from Questions where id = ?;");
        $stmt->bind_param("i", $qid);
        if(!$stmt->execute()){
            $message = "<div class='alert alert-info'>Error: could not find previous question</div>"; 
        } else {
            $res = $stmt->get_result();
            $data = $res->fetch_all(MYSQLI_ASSOC);

            if (!isset($data[0])) {
                $message = "<div class='alert alert-info'>Error: could not find previous question</div>"; 
            } else {
                if ($stmt0 = $db->prepare('UPDATE Users SET last_topic = ? WHERE username=?;')) {
                    $stmt0->bind_param('ss', $_GET['category'], $_COOKIE['username']);
                    $stmt0->execute();
                }
                $past = json_decode($_COOKIE['past_questions']);
                array_push($past, array("question" => $data[0]['question'], "answer" => $data[0]["answer"]));
                setcookie('past_questions', json_encode($past), time() + (86400 * 30), '/');
                if ($data[0]["answer"] == $answer) {
                    $gain = $data[0]['points'];
                    $message = "<div class='alert alert-success'><b>$answer</b> was correct! You got <b>$gain</b> points!</div>";
                    if ($stmt2 = $db->prepare('SELECT points FROM Users WHERE username=?;')) {
                        $stmt2->bind_param('s', $_COOKIE['username']);
                        $stmt2->execute();
                        $result = $stmt2->get_result();
                        $row = $result->fetch_assoc();
                        $points = $row['points'];
                    }
                    if ($stmt = $db->prepare('UPDATE Users SET points = ? WHERE username=?;')) {
                        $total = $points + (int)$data[0]["points"];
	                    $stmt->bind_param('is', $total, $_COOKIE['username']);
                        $stmt->execute();
                    }
                } else {
                    $message = "<div class='alert alert-danger'><b>$answer</b> was Incorrect! The answer was: <b>{$data[0]['answer']}</b></div>";
                }
            }
        }
    } else {
        $message = "<div class='alert alert-info'>Please enter the correct answer to earn points!</div>";
        setcookie('past_questions', '[]', time() + (86400 * 30), '/');
        $past = json_decode($_COOKIE['past_questions']);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/home.css">
        <link rel="stylesheet" href="styles/game.css">
        <meta name="author" content="Min Woo Kim, Ryan Pope">
        <meta name="description" content="Trivia Game">

        <title><?=$_GET['category']?> Trivia Game</title>

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

        <div class="container" style="margin-top: 15px;">
            <div class="split-container">
                <div class="split-half">
                    <div class="row col-xs-8">
                        <h1>CS4640 <?=$_GET['category']?> Trivia Game</h1>
                    </div>
                    <div class="row col-xs-4">
                        <h4>Playing as <?=$_COOKIE['username']?></h4>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 mx-auto">
                            <form action="questions.php?category=<?=$_GET['category']?>" method="post">
                                <div class="h-100 p-5 bg-light border rounded-3">
                                    <h2>Question</h2>
                                    <p><?=$question["question"]?></p>
                                    <input type="hidden" name="questionid" value="<?=$question['id']?>"/>
                                </div>
                                <?=$message?>
                                <div class="h-10 p-5 mb-3">
                                    <input type="text" class="form-control" id="answer" name="answer" placeholder="answer">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>  
                </div>
                <div class="split-half">
                    <div class="past-container">
                        <h2>Previous Answer Bank</h2>
                        <?php
                            if(isset($_COOKIE['past_questions'])){
                                foreach ($past as $pi) {
                                    if(isset($pi->answer)){
                                        echo "<div class='past-item'>";
                                        echo "<p>". $pi->answer . "</p>";
                                        echo "</div>";
                                    }else if(isset($data[0]["answer"])){
                                        echo "<div class='past-item'>";
                                        echo "<p>". $data[0]["answer"] . "</p>";
                                        echo "</div>";
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>