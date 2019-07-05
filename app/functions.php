<?php
session_start();

// connect to database
$db = new PDO('mysql:host=localhost;dbname=multi_login', 'root', 'online@2017');

// variable declaration
$username = "";
$email    = "";
$season    = "";
$errors   = array();
$dateEvent = "";
$lieuMatch = "";
$dispoEvent = "";

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
    register();
}

if (isset($_POST['season_btn'])) {
    createSeason();
}

if (isset($_POST['date_btn'])) {
    add_date();
    
    
}

// REGISTER USER
function register()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $username, $email;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $username    =  $_POST['username'];
    $email       =  $_POST['email'];
    $password_1  =  $_POST['password_1'];
    $password_2  =  $_POST['password_2'];

    $sql_u = "SELECT * FROM users WHERE username='$username'";
    $sql_e = "SELECT * FROM users WHERE email='$email'";
    $res_u = $db->query($sql_u);
    $res_e = $db->query($sql_e);

    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    if ($res_u->rowCount() > 0) {
        array_push($errors, "Sorry... username already taken");
    } else if ($res_e->rowCount() > 0) {
        array_push($errors, "Sorry... email already taken");
    } else {
        // register user if there are no errors in the form
        if (count($errors) == 0) {
            $password = md5($password_1); //encrypt the password before saving in the database
            $user_type = $_POST['user_type'];
            $sql = "INSERT INTO users (username, email, user_type, password) 
					  VALUES(:username, :email, :user_type, :password)";
            $sth = $db->prepare($sql);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':user_type', $user_type, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->execute();

            // $last_id = $db->lastInsertId();
            // var_dump($last_id);
            // $seasonUser  =  $_POST['season_user'];

            $_SESSION['success']  = "New user successfully created!!";
            header('location: home.php');
        }
    }
}

// return user array from their id
// function getUserById($id)
// {
// 	global $db;
// 	$query = "SELECT * FROM users WHERE id=" . $id;
// 	$result = mysqli_query($db, $query);
// 	$user = mysqli_fetch_assoc($result);
// 	echo'ici';
// 	return $user;
// }

function createSeason()
{

    global $db, $errors, $season;
    $season =  $_POST['date_saison'];

    if (empty($season)) {
        array_push($errors, "Une saisie est requise");
    }

    if (count($errors) == 0) {

        $sql = "INSERT INTO saison (date_saison) 
		VALUES('$season')";
        $sth = $db->prepare($sql);
        $sth->execute();
        $_SESSION['success']  = "New user successfully created!!";
        header('location: saisons.php');
    }
}

function display_error()
{
    global $errors;

    if (count($errors) > 0) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}

function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

// log user out if logout button clicked
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
    login();
}

// LOGIN USER
function login()
{
    global $db, $username, $errors;

    // grap form values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // make sure form is filled properly
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password = md5($password);

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
        $sth = $db->prepare($sql);
        $sth->execute();
        $results = $db->query($sql);

        // $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
        // $result = $db->query($query);

        if ($results->rowCount() == 1) { // user found
            // check if user is admin or user
            $logged_in_user = $results->fetch(PDO::FETCH_ASSOC);

            if ($logged_in_user['user_type'] == 'admin') {

                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success']  = "You are now logged in";
                header('location: admin/home.php');
            } else {
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success']  = "You are now logged in";

                header('location: index.php');
            }
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

function isAdmin()
{
    if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin') {
        return true;
    } else {
        return false;
    }
}


// Ajout date d'evenement et recuperation donnees
function add_date()
{

    // ajout de la date de l'evenement
    global $db, $errors;
    $dateEvent = $_POST['date_events'];
    $lieuMatch = $_POST['lieu_events'];
    $dispoEvent = $_POST['dispo_events'];

    if (empty($dateEvent)) {
        array_push($errors, "Date non défini");
    }
    if (empty($lieuMatch)) {
        array_push($errors, "Lieu non défini");
    }
    if (empty($dispoEvent)) {
        array_push($errors, "Nombre de joueurs non défini");
    }
    // $sql = "INSERT INTO saison (date_saison) 
    // VALUES('$season')";
    // $sth = $db->prepare($sql);
    // $sth->execute();
    if(count($errors) == 0) {
    // Requete insert mon formulaire dans la table planning
    $sql = "INSERT INTO planning (events, lieu, places_dispo) 
VALUES('$dateEvent', '$lieuMatch', '$dispoEvent')";
    $sth = $db->prepare($sql);
    $sth->bindParam(':events', $dateEvent, PDO::PARAM_STR);
    $sth->bindParam(':lieu', $lieuMatch, PDO::PARAM_STR);
    $sth->bindParam(':places_dispo', $dispoEvent, PDO::PARAM_STR);
    $sth->execute();
    $_SESSION['success']  = "New user successfully created!!";
    header('location: notifs.php');
    }

    // je vais sortir mes date d'evenement de la table planning
    // $stmt = $db->prepare("SELECT * FROM planning");
    // if ($stmt->execute(array())) {
    //     while ($row = $stmt->fetch()) {
    //         echo $row[0] . '<br>';
    //     }
    // }

    // Je vais chercher l'id de mes parent inscrit
    // $stmt = $db->query("SELECT * FROM Planning");
    // while ($row = $stmt->fetch()) {
    // echo $row['events']."<br />\n";
    // }

    // $stmt = $pdo->query("SELECT * FROM Planning");
    // $result = $db->query($id_user);

    // affichage d'un id
    // $test = $result->fetch_assoc();
    // echo $test["id"] . "<br>";

    // $verifdate = $_POST['date_events'];
    // var_dump($_POST['date_events']);

    // $requery = "INSERT INTO Reponse VALUES(" . $verifdate . ")";

    // mysqli_query($db, $requery);


    // while ($row = $result->fetch_assoc()) {
    // echo $row["id"] ."<br>";
    // }

}
