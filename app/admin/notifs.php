<?php
include('../functions.php');

if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: ../login.php");
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
    <title>Admin - saisons</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body class="body">
    <div class="container-fluid main">

        <div class="side_bar col-2">
            <div class="container">
                <div class="row">
                    <div class="profil">
                        <img src="../assets/maquettes/img/photo_profil.png" width="150px">
                        <?php if (isset($_SESSION['user'])) : ?>
                            <div class="type_user">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</div>
                            <?php echo $_SESSION['user']['username']; ?>
                        <?php endif ?>

                        <nav>
                            <ul>

                                <li><a href="/admin/home.php">HOME</a></li>
                                <div class="underline"></div>

                                <li><a href="/admin/notif.php">NOTIFICATION</a></li>
                                <div class="underline"></div>

                                <li><a href="/admin/events.php">CALENDRIER</a></li>
                                <div class="underline"> </div>

                                <li><a href="/admin/saisons.php">SAISONS</a></li>
                                <div class="underline"></div>

                                <li><a href="/admin/stats.php">STATISTIQUES</a></li>
                                <div class="underline"></div>

                                <!-- <li><a href="/admin/events.php">PARAMÈTRES</a></li>
                                <div class="underline"></div> -->
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
                        <a href="home.php?logout='1'">logout</a>
                        &nbsp; <a href="create_user.php"> + add user</a>
                    <?php endif ?>
                </div>
            </div>

            <!-- CONTENUE PAGE -->
            <div class=" row">
                <div class="main_notifs offset-1 col-9">
                    <h3>NOUVEAU MATCH</h3>
                    <div class="info_error">
                        <?php echo display_error(); ?>
                    </div>
                    <div class="add_events">

                        <div class="formulaire_notif col-6">
                            <form action="notifs.php" method="post">

                                <div class="input_notifs">
                                    <label>DATE</label>
                                    <input type="date" class="date_events" name="date_events" value="<?php echo $dateEvent; ?>">
                                </div>
                                <div class="input_notifs">
                                    <label>LIEU</label>
                                    <input type="text" class="lieu_events" name="lieu_events" value="<?php echo $lieuMatch; ?>">
                                </div>
                                <div class="input_notifs">
                                    <label>NOMBRE DE JOUEURS</label>
                                    <input type="number" class="dispo_events" name="dispo_events" value="<?php echo $dispoEvent; ?>">
                                </div>
                                <button type="submit" class="btn" name="date_btn">VALIDER</button>

                            </form>
                        </div>
                        <div class="dernier_match offset-1 col-5">
                            <?php
                            $row = "";
                            $stmt = $db->prepare("SELECT * FROM planning
                        ORDER BY events DESC");
                            if ($stmt->execute(array())) {
                                $row = $stmt->fetch()
                                ?>
                                <h4>DERNIER MATCH ENREGISTRÉ</h4>
                                <p> <span>Date du macth :</span> <?php echo $row[1]; ?> </p>
                                <p> <span>Lieu du macth :</span> <?php echo $row[2]; ?> </p>
                                <p> <span>Nombre de joueurs :</span> <?php echo $row[3]; ?> </p>
                            <?php
                            }

                            ?>
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