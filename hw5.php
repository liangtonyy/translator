<?php
    session_start();
    require_once 'login.php';

    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) {
        die("<h4 style='color:red'>There was a problem connecting to mySQL: </h4>" . $conn->connect_error);
    }

    $createUserCredTable = "CREATE TABLE IF NOT EXISTS userCred (
               idUsers int(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
               uidUsers TINYTEXT NOT NULL,
               emailUsers TINYTEXT NOT NULL,
               pwdUsers LONGTEXT NOT NULL

            )";
    if($conn->query($createUserCredTable) === FALSE) {
        die("<h4 style='color:red'>There was a problem creating the user database</h4>");
    }

    if(isset($_SESSION['userID'])){
        echo <<<_END
            <html>
                <head>
                    <body>
                        <form action="logout.inc.php" method="post">
                            <button type="submit" name="logoutBtn">Log Out</button>
                        </form>
                    </body>
                </head>
            </html>
        _END;
    }
    else{
        echo <<<_END
            <html>
                <head>
                    <body>
                        <form action="login.inc.php" method="post">
                            <input type="text" name="mailuid" placeholder="Username/Email" </input>
                            <input type="text" name="pwd" placeholder="Password" </input>
                            <button type="submit" name="loginBtn">Login</button>
                        </form>

                        <form action="signup.php" method="post">
                            <button type="submit" name="signupBtn">Signup</button>
                        </form>

                    </body>
                </head>
            </html>
        _END;
    }


    $conn->close();
?>