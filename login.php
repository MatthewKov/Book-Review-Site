<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
<html>
	<head>
		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<title>BookKeeper</title>
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />

  		<style>
  			body {
  				margin: 0px auto;
  				width: 50%;
  				display: block;
  			}

  			.error_message {
  				color: crimson;
  				font-style: italic;
  				padding: 10px;
  			}

  		</style>
  	</head>

    <?php
      require('db_connect.php');

      $_POST['err_user'] = "";
      $_POST['err_pass'] = "";

      function authenticate($usr, $pwd) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
          global $db;

          $query = "SELECT password FROM user WHERE username = :usr";

          $statement = $db->prepare($query);
          $statement->bindValue(':usr', $usr);
          $statement->execute();

          $result = $statement->fetch();
          $statement->closeCursor();
          if($result) {
            $pass = $result['password'];
            if(password_verify($pwd, $pass)) {
              session_start();
              $_SESSION['user'] = $usr;
              header('Location: explore.php');
            }
            else {
              $_POST['err_pass'] = "Incorrect password";
            }
          }
          else {
            $_POST['err_user'] = "That username doesn't exist";
          }
        }
      }

      if(!empty($_POST['username']) && !empty($_POST['password'])) {
        $usr = htmlspecialchars($_POST['username']);
        $pwd = htmlspecialchars($_POST['password']);
        authenticate($usr, $pwd);
      }
    ?>

  	<body>
  		<div class="container">
	  		<h4>Sign in to BookKeeper</h4>
	  		<form action="login.php" name="loginForm" method="post" onsubmit="return checkInput()">
	  			<div class="form-group">
	  				<input id="username" name="username" class="form-control" type="text" placeholder="Username">
	  				<span class="error_message" id="err_user"><?php echo $_POST['err_user'] ?></span>
	  			</div>
	  			<div class="form-group">
	  				<input id="password" name="password" class="form-control" type="password" placeholder="Password">
	  				<span class="error_message" id="err_pass"><?php echo $_POST['err_pass'] ?></span>
	  			</div>
	  			<div class="form-group">
	  				<button id="submit" class="btn btn-primary" type="submit">Sign in</button>
	  			</div>
          <div>
          <a href="registration.php">Don't have an account?</a>
        </div>
	  		</form>
	  	</div>

  		<script>
  			function checkInput() {
  				var num_errs = 0;
  				var username = document.getElementById("username");
  				if(username.value === "") {
  					num_errs++;
  					document.getElementById("err_user").innerHTML = "Username is required";
  				}
  				else {
  					document.getElementById("err_user").innerHTML = "";
  				}

  				var password = document.getElementById("password");
  				if(password.value === "") {
  					num_errs++;
  					document.getElementById("err_pass").innerHTML = "Password is required";
  				}
  				else {
  					document.getElementById("err_pass").innerHTML = "";
  				}

  				if(num_errs > 0) {
  					return false;
  				}
  				return true;
  			}
  		</script>
  	</body>
</html>