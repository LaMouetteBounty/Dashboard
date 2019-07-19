<?php
session_start();

// CONNEXION BADE DE DONNEE
$db = new PDO('mysql:host=localhost;dbname=multi_login', 'root', 'online@2017');

// DECLARATION DES VARIABLES
$username = "";
$email    = "";
$season    = "";
$errors   = array();
$dateEvent = "";
$lieuMatch = "";
$dispoEvent = "";
$jour_event = "";
$id_username = "";
$reponse = "";
$recupUser = "";
$place = "";
$sendMail = "";
$id_user = "";
$date_saison = "";
$selectMail = "";

// ACTIVER FUNCTION BOUTON
if (isset($_POST['register_btn'])) {
    register();
}

if (isset($_POST['season_btn'])) {
    createSeason();
}

if (isset($_POST['date_btn'])) {
    add_date();
}
if (isset($_POST['reponse_btn'])) {
    reponse();
}

if (isset($_POST['delete_btn'])) {
    delete();
}


// CREATION UTILISATEUR
function register()
{
    // DECLARATION VARIABLES
    global $db, $errors, $username, $email;

    // RECUPERER INPUT
    $username    =  $_POST['username'];
    $email       =  $_POST['email'];
    $password_1  =  $_POST['password_1'];
    $password_2  =  $_POST['password_2'];
    $date_saison  =  $_POST['date_saison'];

    // REQUETES SI DOUBLONS
    $sql_u = "SELECT * FROM users WHERE username='$username'";
    $sql_e = "SELECT * FROM users WHERE email='$email'";
    $res_u = $db->query($sql_u);
    $res_e = $db->query($sql_e);

    // ERREURS
    if (empty($username)) {
        array_push($errors, "Le champ identifiant est obligatoire");
    }
    if (empty($email)) {
        array_push($errors, "Le champ email est obligatoire");
    }
    if (empty($password_1)) {
        array_push($errors, "Le champ mot de passe est obligatoire");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "Les deux mots de passes ne correspondent pas");
    }

    // CONDITIONS EN CAS DE DOUBLONS
    if ($res_u->rowCount() > 0) {
        array_push($errors, "Identifiant déjà pris");
    } else if ($res_e->rowCount() > 0) {
        array_push($errors, "Email déjà pris");
    } else {
        // SI IL N'Y A PAS D'ERREURS :
        if (count($errors) == 0) {
            $password = md5($password_1); //CRYPTAGE DU MOT DE PASSE
            $user_type = $_POST['user_type'];
            $sql = "INSERT INTO users (username, email, user_type, password, date_saison) 
					VALUES(:username, :email, :user_type, :password, :date_saison)";
            $sth = $db->prepare($sql);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':user_type', $user_type, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->bindParam(':date_saison', $date_saison, PDO::PARAM_STR);
            $sth->execute();
            header('location: home.php');
        }
    }
}

// CREATION DES SAISONS
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
        header('location: saisons.php');
    }

    // LIASONS ID USER AVEC SAISONS
    $recupUserFor = "SELECT id FROM users";
    $recupUserFor = $db->prepare($recupUserFor);
    $recupUserFor->execute();
    $result = $recupUserFor->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $users => $recupUserFor) {
        $requete = "INSERT INTO users_saison (id_user, id_saison) VALUES(:id_user, :id_saison)";
        $sth_userFor = $db->prepare($requete);
        $sth_userFor->bindParam(':id_saison', $season, PDO::PARAM_STR);
        $sth_userFor->bindParam(':id_user', $recupUserFor['id'], PDO::PARAM_INT);
        $sth_userFor->execute();
    }
}

// AFFICHAGE DES ERREURS
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

// CONNECTION UTILISTATEUR
function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

// DECONNECTION
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}

// BOUTON CONNECTION
if (isset($_POST['login_btn'])) {
    login();
}

// LOGIN USER
function login()
{
    global $db, $username, $errors;

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        array_push($errors, "Le champ identifiant est obligatoire");
    }
    if (empty($password)) {
        array_push($errors, "Le mot de passe est obligatoire");
    }

    if (count($errors) == 0) {
        $password = md5($password);

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
        $sth = $db->prepare($sql);
        $sth->execute();
        $results = $db->query($sql);


        if ($results->rowCount() == 1) { // UTILISATEUR TROUVÉ
            // CHECK SI C'EST ADMIN OU USER
            $logged_in_user = $results->fetch(PDO::FETCH_ASSOC);

            if ($logged_in_user['user_type'] == 'admin') {

                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success']  = "Vous êtes bien connecté en tant que";
                header('location: admin/home.php');
            } else {
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success']  = "Connecté";

                header('location: index.php');
            }
        } else {
            array_push($errors, "Identifiant ou mot de passe incorrect !");
        }
    }
}

