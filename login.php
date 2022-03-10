<?php
include('userstorage.php');
include('auth.php');

function validate($post, &$data, &$errors) {
    // username, password not empty
    
    $data = $post;
  
    if (!isset($post['username'])){
      $errors['username']="Username is missing";
  }
  else if (trim($post["username"]) === "") {
      $errors['username']="Name is empty";
  }
  if (!isset($post['password'])){
    $errors['password']="Password is missing";
}
else if (trim($post["password"]) === "") {
    $errors['password']="Password is empty";
}

    return count($errors) === 0;
  }
  
  function redirect($page) {
      header("Location: ${page}");
      exit();
    }

// main
session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);

$data = [];
$errors = [];
if ($_POST) {
  if (validate($_POST, $data, $errors)) {
    $auth_user = $auth->authenticate($data['username'], $data['password']);
    if (!$auth_user) {
      $errors['global'] = "Login error";
    } else {
      $auth->login($auth_user);
      redirect('index.php');
    }
  }
}

if ($auth->is_authenticated()) {
    
  redirect('index.php');
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
</head>
<div class="topnav">
  <a class="" href="index.php">Home</a>
  <!-- <a class="active" href="teams.php?id=<?= $id?>">ELTE Teams</a> -->
  <div class="topnav-right">
    <a class="active" href="login.php">Log In</a>
    <a href="registration.php">Registration</a>
  </div>
</div>
<style>
    form {
        text-align:center;
    margin-left: auto;
  margin-right: auto;
    }
    body {
        background-color:#F0F0F0;
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #04AA6D;
  color: white;
}

.topnav-right {
  float: right;
}
    table {
    border-collapse: collapse;
    margin-top: 50px;
    margin-bottom: 50px;
    font-size: 15px;
    text-align:center;
    margin-left: auto;
  margin-right: auto;
    }

    th {
        background-color:#04AA6D;
        font-size: 20px;
        font-family: Verdana;
        padding:10px;
        border: 1px solid white;
        color:white;
        font-weight:100;
    }
    td {
        padding: 17px;

    }
 tr{
    border: 2px solid white;
 }
    h1 {
        font-size: 40px;
  color: #04AA6D;
  font-weight: normal;
  line-height: 1;
  text-align: center;
  margin: 0px 50px 0 50px;
  margin-top: 120px;
  padding-top: -60px;
  margin-bottom: 30px;
    }
    #comment {
        font-weight: bold;
        font-size: 20px;
        color: 04AA6D;
        text-align: left;
        margin-top: 100px;
        margin-bottom: 50px;
    }
    h2 {
        font-size: 15px;
  color: black;
  font-weight: normal;
  line-height: 1;
 text-align: center;
  margin: 0px 50px 0 50px;
  padding-top: 10px; 
    }

    #mytable {
        background-color: lightgrey;
        text-align: left;
        margin-left: 50px;
  margin-right: auto;
    }

    #mytable td {
        margin-left: 50px;
        border:1px solid white;
    }
</style>
<body>
 
    <?php if (isset($errors['global'])) : ?>
  <p><span class="error"><?= $errors['global'] ?></span></p>
<?php endif; ?>
<form action="" method="post">
<h1>Login Page</h1>
<div>
    <label for="username">Username: </label><br><br>
    <input type="text" name="username" id="username" value="<?= $_POST['username'] ?? "" ?>">
    <?php if (isset($errors['username'])) : ?>
      <span class="error"><?= $errors['username'] ?></span>
    <?php endif; ?>
  </div>
  <div>
 <br>
    <label for="password">Password: </label><br>
    <br><input type="password" name="password" id="password">
    <?php if (isset($errors['password'])) : ?>
      <span class="error"><?= $errors['password'] ?></span>
    <?php endif; ?>
  </div>
  <br>
  <div>
    <button type="submit">Login</button>
  </div>
  <h2>No account?
    <br><a href="registration.php" >Register </a>here</h2>
</form>

    
</body>
</html>