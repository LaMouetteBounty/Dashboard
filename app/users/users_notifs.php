<?php
include('../functions.php');
if (!isLoggedIn()) {
    $_SESSION['msg'] = "Vous devez être connecté";
    header('location: login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="../assets/maquettes/favicon_bkc.png">
    <title>Prochain match</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body class="body">
    <div class="container-fluid main">

        <div class="side_bar col-2">
            <div class="container">
                <div class="row">
                    <div class="profil">
                        <img src="../assets/maquettes/bkc_dashboard.png" width="150px">
                        <?php if (isset($_SESSION['user'])) : ?>
                            <div class="type_user">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</div>
                            <?php echo $_SESSION['user']['username']; ?>
                        <?php endif ?>

                        <nav>
                            <ul>

                                <li><a href="../index.php">HOME</a></li>
                                <div class="underline"></div>

                                <li><a href="users_notifs.php">NOTIFICATION</a></li>
                                <div class="underline"></div>

                                <li><a href="users_events.php">CALENDRIER</a></li>
                                <div class="underline"> </div>

                                <li><a href="users_saisons.php">SAISONS</a></li>
                                <div class="underline"></div>

                                <li><a href="users_stats.php">STATISTIQUES</a></li>
                                <div class="underline"></div>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="infos_connec">
                    <?php if (isset($_SESSION['user'])) : ?>
                        <?php echo $_SESSION['user']['username']; ?>
                        (<?php echo ucfirst($_SESSION['user']['user_type']); ?>)
                    <?php endif ?>
                    <img src="../assets/maquettes/bkc_dashboard.png" width="40px">
                    <div class="dropdown inline-block">
                        <img src="../assets/img/icons/arrow.png" width="20px">
                        <ul class="dropdown-menu absolute hidden">
                            <li class="logout"> <a href="../index.php?logout='1'">DÉCONNEXION</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- CONTENUE PAGE -->
            <div class=" row">
                <div class="main_notifs offset-1 col-9">
                    <h3>TRANSPORT MATCH</h3>
                    <div class="info_error">
                        <?php echo display_error(); ?>
                    </div>
                    <div class="add_events">


                        <div class="prochain_match col-5">
                            <?php
                            $row = "";
                            $stmt = $db->prepare("SELECT * FROM planning
                        ORDER BY events DESC");
                            if ($stmt->execute(array())) {
                                $row = $stmt->fetch()
                                ?>
                                <h4>PROCHAIN MATCH</h4>
                                <p> <span>Date du match :</span> <?php echo $row[1]; ?> </p>
                                <p> <span>Lieu du match :</span> <?php echo $row[2]; ?> </p>
                                <p> <span>Nombre de joueurs :</span> <?php echo $row[3]; ?> </p>
                            <?php
                            }
                            ?>
                            <?php
                            $lastRow = "";
                            $session_id = $_SESSION['user']['username'];
                            $LastStmt = $db->prepare("SELECT * FROM response_parent
                            WHERE id_user='$session_id' AND jour_event ='$row[1]'");
                            if ($LastStmt->execute(array())) {
                                $lastRow = $LastStmt->fetch()
                                ?>
                                <h4>RÉPONSE ENREGISTRÉ</h4>
                                <p> <span>Date du match :</span> <?php echo $lastRow[1]; ?>  </p>
                                <p> <span>Réponse :</span> <?php echo $lastRow[3]; ?> </p>
                                <p> <span>Nombre de joueurs :</span> <?php echo $lastRow[4]; ?> </p>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="formulaire_notif_user offset-1 col-6">
                            <form action="users_notifs.php" method="post">

                                <div class="">
                                        <input type="text" class="jour_event" name="jour_event" value="<?php echo $row[1]; ?>" readonly>
                                        <input type="text" class="id_username" name="id_username" value="<?php echo $_SESSION['user']['username']; ?>" readonly>
                                        <input type="text" class="id_user" name="id_user" value="<?php echo $_SESSION['user']['id']; ?>" readonly>
                                    </div>
                                <div class="input_notifs">
                                    <h4> TRANSPORT DES JOUEURS</h4>
                                    <p> Pouvez-vous conduire des joueurs au match le <?php echo $row[1]; ?> à <?php echo $row[2]; ?>? </p>
                                    <label>RÉPONSE</label>
                                    <input type="text" class="reponse" name="reponse" value="<?php echo $reponse; ?>">
                                </div>
                                <div class="input_notifs">
                                    <label>NOMBRE DE PLACES DISPONIBLE</label>
                                    <input type="text" class="place" name="place" value="<?php echo $place; ?>">
                                </div>

                                <!-- <div class="input_notifs">
                                    <label>NOMBRE DE PLACES</label>
                                    <input type="text" class="reponse" name="reponse" value="">
                                </div> -->

                                <button type="submit" class="btn" name="reponse_btn">VALIDER</button>
                                
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>

</html>