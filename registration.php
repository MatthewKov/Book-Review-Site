<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Website name</title>
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

    function verifyNewUser($username) {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        global $db;

        $query = "SELECT username FROM user WHERE username = :usr";

        $statement = $db->prepare($query);
        $statement->bindValue(':usr', $username);
        $statement->execute();

        if(count($statement->fetchAll()) > 0) {
          $statement->closeCursor();
          return false;
        }
        $statement->closeCursor();
        return true;
      }
    }

    function createUser($username, $password) {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        global $db;

        $query = "INSERT INTO user (username, password) VALUES (:usr, :pass)";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':usr', $username);
        $statement->bindValue(':pass', password_hash($password, PASSWORD_BCRYPT));
        $statement->execute();
        
        $statement->closeCursor();
      }
    }

    if(!empty($_POST['username']) && !empty($_POST['password1']) && !empty($_POST['password2']) && $_POST['password1'] == $_POST['password2']) {
      $username = trim($_POST['username']);
      if(verifyNewUser($username)) {
        $password = $_POST['password1'];
        createUser($username, $password);
        header('Location: login.php');
      }
      else {
        $_POST['err_user'] = "That username is already taken";
      }
    }

  ?>

	<body>
    <div class="container">
      <h4>Create an account with BookKeeper</h4>
      <form action="registration.php" name="registrationForm" method="post" onsubmit="return checkInput()">
        <div class="form-group">
          <input id="username" name="username" class="form-control" type="text" placeholder="Username">
          <span class="error_message" id="err_user"><?php echo $_POST['err_user'] ?></span>
        </div>
        <div class="form-group">
          <input id="password1" name="password1" class="form-control" type="password" placeholder="Password">
          <span class="error_message" id="err_pass1"></span>
        </div>
        <div class="form-group">
          <input id="password2" name="password2" class="form-control" type="password" placeholder="Confirm password">
          <span class="error_message" id="err_pass2"></span>
        </div>
        <div class="form-group">
          <button id="submit" class="btn btn-primary" type="submit">Create Account</button>
        </div>
        <div>
          <a href="login.php">Already have an account?</a>
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

          var password1 = document.getElementById("password1");
          if(password1.value === "") {
            num_errs++;
            document.getElementById("err_pass1").innerHTML = "Password is required";
          }
          else {
            document.getElementById("err_pass1").innerHTML = "";
          }

          var password2 = document.getElementById("password2");
          if(password2.value === "" || password1.value != password2.value) {
            num_errs++;
            document.getElementById("err_pass2").innerHTML = "Passwords don't match";
          }

          if(num_errs > 0) {
            return false;
          }
          return true;
        }
      </script>
  </body>
</html>