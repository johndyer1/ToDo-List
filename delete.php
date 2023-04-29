<?php
    //Connect to the database 
    $dsn ='mysql:host=localhost;dbname=todolist';
    $username = 'root';
    //Try connecting to the database and exit if there is an issue
    try
    {
        $db = new PDO($dsn, $username);
    }
    catch (PDOException $e)
    {
        //Concat an error message to echo to the console if there is a database error and exit
        $error = "Database Error: ";
        $error .= $e ->getMessage();
        echo $error;
        exit();
    }

    $id = $_GET['id'];

    $query = "DELETE FROM todoitems WHERE ItemNum = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();
            $statement->closeCursor();
            header('Location: http://localhost/ToDo%20List/index.php', true);
            die();
?>