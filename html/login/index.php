<?php
	require_once('../befuncs/snips.php');
	require_once('../befuncs/db_user.php');
	$db=new accountdb();
	
	$username_cache = '';
	$passwd_cache = '';
	$login_result = null;

	if(	array_key_exists('user',$_POST)
	&&	array_key_exists('passwd',$_POST) ){
		$login_result = $db->login($_POST['user'],$_POST['passwd']);
		if($login_result){
			if(array_key_exists('redirect_to', $_POST)){
				$url = parse_url($_POST['redirect_to']);
				if(is_array($url)){
					$uri = $url['path'] . '?' . $url['query'] . '#' . $url['fragment'];
					header('Location: ' . $uri, true, 303);
					die();
				}
			}
			header('Location: /login/');
			die();
		}else{
			$username_cache = $_POST['user'];
			$passwd_cache = $_POST['passwd'];
		}
	}else if(array_key_exists('logout',$_POST)){
		$db->logout();
	}
	
	genUsual('Riedler\'s Login Site',['/style/login.css'],'');
?>
<body>
	<?php
		genNavBar();
	?>
	<form id="loginform" method="POST" action="/login/">
		<?php if($_SESSION['userid']){
			$user = $db->get_user_by_id($_SESSION['userid']);
		?>
		
		<h2>LOGOUT</h2>
		<span>Logged in as <?= $user['type']?> "<?= $user['name'] ?>"</span>
		<input type="submit" value="Logout" class="btn" name="logout" id="i_submit"/>
		
		<?php }else{ ?>
		
		<h2>LOGIN</h2>
		<label for="i_user">Username:</label>
		<input type="text" name="user" id="i_user" class="input__text" value="<?= $username_cache ?>" required/>
		<label for="i_passwd">Password:</label>
		<input type="password" name="passwd" id="i_passwd" class="input__text" value="<?= $passwd_cache ?>" required/>
		<input type="submit" value="Login" class="btn" id="i_submit"/>
		<?php
			if($login_result===false){
				echo '<span>incorrect password or username</span>';
			}
		?>
		<?php } ?>
	</form>
	<?php genFooter(); ?>
</body>