<?php

include("userstorage.php");
include("auth.php");
// print_r($_GET);
// print_r($_POST);

session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
if ($auth->is_authenticated()) {
    
    redirect('index.php');
  }
$user = $auth->authenticated_user();
//Validate data on the server side in PHP. Display the errors next to the form fields. 
//You can use the general form processing template for this section, where you only need to extend the validatefunction:

// functions
function validate($post, &$data, &$errors) {
    $data = $post;
    // VALIDATION
    if (!isset($post['username'])){
        $errors['username']="Name is missing";
    }
    else if (trim($post["username"]) === "") {
        $errors['username']="Name is empty";
    }
    if (!isset($post['email'])){
      $errors['email']="Email is missing";
  }
  else if (trim($post["email"]) === "") {
      $errors['email']="Email is empty";
  }
  if(!filter_var($post["email"], FILTER_VALIDATE_EMAIL)){
    $errors['email']="Email is in wrong format";
  }
  if (!isset($post['password'])){
    $errors['password']="password is missing";
}
else if (trim($post["password"]) === "") {
    $errors['password']="password is empty";
}
if (!isset($post['repeatpassword'])){
  $errors['repeatpassword']="password is missing";
}
else if (trim($post["repeatpassword"]) === "") {
  $errors['repeatpassword']="password is empty";
}
if($post['repeatpassword']!=$post['password']) {
  $errors['repeatpassword']="Passwords don't match";
}
    else {
      $data['username']=$post["username"];
      $data['email']=$post["email"];
      $data['password']=$post["password"];
        }

    return count($errors) === 0;
}
// main
$data = [];
$errors = [];
//if something is coming read the data and process it
if (count($_POST) > 0) {
    
    if (validate($_POST, $data, $errors)) {
       
      
        print_r($data); 
       
    
        if ($auth->user_exists($data['username'])) {
          $errors['global'] = "User already exists";
        }else {
          $auth->register($data);
          header('Location: login.php'); //redirect the page 
        exit(); }}
     else {
        print_r($errors);
    }
}



// include("userstorage.php");
// // include("storage.php");
// include('auth.php');
// print_r($_POST);
// // functions
// function validate($post, &$data, &$errors) {

//   $data = $post;
 
//   // VALIDATION
//   if (!isset($post['username'])){
//       $errors['username']="Name is missing";
//   }
//   if(!isset($post['email'])) {
//     $errors['email']="Email is missing";
//   }
//   if(!isset($post['password'])) {
//     $errors['password']="Password is missing";
//   }
//   if(!isset($post['repeatpassword'])) {
//     $errors['password']="Password is missing";
//   }
//   else {
//       $data['username']=$post["username"];
//       $data['email']=$post["email"];
//       $data['password']=$post["password"];
//       }
//   return count($errors) === 0;
// }
function redirect($page) {
    header("Location: ${page}");
    exit();
  }
// // main
// $user_storage = new UserStorage();
// $auth = new Auth($user_storage);
// $errors = [];
// $data = [];
// if (count($_POST) > 0) {
//   if (validate($_POST, $data, $errors)) {
//     if ($auth->user_exists($data['username'])) {
//       $errors['global'] = "User already exists";
//     } else {
//       $auth->register($data);
//       // redirect('login.php');
//     } 
//   }
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<style>
  input[type=text], input[type=password],input[type=email] {
  width: 25%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus,input[type=email]:focus {
  background-color: #ddd;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 27.5%;
  opacity: 0.9;
}
h1 {
        font-size: 30px;
  color: #04AA6D;
  font-weight: normal;
  line-height: 1;
  margin: 0px 50px 0 50px;
  margin-top: 50px;
  padding-top: -60px;
  margin-bottom: 50px;
  margin-left:60px;
    }
button:hover {
  opacity: 1;
}
</style>
<body>
    <h1>Registering Form</h1>
    <?php if (isset($errors['global'])) : ?>
  <p><span class="error"><?= $errors['global'] ?></span></p>
<?php endif; ?>
<form action="" method="post" novalidate>
  <div>
    <label for="username">Username: </label><br>
    <input type="text" name="username" id="username" value="<?= $_POST['username'] ?? "" ?>" required>
    <?php if (isset($errors['username'])) : ?>
      <span class="error"><?= $errors['username'] ?></span>
    <?php endif; ?>
  </div>
  <div>
    <label for="email">Email: </label><br>
    <input type="email" name="email" id="email" value="<?= $_POST['email'] ?? "" ?>" required>
    <?php if (isset($errors['email'])) : ?>
      <span class="error"><?= $errors['email'] ?></span>
    <?php endif; ?>
  </div>

  <div>
    <label for="password">Password: </label><br>
    <input type="password" name="password" id="password" required>
    <?php if (isset($errors['password'])) : ?>
      <span class="error"><?= $errors['password'] ?></span>
    <?php endif; ?>
  </div>
  <div>
 
  <div>
    <label for="repeatpassword">Repeat Password: </label><br>
    <input type="password" name="repeatpassword" id="repeatpassword" required>
    <?php if (isset($errors['repeatpassword'])) : ?>
      <span class="error"><?= $errors['repeatpassword'] ?></span>
    <?php endif; ?>
  </div>
    <button type="submit">Register</button>
  </div>
</form>
</body>
</html>