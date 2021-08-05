<?php

/********************************************************BUILDS FORM OR GIVES ACCESS GRANTED***************************************************/
function createData()
{
	if(empty($_POST['sub-button'])) //Button not clicked
	{
		if(empty($_GET['new'])){$ec = 0;} else {$ec = $_GET['new'];}
		$form = createForm($ec);
		return $form;
	}
	else // Button Clicked
	{	
		$ec = 0;
		if (isset($_POST['user'])) {$user = strtolower($_POST['user']);} else {$user = ''; $ec = 1;}
		if (isset($_POST['pass'])) {$pass = $_POST['pass'];} else {$pass = ''; $ec = 1;}
		if ($ec) {$access = createForm($ec);}
		if (!$ec) {$access = checkData($user, $pass);}
		return $access;
	}
}


/***************************************************************CHECKS THE INFO YOU TYPE IN AGAINST THE DATABASE*****************************/
function checkData($username, $password)
{
	if ($_SERVER['HTTP_HOST'] == 'localhost')
	{
		define("HOST", "localhost");
		define("USER", "root");
		define("PASS", "1550");
		define("BASE", "less-insecure");
	}
	else
	{
		define("HOST", "sql107.freesite.vip");
		define("USER", "frsiv_25076911");
		define("PASS", "Not1550");
		define("BASE", "frsiv_25076911_lessinsecure");
	}
	// Create Connection
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
	
	$salt1 = hash("SHA256", $username);
	$salt2 = hash("SHA512", $username.$username.$username);
	$password = $salt1.$password.$salt2;
	$password = hash("SHA512",$password);
	// Create Command
	$sql = 'SELECT * FROM `db-li`';
			
	// Run Command
	$results = mysqli_query($conn, $sql) or die("something's wrong: ".mysqli_connect_error());
	
	
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{

		if ($final['username'] == $username & $final['password'] == $password)
		{
			$site = accessGranted();
			break;
		}
		else
		{
			$ec = 2;
		}
		
		if ($ec == 2)
		{
		$site = createForm($ec);
		}
	}
	// $site = $sql;
	return $site;
}


/****************************************************************CREATE THE FROM*************************************************************/
function createForm($ec)
{
	$form = '';
	$form = '<div class="box-1"><h1 class="welcome">Login To Your Account</h1><p class="error">';
	$form .= error($ec);
	$form .= '</p></div>';
	$form .= '<div class="box-2">';
		$form .= '<form method="post" action="?s=">';
			$form .= '<input name="user" type="text" placeholder="Username" class="user">';
			$form .= '<input name="pass" type="password" placeholder ="Password" class="pass">';
			$form .= '<input type="submit" name="sub-button" class="buttons">';
			$form .= '<input type="reset" class="buttons">';
			$form .= '<button type="button" class="buttons" onclick="location.href=\'create-account.php\'"><span class="signup">Sign Up</span></button>';
		$form .= '</form>';
	$form .= '</div>';
	return $form;
}


/*******************************************************CREATE ACCESS GRANTED*******************************************************************/
function accessGranted()
{

	$granted = '';
	$granted .= '<div class="box-1"><h1 class="welcome">ACCESS GRANTED</h1><p class="enter">Time for you to break into the Matrix</p></div>';
	$granted .= '<div class="box-2"><img src="img/matrix.gif" alt="matrix" class="matrix"></div>';
	$granted .= '';
	return $granted;
}

/******************************************************CREATE ERROR LINE*******************************************************************/
function error($error)
{
	$form = '';
	if($error == 0)
	{
		$form .= '';
	}
	else if ($error == 1) 
	{
		$form .= 'You forgot to fill something out';
	} 
	else if ($error == 2) 
	{
		$form .= 'ACCESS DENIED';
	}
	if ($error === 'true')
	{
		$form .= '<span class="signup">Your Account was Created Successfully</span>';
	}
	return $form;
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Log-in Page</title>
		<meta charset="UTF-8" />
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<link href="img/icon1.jpg" rel="icon" type="image/x-icon">
		<script src="js/script.js"></script>
	</head>
	<body>
		<main>
			<?php echo createData();?>
		</main>
	</body>
</html>
