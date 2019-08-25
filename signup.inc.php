<?php
	require_once 'login.php';

	$conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) {
        die("<h4 style='color:red'>There was a problem connecting to mySQL: </h4>" . $conn->connect_error);
    }

    if(isset($_POST['signupButton'])){
    	$username = $_POST['uid'];
	    $email = $_POST['mail'];
	    $password = $_POST['pwd'];

	    if(empty($username) || empty($email) || empty($password)){
	    	header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail=".$email);
	    	exit();
	    }
	    else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)){
	    	header("Location: ../signup.php?error=invalidmailuid");
	    	exit();
	    }
	    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	    	header("Location: ../signup.php?error=invalidmail&uid=".$username);
	    	exit();
	    }
	    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
	    	header("Location: ../signup.php?error=invaliduid&mail=".$email);
	    	exit();
	    }
	    else{
	    	$sql = "SELECT uidUsers FROM userCred WHERE uidUsers=?";
	    	$stmt = mysqli_stmt_init($conn);
	    	if(!mysqli_stmt_prepare($stmt,$sql)){
	    		header("Location: ../signup.php?error=sqlerror");
	    		exit();
	    	}
	    	else{
	    		mysqli_stmt_bind_param($stmt,"s",$username);
	    		mysqli_stmt_execute($stmt);
	    		mysqli_stmt_store_result($stmt);
	    		$resultCheck = mysqli_stmt_num_rows($stmt);
	    		if($resultCheck > 0){
	    			header("Location: ../signup.php?error=usertaken&mail=".$email);
	    			exit();
	    		}
	    		else{
	    			$sql = "INSERT INTO userCred (uidUsers, emailUsers, pwdUsers) VALUES (?,?,?)";
	    			$stmt = mysqli_stmt_init($conn);
	    			if(!mysqli_stmt_prepare($stmt,$sql)){
	    				header("Location: ../signup.php?error=sqlerror");
	    				exit();
	    			}
	    			else{
	    				$hashedPwd = password_hash($password,PASSWORD_DEFAULT);
	    				mysqli_stmt_bind_param($stmt, "sss",$username, $email, $hashedPwd);
	    				mysqli_stmt_execute($stmt);
	    				header("Location: ../homepage.php");
	    				
	    				exit();
	    			}
	    		}
	    	}
	    }

	    mysqli_stmt_close($stmt);
	    mysqli_close($conn);
    }
    else{
    	header("Location: ../signup.php");
    	exit();
    }

    $conn->close();


