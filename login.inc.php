
<?php
	require_once 'login.php';

	$conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) {
        die("<h4 style='color:red'>There was a problem connecting to mySQL: </h4>" . $conn->connect_error);
    }

	if(isset($_POST['loginBtn'])){
		echo "<h4 style='color:red'>LOGIN.INC isset</h4>";

		$mailuid = $_POST['mailuid'];
		$password = $_POST['pwd'];

		if(empty($mailuid) || empty($password)){
			header("Location: ../hw5.php?error=emptyfields");
			exit();
		}
		else{
			echo "<h4 style='color:red'>in ELSE </h4>";

			$sql = "SELECT * FROM userCred WHERE uidUsers=? OR emailUsers=?;";
			$stmt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt,$sql)){ 
				header("Location: ../hw5.php?error=sqlerror");
				exit();
			}
			else{
				mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				if($row = mysqli_fetch_assoc($result)){
					$pwdCheck = password_verify($password,$row['pwdUsers']);
					if($pwdCheck == false){
						header("Location: ../hw5.php?error=wrongpwd");
						
						exit();
					}
					else if($pwdCheck == true){
						session_start();
						$_SESSION['userID'] = $row['idUsers'];
						$_SESSION['userUid'] = $row['uidUsers'];
						header("Location: ../index.php?login=success");
						
						exit();
					}
					else{
						header("Location: ../hw5.php?error=wrongpwd");
						exit();

					}
				}
				else{
					header("Location: ../hw5.php?error=nosuser");
				}
			}
		}
	}
	else{
		header("Location: ../hw5.php");
		exit();
	}	

	$conn->close();
