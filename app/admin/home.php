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
                        <img src="../assets/maquettes/bkc_dashboard.png" width="150px">
                        <?php if (isset($_SESSION['user'])) : ?>
                            <div class="type_user">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</div>
                            <?php echo $_SESSION['user']['username']; ?>
                        <?php endif ?>

                        <nav>
                            <ul>

                                <li><a href="/admin/home.php">HOME</a></li>
                                <div class="underline"></div>

                                <li><a href="/admin/notifs.php">NOTIFICATION</a></li>
                                <div class="underline"></div>

                                <li><a href="/admin/events.php">CALENDRIER</a></li>
                                <div class="underline"> </div>

                                <li><a href="/admin/saisons.php">SAISONS</a></li>
                                <div class="underline"></div>

                                <li><a href="/admin/stats.php">STATISTIQUES</a></li>
                                <div class="underline"></div>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
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
                            <li class="logout"> <a href="create_user.php">INSCRIPTION</a></li>
                            <li class="logout"> <a href="home.php?logout='1'">DÉCONNEXION</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- CONTENUE -->
            <div class="container">
                <div class="row">
                    <!-- 3 CONTAINS TOP -->
                    <div class="contains_top">
                        <!-- NOMBRE D'INSCRIT SUR LA PLATEFORME -->
                        <div class="nb_inscrit col-6">
                            <h4> NOMBRE D'UTILISATEUR</h4>
                            <?php
                            $utilisateur = "";
                            $connect = $db->prepare("SELECT COUNT(*) FROM users");
                            if ($connect->execute(array())) {
                                $utilisateur = $connect->fetch();
                                echo $utilisateur[0];
                            }

                            ?>
                        </div>
                        <!-- DATE DU DERNIER MATCH -->
                        <div class="last_match offset-1 col-6">
                            <h4> Prochain match </h4>
                            <?php
                            $infoMatch = "";
                            $connectMatch = $db->prepare("SELECT * FROM planning
                        ORDER BY events DESC");
                            if ($connectMatch->execute(array())) {
                                $infoMatch = $connectMatch->fetch();
                                echo $infoMatch[1];
                                ?>
                                <span> à </span>
                                <?php
                                echo $infoMatch[2];
                            }
                            ?>
                        </div>
                        <!-- PARENT AYANT LE PLUS TRANSPORTE DE JOUEURS -->
                        <div class="top_un offset-1 col-6">
                            <h4> Top 1 </h4>



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