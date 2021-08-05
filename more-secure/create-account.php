<?php
/*******************************************************CREATE CONSTANTS********************************************************************/
if ($_SERVER['HTTP_HOST'] == 'localhost')
	{
		define("HOST", "");
		define("USER", "");
		define("PASS", "");
		define("BASE", "");
	}
	else
	{
		define("HOST", "");
		define("USER", "");
		define("PASS", "");
		define("BASE", "");
	}

/****************************************************************CREATE THE SITE****************************************************************/
function createData()
{
	//Create Error Trap for empty fields, duplicate usernames, and secret code not being correct`
	if(empty($_POST['sub-button'])) //Button not clicked
	{
		$site = createForm();
		return $site;
	}
	else //Button is clicked
	{

		$ec = 0;
		if (!empty($_POST['user'])) {$user = strtolower($_POST['user']);} else {$user = ''; $ec += 1;}
		if (!empty($_POST['pass'])) {$pass = $_POST['pass'];} else {$pass = ''; $ec += 2;}
		if (!empty($_POST['secret'])) {$secret = $_POST['secret'];} else {$secret = ''; $ec += 10;}

		if ($ec === 0) {$ec = checkSecret($secret);}		
		if ($ec === 0) {$ec = intoDB($user, $pass);}

		if ($ec === 'granted') 
		{
			header ('location: index.php?new=true');
		}
		elseif ($ec != 'granted')
		{
			$site = createForm($ec);
		}
		return $site;
	}
}

/***********************************************************CREATE FORM**************************************************************************/
function createForm($error = 0)
{
	$form = '';
	$form .= '<div class="box-1">';
		$form .= '<h1 class="welcome welcome-1">Sign Up</h1>';
		$form .= '<h1 class="welcome">For Your</h1>';
		$form .= '<h1 class="welcome">Account</h1>';
		$form .= '<p class="error">'.errorTrap($error).'</p>';
	$form .= '</div>';
	$form .= '<div class="box-2">';
		$form .= '<form method="post" action="?fs=">';
			$form .= '<input type="text" placeholder="Username" name="user" value="">';
			$form .= '<input type="password" placeholder="Password" name="pass" oninput="verify()" id="pass">';
			$form .= '<input type="password" placeholder="Verify Password" name="vpass" oninput="verify()" id="vpass">';
			// Line that shows passwords don't match

			$form .= '<input type="password" placeholder="Secret Code" name="secret">';
			$form .= '<input type="submit" name="sub-button" value="Create Account" class="buttons" disabled id="submit">';
			$form .= '<input type="reset" class="buttons">';
			$form .= '<p id="verify" class="hide">Your Passwords don\'t match</p>'; 
			// $form .= '<a href="index.php"><button class="buttons">Back to Login Page</button></a>';
		$form .= '</form>';
	// $form .= '</div>';
	// $form .= '<div class="box-2">';
		
	$form .= '</div>';
	return $form;
}

/***********************************************************CATCH AND DEFINE ERRORS***********************************************************/
function errorTrap($error)
{
	$forgot = 'You Forgot To Type in <span class="errors">';
	$message = '';
	if($error == 0)
	{
		$message = '';
	}
	elseif($error == 1)
	{
		$message = $forgot . 'the Username</span>';
	}
	elseif($error == 2)
	{
		$message = $forgot . 'the Password</span>';
	}
	elseif($error == 3)
	{
		$message = $forgot . 'the Username and Password</span>';
	}
	elseif($error == 10)
	{
		$message = $forgot . 'the Secret Code</span>';
	}
	elseif($error == 11)
	{
		$message = $forgot . 'the Username and the Secret Code</span>';
	}
	elseif($error == 12)
	{
		$message = $forgot . 'the Password and the Secret Code</span>';
	}
	elseif($error == 13)
	{
		$message = $forgot . 'the Username, Password, and the Secret Code</span>';
	}
	if($error === 'user')
	{
		$message = 'That <span class="errors">Username</span> is already being used';
	}
	elseif($error === 'secret')
	{
		$message = 'The <span class="errors">Secret Code</span> is incorrect';
	}
	return $message;
}

/**************************************************INSERT INTO DATABASE***************************************************************/
function intoDB($username, $password)
{
	// Create Connection
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
			
	// Create Command
	$sql = 'SELECT * FROM `db-li`';
	
	$results = mysqli_query($conn, $sql) or die("Something's wrong: ".mysqli_connect_error());
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{
		if ($username == $final['username'])
		{
			return 'user';
		}
	}
	$salt1 = hash("SHA256", $username);
	$salt2 = hash("SHA512", $username.$username.$username);
	$password = $salt1.$password.$salt2;
	$password = hash("SHA512",$password);
	$insert = 'INSERT INTO `db-li`(`username`, `password`) VALUES ("'.$username.'", "'.$password.'")';

	mysqli_query($conn, $insert);
	return 'granted';
}

/****************************************************CHECK TO SEE IF SECRET CODE IS CORRECT********************************************/
function checkSecret($secret)
{
	$sql = 'SELECT * FROM `secret`';
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
	$results = mysqli_query($conn, $sql) or die("Something's wrong: ".mysqli_connect_error());
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{
		if ($final['secret'] == $secret)
		{
			return 0;
		}
		else
		{
			return 'secret';
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Create Account</title>
		<meta charset="UTF-8" />
		<link type="text/css" rel="stylesheet" href="css/style2.css">
		<link href="img/icon1.jpg" rel="icon" type="image/x-icon">
		<script src="js/script.js"></script>
	</head>
	<body>
		<main>
			<?php echo createData();?>
		</main>
	</body>
</html>
