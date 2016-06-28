<!DOCTYPE html>
<?php
   include("config.php");
   session_start();
   
 if(isset($_POST['submit'])){
		$errMsg = '';
		//username and password sent from Form
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		if($username == '')
			$errMsg .= 'You must enter your Username<br>';
		
		if($password == '')
			$errMsg .= 'You must enter your Password<br>';
		
		
		if($errMsg == ''){
			$records = $db->prepare('SELECT * FROM users WHERE username = :demo AND password =:demo');
			$records->bindParam(':demo', $username);
			$records->bindParam(':demo', $password);
			$records->execute();
			$results = $records->fetch(PDO::FETCH_ASSOC);
			if($results > 0){
				$_SESSION['login_success'] = $results['username'];
				header('location:dashboard.php');
				exit;
			}else{
				$errMsg .= 'Username and Password are not found<br>';
			}
		}
	}
?>
<html>
<head><title>Login | Dashboard</title></head>
<body>
	<div align="center">
		<div style="width:300px; border: solid 1px #006D9C; " align="left">
			<?php
				if(isset($errMsg)){
					echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$errMsg.'</div>';
				}
			?>
			<div style="background-color:#006D9C; color:#FFFFFF; padding:3px;"><b>Login</b></div>
			<div style="margin:30px">
				<form action="" method="post">
					<label>Username  :</label><input type="text" name="username" required /><br /><br />
					<label>Password  :</label><input type="password" name="password" required /><br/><br />
					<input type="submit" name='submit' value="Submit" /><br />
				</form>
			</div>
		</div>
	</div>
</body>
</html>