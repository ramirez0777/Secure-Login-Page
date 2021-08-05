<?php
/********************************************************BUILDS FORM OR GIVES ACCESS GRANTED***************************************************/
function createData()
{
	if(empty($_POST['sub-button'])) //Button not clicked
	{
		$ec = 0;
		$form = createForm($ec);
		return $form;
	}
	else // Button Clicked
	{	
		$ec = 0;
		if (isset($_POST['user'])) {$user = $_POST['user'];} else {$user = ''; $ec = 1;}
		if (isset($_POST['pass'])) {$pass = $_POST['pass'];} else {$pass = ''; $ec = 1;}
		if ($ec) {createForm($ec);}
		$access = checkData($user, $pass);
		return $access;
	}
}


/***************************************************************CHECKS THE INFO YOU TYPE IN AGAINST THE DATABASE*****************************/
function checkData($username, $password)
{
	define("HOST", "localhost");
	define("USER", "root");
	define("PASS", "1550");
	define("BASE", "less-insecure");
			
	// Create Connection
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
			
	// Create Command
	$sql = "SELECT * FROM `db-li`";
			
	// Run Command
	$results = mysqli_query($conn, $sql) or die("something's wrong: ".mysqli_connect_error());
			 
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{
		$tusername = $username;
		$tpassword = $password;
		if ($final['username'] == $username)
		{
			
			if ($final['password'] == $password)
			{
				$site = accessGranted();
				
				break;
			}
			else
			{
				$ec = 2;
				$site = createForm($ec);
				break;
			}
		}
		else 
		{
			$ec = 2;
			$site = createForm($ec);
			break;
		}
	}
	return $site;
}


/****************************************************************CREATE THE FROM*************************************************************/
function createForm($ec)
{
	$error = $ec;
	$form = '';
	$form = '<div class="box-1"><h1 class="welcome">Login To Your Account</h1><p class="error">';
	if ($error == 1) {$form .= 'You forgot to fill something out';} else if ($error == 2) {$form .= 'ACCESS DENIED';}
	$form .= '</p></div>';
	$form .= '<div class="box-2">';
		$form .= '<form method="post" action="?s=">';
			$form .= '<input name="user" type="text" placeholder="Username" class="user">';
			$form .= '<input name="pass" type="password" placeholder ="Password" class="pass">';
			$form .= '<input type="submit" name="sub-button" class="submit">';
			$form .= '<input type="reset" class="reset">';
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
?>