// CONNECTION ADMIN
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
    if (count($errors) == 0) {
        // Requete pour formulaire de creation dans la table planning
        $sql = "INSERT INTO planning (events, lieu, places_dispo) 
            VALUES('$dateEvent', '$lieuMatch', '$dispoEvent')";

        $sth = $db->prepare($sql);
        $sth->bindParam(':events', $dateEvent, PDO::PARAM_STR);
        $sth->bindParam(':lieu', $lieuMatch, PDO::PARAM_STR);
        $sth->bindParam(':places_dispo', $dispoEvent, PDO::PARAM_STR);
        $sth->execute();


//  ENVOI EMAIL
        $recupMail = "";
        $theRecupMail = $db->prepare("SELECT `email` FROM `users`");
        if ($theRecupMail->execute(array())) {
            $recupMail  = $theRecupMail->fetchAll(PDO::FETCH_ASSOC);
        }
        if ($recupMail != false) {
            $mailList = [];
            foreach ($recupMail as $row) {
                $mailList[] = $row['email'];
            }
        }
        $email_array = implode(',', $mailList);
        $to      =  $email_array;
        $subject = 'Nouveau match';

        $message = "";
        $message .= ' 
        <html>
        <body bgcolor="#e6e7e8">
        <h1 style="font-family:anton; text-align: center; padding-top: 70px; font-size: 2.5em; margin:0;"> NOUVEAU MATCH </h1>
        
        
        <p style="font-family: lato; font-size: 1em; text-align: center; padding-top: 40px; padding-bottom:70px;"> 
        Un nouveau match auras lieu ce week-end, <br> 
        vous avez la possibilité de vous porter volontaire <br>
        pour transporter les joueurs jusqu\'au lieu de rendez-vous. <br> 
        Pour répondre à cette demande, merci de vous connecter <br>
        sur votre compte à l\'adresse : <br>
        <a class="lien_site" href="http://localhost:8080/login.php" style="font-family: lato; font-weight: 700; font-size: 1.5em; text-align: center; text-decoration: none; color: black; ">www.bkc.fr</a>
        <br>
        <p style="font-family: lato; font-size: .6em; padding-top: 40px; padding-bottom:70px; padding-left:20px">Ceci est un email automatique, merci de ne pas répondre à cette adresse.</p>
        </p>
        </body>
        </html>
        ';
        // recupere adresse admin
        $recupMailAdmin = "";
        $theRecupMailAdmin = $db->prepare("SELECT `email` FROM `users` WHERE `user_type`='admin'");
        if ($theRecupMailAdmin->execute()){
            $recupMailAdmin = $theRecupMailAdmin->fetch();
        }
       
        $headers[] = 'From:' .$recupMailAdmin[0];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';

        mail($to, $subject, $message,  implode("\r\n", $headers));
        header('location: notifs.php');
    }
}

// Reponse user -> bdd
function reponse()
{
    global $db, $errors;

    $jour_event = $_POST['jour_event'];
    $id_username = $_POST['id_username'];
    $reponse = $_POST['reponse'];
    $place = $_POST['place'];
    $id_user = $_POST['id_user'];

    // Si déja repondu
    $sql_doublons = "SELECT * FROM response_parent WHERE jour_event='$jour_event' AND id_username='$id_username'";
    $res_doublons = $db->query($sql_doublons);

    if (empty($reponse)) {
        array_push($errors, "Réponse non défini");
    }

    //  Si pas déja répondu alors :
    if ($res_doublons->rowCount() > 0) {
        array_push($errors, "Déja repondu");
    } else {
        if (count($errors) == 0) {
            $sql = "INSERT INTO response_parent (jour_event, id_username, reponse, place, id_user) 
            VALUES('$jour_event', '$id_username', '$reponse', '$place', '$id_user ')";
            $sth = $db->prepare($sql);
            $sth->bindParam(':reponse', $reponse, PDO::PARAM_STR);
            $sth->bindParam(':place', $place, PDO::PARAM_STR);
            $sth->execute();
            $_SESSION['success']  = "New user successfully created!!";
            header('location: users_notifs.php');
        }
    }
}

//  SUPRESSION SAISON
// function delete()
// {
//     global $db, $season;
//     $season =  $_POST['date_saison'];

//     $delete = "DELETE FROM saison WHERE (date_saison =':date_saison')";

//     $sthDelete = $db->prepare($delete);
//     $sthDelete->bindParam(':date_saison', $season, PDO::PARAM_STR);
//     $sthDelete->execute();
//     header('location: saisons.php');
// }

// CHANGEMENT MOT DE PASSE
function updateMp ()
{
    global $db, $errors;



}