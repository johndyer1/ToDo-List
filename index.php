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
?>

<?php
    //Post data
    $itemNum = filter_input(INPUT_POST, "ItemNum", FILTER_UNSAFE_RAW);
    $title = filter_input(INPUT_POST, "Title", FILTER_UNSAFE_RAW);
    $description = filter_input(INPUT_POST, "Description", FILTER_UNSAFE_RAW);

    //Get data
    $iTitle = filter_input(INPUT_GET, "iTitle", FILTER_UNSAFE_RAW);
    $iDescription = filter_input(INPUT_GET, "iDescription", FILTER_UNSAFE_RAW);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <header>ToDo List</header>

        <section>
            <h2>Input</h2>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">
                <label for="iTitle">Title</label>
                <input type="text" id="iTitle" name="iTitle" required>
                <br>
                <label for="iDescription">Description</label>
                <input type="text" id="iDescription" name="iDescription" required>
                <br>
                <input type="submit" name="submit" class="button" value="Submit" />
            </form>
        </section>
        <?php 
        if(isset($_GET['submit'])){
            $query = 'INSERT INTO todoitems (Title, Description) VALUES (:iTitle, :iDescription)';
            $statement = $db->prepare($query);
            $statement->bindValue(':iTitle', $iTitle);
            $statement->bindValue(':iDescription', $iDescription);
            $statement->execute();
            $statement->closeCursor();
            header('Location: http://localhost/ToDo%20List/index.php', true);
            die();
        }
        ?>
        
        <section>
            <table>
                <?php
                    $query = 'SELECT ItemNum, Title, Description FROM todoitems';
                    $statement = $db->query($query);
                    $lrow = $statement->fetch();
                    if (!($lrow)) {
                        echo "<h2>NO ITEMS ENTERED</h2>";
                    }
                    else {
                        echo "<tr>". 
                            "<th><h3>ItemNum</h3></th>" . 
                            "<th><h3>Title</h3></th>". 
                            "<th><h3>Description</h3></th>" . 
                            "<th><h3>Delete</h3></th>" . 
                            "</tr>";
                        $lid = $lrow["ItemNum"];
                        echo "<tr>". 
                                    "<th>" . $lrow["ItemNum"] . "</th>" . 
                                    "<th>" . $lrow["Title"] . "</th>". 
                                    "<th>" . $lrow["Description"] . "</th>" . 
                                    "<th><a href='delete.php?id=$lid'>❌</a></th>" . 
                                "</tr>";
                        while ($row = $statement->fetch()) {
                            $id = $row["ItemNum"];
                            echo "<tr>". 
                                    "<th>" . $row["ItemNum"] . "</th>" . 
                                    "<th>" . $row["Title"] . "</th>". 
                                    "<th>" . $row["Description"] . "</th>" . 
                                    "<th><a href='delete.php?id=$id'>❌</a></th>" . 
                                "</tr>";
                        }
                    }
                    $statement->closeCursor();
                    die();
                ?>
            </table>
        </section>

    </main>  
</body>
</html>