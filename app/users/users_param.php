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

                                <li><a href="users_notifs.php">NOTIFICATION</a></li>

                                <li><a href="users_events.php">CALENDRIER</a></li>

                                <li><a href="users_param.php">PARAMÈTRES</a></li>

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
                    <img src="../assets/maquettes/favicon_bkc.png" width="30px">
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
                <div class="main_params offset-1 col-9">
                    <h3>PARAMÈTRES</h3>
                    <div class="info_error">
                        <?php echo display_error(); ?>
                    </div>
                    <div class="content_params">
                    
                        <div class="info_user">
                            <h4> INFORMATION UTILISATEUR </h4>
                            <p> IDENTIFIANTS : <span>
                            <?php if (isset($_SESSION['user'])) : ?>
                        <?php echo $_SESSION['user']['username']; ?>
                    <?php endif ?>
                            </span></p>
                            <p> EMAIL : <span>                          
                                <?php if (isset($_SESSION['user'])) : ?>
                        <?php echo $_SESSION['user']['email']; ?>
                    <?php endif ?></span></p>
                            <p> SAISON : <span>
                            <?php if (isset($_SESSION['user'])) : ?>
                        <?php echo $_SESSION['user']['date_saison']; ?>
                    <?php endif ?>

                            </span></p>
                        </div>

                        

                        <div class="changeMp">
                            <div class="input_mp">
                                <h4>CHANGEMENT MOT DE PASSE</h4>
                                <label>ANCIEN MOT DE PASSE</label>
                                <input type="text" name="ancienMp" value="">
                            </div>

                            <div class="input_mp">
                                <label>NOUVEAU MOT DE PASSE</label>
                                <input type="text" name="newMp" value="">
                            </div>
                            <div class="input_mp">
                                <label>CONFIRMATION NOUVEAU MOT DE PASSE</label>
                                <input type="text" name="confNewMp" value="">
                            </div>
                            <button type="submit" class="btn" name="mp_btn">VALIDER</button>
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