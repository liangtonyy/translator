<?php
	require "homepage.php";

?>
<main>
	<div class="wrapper-main">
		<section class="section-default">
			<?php
				if(isset($_SESSION['userID'])){
					echo '<p class="login-status">You are logged in!</p>';
				}
				else{
					echo '<p class="login-status">You are logged out!</p>';
				}
			?>
		</section>
	</div>
</main>


<?php 
	require_once 'login.php';

	$conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) {
        die("<h4 style='color:red'>There was a problem connecting to mySQL: </h4>" . $conn->connect_error);
    }

    $createUserContentTable = "CREATE TABLE IF NOT EXISTS userContent (
               contentId int(10) AUTO_INCREMENT PRIMARY KEY NOT NULL ,
               contentName VARCHAR(64) NOT NULL,
               dictionaryKey VARCHAR(64) NOT NULL,
               dictionValue VARCHAR(64) NOT NULL,
               idUsers int NOT NULL,
         
               FOREIGN KEY(idUsers) REFERENCES userCred(idUsers)
            )";
    if($conn->query($createUserContentTable) === FALSE) {
        die("<h4 style='color:red'>There was a problem creating the userContent database</h4>");
    }

     function closeConnectionAndExit($message, &$conn) {
        $conn->close();
        exit("<h4 style='color:red'>$message</h4>");
    }


	echo <<<_END
    	<html>
    		<head>
    			<body>
    				<form enctype="multipart/form-data" action="index.php" method="POST">
    					<p>Upload your dictionary(.txt files ONLY)</p>
                        <p>format for .txt file MUST be</p>
                        <p>key: value</p>
    						<input type="file" name="file"></input><br />
    						<input type="submit" value="Upload"></input>
    				</form>

                    <form enctype="multipart/form-data" action="index.php" method="POST">
                        <textarea name="textToTranslate" cols="60" rows="10"></textarea>
                        <input type="submit" name="translateBtn" value="Translate"></input>

                    </form>
    			</body>
    		</head>
    	</html>
    _END;


    if($_FILES && is_uploaded_file($_FILES['file']['tmp_name']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $name = $_FILES['file']['name'];
        $name = strtolower(preg_replace("/[^A-Za-z0-9.]/", "", $name));
        
        if($_FILES['file']['type'] == "text/plain" ) {
            move_uploaded_file($_FILES['file']['tmp_name'], $name);
            
            $queryString = file_get_contents($name);
            if(trim($queryString) == "") {
                closeConnectionAndExit("The textfile is empty.", $conn);
            }
            else{
                $data = file($name);
                $returnArray = array();
                foreach($data as $line) {
                    $explode = explode(": ", $line);
                    $returnArray[$explode[0]] = $explode[1];
                }
                foreach($returnArray as $x => $x_value) {
                    $qry = "INSERT INTO userContent (contentName, dictionaryKey, dictionValue, idUsers) VALUES ('".$name."', '".$x."', '".$x_value."', '".$_SESSION['userID']."')";
                    mysqli_query($conn,$qry);
                }
                    

                }
                
            echo "<h4 style='color:green'>Text file successfully uploaded!!</h4>";
            }
            else{
                closeConnectionAndExit("Only plain text documents are allowed. $name is not an accepted file.", $conn);
            }
        }


    if(isset($_POST['translateBtn'])){
        $beforeTranslate = array($_POST['textToTranslate']);
        $words = explode(" ", $beforeTranslate[0]);
        foreach($words as $word){
            echo $word . " ";
        }
        echo "<br> translates to <br>";

        foreach($words as $word){
            $sql = "SELECT * FROM userContent WHERE dictionaryKey = '$word' AND idUsers ='".$_SESSION['userID']."'";
            $result = $conn->query($sql);
            if ($result->num_rows <= 0) {
                echo $word . " ";
            }
            while($row = $result->fetch_assoc()) {
                echo $row["dictionValue"] ." ";
            }
        }
    }

    echo "<br><br><-----dictionary-----><br>";
      
    $sql = "SELECT * FROM userContent WHERE idUsers ='".$_SESSION['userID']."'";
     $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<br>" . $row["dictionaryKey"]. " means ". $row["dictionValue"];
        }
    } else {
        echo "0 results";
    }

    
    
	$conn->close();
    

?>
