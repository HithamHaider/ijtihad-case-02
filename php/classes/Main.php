<?php
class Main extends Pager {
	public function Home() {
		return '<h1>Welcome</h1>';
	}
	public function Register() {
		return '<h1>Register</h1>';
	}
	public function Login() {
		$body = '<h1>Login</h1><hr>
				<form method="post" action="'.$this->meUrl('doLogin').'">
					Username: <input type="email" name="username"><br>
					Password: <input type="password" name="password">
					<hr>
					<input type="submit">
				</form>
				';
		return $body;
	}
	public function doLogin($username, $password) {
		//$sql = "SELECT * FROM tbl_users WHERE `email`='$username' AND password='$password'";
		//$arr = $this->DBQuery($sql);
		return 'Thank you';
	}
}
?>