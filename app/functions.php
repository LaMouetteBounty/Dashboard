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
// $LastUsers = NULL;
// $LastSeason = NULL;

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

if (isset($_POST['delete_btn'])) {
    delete();
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

    global $db, $errors, $season, $users;
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
    // $recupSaisonFor = "SELECT id FROM saison";
    // $recupSaisonFor = $db->prepare($recupSaisonFor);
    // $recupSaisonFor->execute();
    // $result = $recupSaisonFor->fetchAll(PDO::FETCH_ASSOC);

    $recupUserFor = "SELECT id FROM users";
    $recupUserFor = $db->prepare($recupUserFor);
    $recupUserFor->execute();
    $result = $recupUserFor->fetchAll(PDO::FETCH_ASSOC);



    // output data of each row
    foreach ($result as $users => $recupUserFor) {
        $requete = "INSERT INTO users_saison (id_user, id_saison) VALUES(:id_user, :id_saison)";
        $sth_userFor = $db->prepare($requete);
        $sth_userFor->bindParam(':id_saison', $season, PDO::PARAM_STR);
        $sth_userFor->bindParam(':id_user', $recupUserFor['id'], PDO::PARAM_INT);
        $sth_userFor->execute();
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
    global $db, $errors, $result, $users;
    $dateEvent = $_POST['date_events'];
    $lieuMatch = $_POST['lieu_events'];
    $dispoEvent = $_POST['dispo_events'];
    $noReponse = "";

    if (empty($dateEvent)) {
        array_push($errors, "Date non défini");
    }
    if (empty($lieuMatch)) {
        array_push($errors, "Lieu non défini");
    }
    if (empty($dispoEvent)) {
        array_push($errors, "Nombre de joueurs non défini");
    }

    if (count($errors) == 0) {
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

    $recupUser = "SELECT id FROM users";
    $recupUser = $db->prepare($recupUser);
    $recupUser->execute();
    $result = $recupUser->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $users => $recupUser) {
        $requete = "INSERT INTO response_parent (jour_event, id_user, reponse) VALUES(:jour_event, :id_user, :reponse)";
        $sth_user = $db->prepare($requete);
        $sth_user->bindParam(':jour_event', $dateEvent, PDO::PARAM_STR);
        $sth_user->bindParam(':id_user', $recupUser['id'], PDO::PARAM_INT);
        $sth_user->bindParam(':reponse', $noReponse, PDO::PARAM_STR);
        $sth_user->execute();
    }
}




function delete()
{

    global $db, $season;
    $season =  $_POST['date_saison'];
// $deleteSaison = isset($_POST['date_saison']) ? $_POST['date_saison'] : false;
    // if (count($errors) == 0) {

// if($deleteSaison){

        $delete = "DELETE FROM saison WHERE (date_saison = :date_saison)";

        $sthDelete = $db->prepare($delete);
        $sthDelete->execute();
        
        header('location: saisons.php');
    //  }
}
