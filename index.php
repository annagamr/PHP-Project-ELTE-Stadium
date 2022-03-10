<?php
include("userstorage.php");
include("auth.php");
//authentication
// function redirect($page) {
//     header("Location: ${page}");
//     exit();
//   }

// session_start();
// $user_storage = new UserStorage();
// $auth = new Auth($user_storage);
// if (!$auth->is_authenticated()) {
//     redirect('index.php');
//   }
// $user = $auth->authenticated_user();
session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);

$teamStorage = new Storage(new JsonIO('teams.json'));
$teams = $teamStorage->findAll();
$matchesStorage = new Storage(new JsonIO('matches.json'));
$matches = $matchesStorage->findAll();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
<div class="topnav">
  <a class="active" href="#home">Home</a>
  <!-- <a href="teams.php">ELTE Teams</a> -->
  <div class="topnav-right">
    <a href="login.php">Log In</a>
    <a href="registration.php">Registration</a>
  </div>
</div>

<style>
    body {
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
    font-size: 20px;
    font-family: Helvetica;
    text-align:center;
    box-shadow: 7px 18px 18px grey;
    margin-left: auto;
  margin-right: auto;
    }

    th {
        background-color:lightgrey;
        font-size: 25px;
        padding:10px;
        border: 1px solid black;
    }
    td {
        padding: 17px;

    }
 tr{
    border: 1px solid black;
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


</style>
<?php if ($auth->authenticated_user()) : ?>
  <?php $user=$auth->authenticated_user(); ?>
<div id="introduction">
  <h1>Welcome to the ELTE Stadium, <?=$user['username']?>!</h1>
  <h1>Here you can see all the Team Clubs from ELTE and the Matches!</h1>
  <h1>You can <a href="logout.php">Log Out</a></h2>
</div>
<?php else : ?>
  <div id="introduction">
  <h1>Welcome to the ELTE Stadium!</h1>
  <h1>Here you can see all the Team Clubs from ELTE and the Matches!</h1>
  <h1>Please, Log In or Register for better experience!</h1>
</div>
<?php endif; ?>

<br>
    <h2>You can see all the teams here:</h2>
    <div class="wrapper">
    <table>
    <tr>    
    <?php foreach($teams as $team) : ?>
      <td style="background-color:lightgrey">
      <a href="teams.php?id=<?= $team["id"]?>">   
            <b><?= $team['name'] ?></b>
            <?php echo "</a>" ?>
</td>
        <?php endforeach ?>
        </tr> 
    </table>
    </div>
<br>
    <h2>All the matches:</h2>

    <div class="wrapper">
    <table>

<tr>
    <th>Home Team</th>
    <th>Guest Team</th>
    <th>Final Score</th>
    <th>Match Date</th>

</tr>
<?php foreach($matches as $match) : ?>
    <!-- $color = $match['home']['score'] > $match['away']['score'] ? 'style="background-color:green"' : 'style="background-color:red"';?>
 if($match['home']['score'] == $match['away']['score']) {
        $color = 'style="background-color:yellow"';}  -->
<tr>
    <td>
        <?php $myId=$match['home']['id'];
              $myteam=($teamStorage->findOne(['id'=>"$myId"]));
              echo '<b>'.$myteam['name'].'</b>';
        ?>
    </td>
    <td>    <?php $myId1=$match['away']['id'];
              $myteam1=($teamStorage->findOne(['id'=>"$myId1"]));
              echo $myteam1['name'];
        ?></td>
    <td>
   <b> <?= $match['home']['score']." : ".$match['away']['score']?> </b>
    </td>
    <td><?= $match['date']?></td>
</tr>
<?php endforeach ?>

</table>
 </div>
<h2>Last 5 matches:</h2>
<div class="wrapper">
<table>

<tr>
    <th>Home Team</th>
    <th>Guest Team</th>
    <th>Final Score</th>
    <th>Match Date</th>

</tr>
<?php
foreach(array_slice($matches,sizeof($matches)-6,5) as $match) : ?>
 <!-- $color = $match['home']['score'] > $match['away']['score'] ? 'style="background-color:green"' : 'style="background-color:red"';
 if($match['home']['score'] == $match['away']['score']) {
        $color = 'style="background-color:yellow"';
}  -->
<tr>

    <td>
        <?php $myId=$match['home']['id'];
              $myteam=($teamStorage->findOne(['id'=>"$myId"]));
              echo '<b>'.$myteam['name'].'<b>';
              
        ?>
    </td>
    <td>    <?php $myId1=$match['away']['id'];
              $myteam1=($teamStorage->findOne(['id'=>"$myId1"]));
              echo $myteam1['name'];
        ?></td>
    <td>
    <b>  <?php if($match['home']['score']>=0 && $match['away']['score']>=0) : ?>
           <?=$match['home']['score']." : ".$match['away']['score']?></b>
    <?php endif ?>
    </td>
    <td><?= $match['date']?></td>
</tr>
<?php endforeach ?>

</table>
</div>
</body>
</html>

