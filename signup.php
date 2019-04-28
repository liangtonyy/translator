<?php
	echo "Signup Page";
	
    echo <<<_END
    	<html>
    		<head>
    			<body>
    				<form action="signup.inc.php" method="post">
    					<input type="text" name="mail" placeholder="Email" </input><br> 
                        <input type="text" name="uid" placeholder="Username" </input><br> 
    					<input type="text" name="pwd" placeholder="Password" </input><br>
    					<button type="submit" name="signupButton">Sign up</button>
    				</form>
    			</body>
    		</head>
    	</html>
    _END;


    
?>