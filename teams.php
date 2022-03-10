<?php
include("userstorage.php");
include("commentstorage.php");
include("auth.php");
$matchesStorage = new Storage(new JsonIO('matches.json'));
$teamsStorage =new Storage(new JsonIO('teams.json'));
$commentsStorage =new Storage(new JsonIO('comments.json'));
$id= $_GET['id'];

$allthematches=$matchesStorage->findAll();
$matchesHome=array();
foreach($allthematches as $matches) {
    if($matches['home']["id"]==$id) {
        array_push($matchesHome,$matches);
    }
}
$matchesAway=array();
foreach($allthematches as $matches) {
    if($matches['away']["id"]==$id) {
        array_push($matchesAway,$matches);
    }
}
$teams = $teamsStorage->findById($id);

$comments= $commentsStorage->findAll(['teamid'=>$id]);

// print_r($comments);
// echo "\n";
function redirect($page) {
  header("Location: ${page}");
  exit();
}

session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);


$user_storage1 = new commentsStorage();
$auth = new Auth($user_storage1);
// functions

function validate($post, &$data, &$errors) {
  $data = $post;
  // VALIDATION
  if (!isset($post['author'])){
      $errors['author']="author is missing";
  }
  else if (trim($post["author"]) === "") {
      $errors['author']="author is empty";
  }
  if (!isset($post['name'])){
    $errors['name']="Comment is missing";
}
else if (trim($post["name"]) === "") {
    $errors['name']="Comment is empty";
}

if (!isset($post['teamid'])){
  $errors['teamid']="password is missing";
}
if (!isset($post['date'])){
  $errors['date']="date is missing";
}
  else {
    $data['author']=$post["author"];
    $data['name']=$post["name"];
    $data['teamid']=$post["teamid"];
    $data['teamid']=$post["teamid"];
      }

  return count($errors) === 0;
}
// main
$data = [];
$errors = [];
if (count($_POST) > 0) {
    
  if (validate($_POST, $data, $errors)) {
     
    
      print_r($data); 
     
  
        $auth->register1($data);
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // $redirection="teams.php?id=" + "$id";
        // print_r($redirection);
        redirect("$actual_link");
      }}
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELTE Teams</title>
</head>
<body>
<div class="topnav">
  <a class="" href="index.php">Home</a>
  <a class="active" href="teams.php?id=<?= $id?>">ELTE Teams</a>
  <div class="topnav-right">
    <a href="login.php">Log In</a>
    <a href="registration.php">Registration</a>
  </div>
</div>
<style>
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
  margin-top: 50px;
  padding-top: -60px;
  margin-bottom: 50px;
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
        font-size: 27px;
  color: black;
  text-transform: uppercase;
  font-weight: normal;
  line-height: 1;
 text-align: center;
  margin: 0px 50px 0 50px;
  padding-top: 0px; 
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
    form {
      margin-left: 50px;
        border:1px solid white;
        margin-bottom: 30px;
        margin-top:50px;
        font-size:22px;
        line-height:30px;
    }
    input {
      padding:5px;
    }
 
</style>



  <?= "<h1>Welcome to the ". $teams['name']." Club Page</h1>"?> 
<h2>Home Matches</h2>
<table>

<tr>
    <th>Home Team</th>
    <th>Guest Team</th>
    <th>Match Date</th>
    <th>Final Score</th>
    

</tr>

  
        <?php foreach($matchesHome as $match) : ?>
            <tr>
    <td>
        <?php $myId=$match['home']['id'];
              $myteam=($teamsStorage->findOne(['id'=>"$myId"]));
              echo '<b>'.$myteam['name'].'</b>';
        ?>
    </td>
    <td>    <?php $myId1=$match['away']['id'];
              $myteam1=($teamsStorage->findOne(['id'=>"$myId1"]));
              echo $myteam1['name'];
        ?></td>

<td><?= $match['date']?></td>
<?php 
      $color = $match['home']['score'] > $match['away']['score'] ? 'style="background-color:#1E8449"' : 'style="background-color:red"';
if($match['home']['score'] == $match['away']['score']) {$color = 'style="background-color:#F1C40F"';} ;
if($match['home']['score'] =="-") {$color = 'style="background-color:white"';} ?>
<td  <?=$color?>>
   <b> <?= $match['home']['score']." : ".$match['away']['score']?> </b>
    </td>

</tr>
<?php endforeach ?>
        </table>
<h2>Guest Matches</h2>
<table>

<tr>
    <th>Home Team</th>
    <th>Guest Team</th>
    <th>Match Date</th>
    <th>Final Score</th>
    

</tr>

    <!-- $color = $match['home']['score'] > $match['away']['score'] ? 'style="background-color:green"' : 'style="background-color:red"';?>
 if($match['home']['score'] == $match['away']['score']) {
        $color = 'style="background-color:yellow"';}  -->
        <?php foreach($matchesAway as $match) : ?>
<tr>
    <td>
        <?php $myId=$match['home']['id'];
              $myteam=($teamsStorage->findOne(['id'=>"$myId"]));
              echo $myteam['name'];
        ?>
    </td>
    <td>    <?php $myId1=$match['away']['id'];
              $myteam1=($teamsStorage->findOne(['id'=>"$myId1"]));
              echo '<b>'.$myteam1['name'].'</b>';
        ?></td>

<td><?= $match['date']?></td>
<?php 
      $color = $match['home']['score'] > $match['away']['score'] ? 'style="background-color:#1E8449"' : 'style="background-color:red"';
if($match['home']['score'] == $match['away']['score']) {$color = 'style="background-color:#F1C40F"';} ;
if($match['home']['score'] =="-") {$color = 'style="background-color:white"';} ?>
<td  <?=$color?>>
   <b> <?= $match['home']['score']." : ".$match['away']['score']?> </b>
    </td>

</tr>
<?php endforeach ?>
        </table>

  <h2>Leave a comment!</h2>
<?php if ($auth->authenticated_user()) : ?>
  <?php $user=$auth->authenticated_user(); ?>
  <form action="" method="post" novalidate>
  <div>
    <label for="author">Name: </label><br>
    <input type="text" name="author" id="author" value="<?=$user["username"]  ?? "" ?>" readonly>
    <?php if (isset($errors['author'])) : ?>
      <span class="error"><?= $errors['author'] ?></span>
    <?php endif; ?>
  </div>
  <div>
    <label for="name">Comment: </label><br>
    <input size="50%"  name="name" id="name" value="<?= $_POST['name'] ?? "" ?>" required>
    <?php if (isset($errors['name'])) : ?>
      <span class="error"><?= $errors['name'] ?></span>
    <?php endif; ?>
  </div>
  <div>
    <label for="teamid">Team ID: </label><br>
    <input type="text" name="teamid" id="teamid" value="<?= $_GET['id'] ?? "" ?>" readonly>
  </div>
  <div>
    <label for="date">Date: </label><br>
    <input type="text" name="date" id="date" value="<?= date("Y-m-d")?? "" ?>" readonly>
    </div>
    <button type="submit">Submit</button>
  </form>
  <?php endif; ?>

  
<h2 id="comment">Comments Section:</h2>
  <?php foreach($comments as $comment) : ?>
    <table id="mytable">
        <tr> 
          <th><?= $comment['author']." left a comment on ".$comment['date']?></th>
        </tr>
        <td><?=$comment['name']?></td>
    </table>
  <?php endforeach ?>
</body>
</html>