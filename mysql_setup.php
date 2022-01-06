<?php
    include('./lib/password.php');
    ini_set('display_errors', 1);
    require_once('./config.php');
    $db->query("drop table if exists Questions;");
    $db->query("create table Questions (
        id int not null auto_increment,
        question text not null,
        answer text not null,
        points int not null,
        category text not null,
        primary key (id));");
        
    $db->query("drop table if exists Users;");
    $db->query("create table Users (
        id int not null auto_increment,
        username text not null,
        password text not null,
        last_topic text not null,
        points int not null,
        primary key (id));");
        
    $db->query("drop table if exists Topics;");
    $db->query("create table Topics (
        id int not null auto_increment,
        name text not null,
        primary key (id));");

    $sportsTrivia = json_decode(file_get_contents("https://opentdb.com/api.php?amount=50&category=21&type=multiple"), true);
    $historyTrivia = json_decode(file_get_contents("https://opentdb.com/api.php?amount=50&category=23&type=multiple"), true);
    $vehiclesTrivia = json_decode(file_get_contents("https://opentdb.com/api.php?amount=50&category=28&type=multiple"), true);
    $geoTrivia = json_decode(file_get_contents("https://opentdb.com/api.php?amount=50&category=22&type=multiple"), true);
    $animalsTrivia = json_decode(file_get_contents("https://opentdb.com/api.php?amount=50&category=27&type=multiple"), true);

    $db->query("insert into Topics (name) values ('Sports');");
    $db->query("insert into Topics (name) values ('History');");
    $db->query("insert into Topics (name) values ('Vehicles');");
    $db->query("insert into Topics (name) values ('Geography');");
    $db->query("insert into Topics (name) values ('Animals');");


    $stmt = $db->prepare("insert into Questions (question, answer, points, category) values (?, ?, ?, ?);");
    foreach($sportsTrivia["results"] as $qn) {
        $points = 0;
        if ($qn["difficulty"] === "hard"){
            $points = 5;
        }
        if ($qn["difficulty"] === "medium"){
            $points = 3;
        }
        if ($qn["difficulty"] === "easy"){
            $points = 1;
        }
        $stmt->bind_param("ssis", $qn["question"], $qn["correct_answer"], $points, $qn["category"]);
        if (!$stmt->execute()){
            echo "Could not add question: {$qn["question"]}\n";
        }
    }
    foreach($historyTrivia["results"] as $qn) {
        $points = 0;
        if ($qn["difficulty"] === "hard"){
            $points = 5;
        }
        if ($qn["difficulty"] === "medium"){
            $points = 3;
        }
        if ($qn["difficulty"] === "easy"){
            $points = 1;
        }
        $stmt->bind_param("ssis", $qn["question"], $qn["correct_answer"], $points, $qn["category"]);
        if (!$stmt->execute()){
            echo "Could not add question: {$qn["question"]}\n";
        }
    }
    foreach($vehiclesTrivia["results"] as $qn) {
        $points = 0;
        if ($qn["difficulty"] === "hard"){
            $points = 5;
        }
        if ($qn["difficulty"] === "medium"){
            $points = 3;
        }
        if ($qn["difficulty"] === "easy"){
            $points = 1;
        }
        $stmt->bind_param("ssis", $qn["question"], $qn["correct_answer"], $points, $qn["category"]);
        if (!$stmt->execute()){
            echo "Could not add question: {$qn["question"]}\n";
        }
    }
    foreach($geoTrivia["results"] as $qn) {
        $points = 0;
        if ($qn["difficulty"] === "hard"){
            $points = 5;
        }
        if ($qn["difficulty"] === "medium"){
            $points = 3;
        }
        if ($qn["difficulty"] === "easy"){
            $points = 1;
        }
        $stmt->bind_param("ssis", $qn["question"], $qn["correct_answer"], $points, $qn["category"]);
        if (!$stmt->execute()){
            echo "Could not add question: {$qn["question"]}\n";
        }
    }
    foreach($animalsTrivia["results"] as $qn) {
        $points = 0;
        if ($qn["difficulty"] === "hard"){
            $points = 5;
        }
        if ($qn["difficulty"] === "medium"){
            $points = 3;
        }
        if ($qn["difficulty"] === "easy"){
            $points = 1;
        }
        $stmt->bind_param("ssis", $qn["question"], $qn["correct_answer"], $points, $qn["category"]);
        if (!$stmt->execute()){
            echo "Could not add question: {$qn["question"]}\n";
        }
    }