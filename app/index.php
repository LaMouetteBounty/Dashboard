<?php
include('functions.php');
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
    <link rel="icon" type="image/png" href="/assets/maquettes/favicon_bkc.png">
    <title>Accueil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>

<body class="body">
    <div class="container-fluid main">

        <div class="side_bar_home col-2">
            <div class="container">
                <div class="row">
                    <div class="profil">
                        <img src="../assets/maquettes/bkc_dashboard.png" width="150px">
                        <?php if (isset($_SESSION['user'])) : ?>
                            <div class="type_user">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</div>
                            <?php echo $_SESSION['user']['username']; ?>
                        <?php endif ?>

                        <nav class="effect-4">
                            <ul>

                                <li><a href="index.php">HOME</a></li>

                                <li><a href="/users/users_notifs.php">NOTIFICATION</a></li>

                                <li><a href="/users/users_events.php">CALENDRIER</a></li>

                                <li><a href="/users/users_param.php">PARAMÈTRES</a></li>

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
                            <li class="logout"> <a href="index.php?logout='1'">DÉCONNEXION</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- CONTENUE -->
            <div class="container">
                <div class="row">
                    <!-- 3 CONTAINS TOP -->
                    <div class="contains_top">

                        <!-- TOTAL DES MATCHS -->
                        <div class="total_match">
                            <h4>TOTAL DES MATCHS</h4>
                            <?php
                            $totalMatch = "";
                            $theMatch = $db->prepare("SELECT COUNT(*) FROM `planning` WHERE `events` LIKE '2019%'");
                            if ($theMatch->execute(array())) {
                                $totalMatch = $theMatch->fetch();
                                echo $totalMatch[0];
                            }
                            ?>
                        </div>

                        <!-- DATE DU DERNIER MATCH -->
                        <div class="last_match">
                            <h4>PROCHAIN MATCH</h4>
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
                        <!-- NOMBRE D'INSCRIT SUR LA PLATEFORME -->
                        <div class="nb_inscrit">
                        <h4>TOTAL DES TRAJETS</h4>
                            <?php

                            $infoCompte = "";
                            $connectCompteur = $db->prepare("SELECT COUNT(*) FROM response_parent WHERE reponse ='oui'");
                            if ($connectCompteur->execute(array())) {
                                $infoCompte  = $connectCompteur->fetch();
                                echo $infoCompte[0];
                            }
                            ?>
                        </div>
                        <!-- TOTAL DE REPONSES POSITIVES -->
                        <div class="top_un">
                            <h4>TOTAL DES TRAJETS</h4>

                            <?php
                            $name = $_SESSION['user']['username'];

                            $infoCompte = "";
                            $connectCompteur = $db->prepare("SELECT COUNT(*) FROM response_parent WHERE reponse ='oui' AND id_username='$name' ");
                            if ($connectCompteur->execute(array())) {
                                $infoCompte = $connectCompteur->fetch();
                                echo $infoCompte[0];
                            }
                            ?>

                        </div>
                    </div>
                </div>
                <div class="row contains_graph">
                    <div class="tableau_top col-3">
                        <?php
                        $resultatDate = "";
                        $theDate = $db->prepare("SELECT jour_event FROM response_parent GROUP BY jour_event DESC ");
                        if ($theDate->execute(array())) {
                            $resultatDate  = $theDate->fetch();
                            $theDate = $resultatDate["jour_event"];
                        }

                        $volontaire = "";
                        $theVolontaire = $db->prepare("SELECT * FROM response_parent WHERE reponse='oui' AND jour_event='$resultatDate[0]' ");
                        if ($theVolontaire->execute(array())) {
                            $volontaire  = $theVolontaire->rowCount();
                        }  ?>
                        <div class="bg_tableau">
                            <h4>VOLONTAIRES POUR LE <?php echo $resultatDate[0]; ?></h4>
                            <ul>
                                <li class="entete">NOM <span> PLACES</span></li>
                                <div class="underline"> </div>
                                <?php
                                //boucle pour recuperer plusieurs lignes
                                if ($volontaire > 0) {
                                    while ($volontaire = $theVolontaire->fetch()) {
                                        ?>
                                        <li><?php echo $volontaire[2]; ?> <span><?php echo $volontaire[4]; ?> </span></li>
                                        <div class="underline"> </div>
                                    <?php
                                    }
                                }
                                ?>
                                <?php
                                $totalPlace = "";
                                $theTotalPlace = $db->prepare("SELECT SUM(`place`) FROM `response_parent` WHERE `jour_event`='$resultatDate[0]' ");
                                if ($theTotalPlace->execute(array())) {
                                    $totalPlace  = $theTotalPlace->fetch();
                                }  ?>

                                <li class="entete">TOTAL <span><?php echo $totalPlace[0]; ?></span></li>

                                <?php
                                $placeNecessaire = "";
                                $thePlace = $db->prepare("SELECT `places_dispo` FROM planning ORDER BY `events` DESC");
                                if ($thePlace->execute(array())) {
                                    $placeNecessaire  = $thePlace->fetch();
                                }  ?>
                                <li class="nb_joueur">(NOMBRE DE JOUEUR PRÉVU : <?php echo $placeNecessaire[0]; ?>)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="graph_bar col-9">
                        <div class="bg_graph_bar">
                            <h4> TRAJETS EFFECTUÉS PAR PERSONNE </h4>
                            <?php
                            $total = "";
                            $theTotal = $db->prepare("SELECT COUNT(`id_user`) AS total ,`id_username` FROM `response_parent` GROUP BY `id_username` ORDER BY `total` DESC");
                            if ($theTotal->execute(array())) {
                                $total  = $theTotal->fetchAll(PDO::FETCH_ASSOC);
                            }

                            if ($total != false) {
                                $userList = [];
                                $totalList = [];
                                foreach ($total as $row) {
                                    $userList[] = $row['id_username'];
                                    $totalList[] = $row['total'];
                                }
                            }
                            ?>

                            <canvas id="myChart"></canvas>

                            <!-- TABLEAU DES PARENTS QUI FONT LE PLUS DE TRAJET X=USERNAME Y=TOTAL DE TRAJET -->
                            <script>
                                var userList = '<?php echo implode(',', $userList); ?>';
                                var totalList = '<?php echo implode(',', $totalList); ?>';
                                userList = userList.split(',');
                                totalList = totalList.split(',');

                                var ctx = document.querySelector('#myChart');
                                var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: userList,
                                        datasets: [{
                                            label: 'Nombre de trajet effectué',
                                            data: totalList,
                                            backgroundColor: [
                                                'rgb(242, 105, 33)',
                                                'rgb(249, 164, 63)',
                                                'rgb(252, 180, 20)',
                                                'rgb(253, 187, 79)',
                                                'rgb(242, 105, 33)',
                                                'rgb(255, 213, 81)'
                                            ],
                                            borderColor: [
                                                'rgb(88, 88, 91)',
                                                'rgb(88, 88, 91)',
                                                'rgb(88, 88, 91)',
                                                'rgb(88, 88, 91)',
                                                'rgb(88, 88, 91)',
                                                'rgb(88, 88, 91)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero: true
                                                }
                                            }]
                                        }
                                    }
                                });
                            </script>
                        </div>
                        <div class="contains_camembert row">
                            <div class="camembert_un">
                                <?php
                                $totalOui = "";
                                $theTotalOui = $db->prepare("SELECT COUNT(*) AS total FROM `response_parent` WHERE `reponse`='oui'");
                                if ($theTotalOui->execute(array())) {
                                    $totalOui  = $theTotalOui->fetch();
                                }

                                $totalNon = "";
                                $theTotalNon = $db->prepare("SELECT COUNT(*) AS totalnon FROM `response_parent` WHERE `reponse`='non';");
                                if ($theTotalNon->execute(array())) {
                                    $totalNon  = $theTotalNon->fetch();
                                }
                                ?>
                                <h4> TOTAL DES RÉPONSES </h4>
                                <canvas id="myPieChart"></canvas>
                                <script>
                                    var graph = document.querySelector('#myPieChart');
                                    var myPieChart = new Chart(graph, {
                                        type: 'pie',
                                        data: {
                                            labels: ['Oui', 'Non'],
                                            datasets: [{
                                                label: 'My First dataset',
                                                data: [<?php echo $totalOui["total"]; ?>, <?php echo $totalNon["totalnon"]; ?>],
                                                backgroundColor: [
                                                    'rgb(242, 105, 33)',
                                                    'rgb(255, 213, 81)'
                                                ],

                                                borderWidth: 1
                                            }]
                                        },

                                        // Configuration options go here
                                        options: {}
                                    });
                                </script>
                            </div>
                            <!-- DEUXIEME CAMEMBERT -->
                            <div class="camembert_deux">
                                <?php
                                $trajetVille = "";
                                $theTrajetVille = $db->prepare("SELECT COUNT(`id`) AS totalLieu ,`lieu` FROM `planning` GROUP BY `lieu`");
                                if ($theTrajetVille->execute(array())) {
                                    $trajetVille  = $theTrajetVille->fetchAll(PDO::FETCH_ASSOC);
                                }
                                if ($trajetVille != false) {
                                    $lieuList = [];
                                    $totalLieu = [];
                                    foreach ($trajetVille as $row) {
                                        $lieuList[] = $row['lieu'];
                                        $totalLieu[] = $row['totalLieu'];
                                    }
                                }
                                ?>
                                <h4> MATCHS PAR VILLE </h4>
                                <canvas id="myPieChartTwo"></canvas>
                                <script>
                                    var lieuList = '<?php echo implode(',', $lieuList); ?>';
                                    var totalLieu = '<?php echo implode(',', $totalLieu); ?>';
                                    lieuList = lieuList.split(',');
                                    totalLieu = totalLieu.split(',');

                                    var graphTwo = document.querySelector('#myPieChartTwo');
                                    var myPieChart = new Chart(graphTwo, {
                                        type: 'pie',
                                        data: {
                                            labels: lieuList,
                                            datasets: [{
                                                label: 'My First dataset',
                                                data: totalLieu,
                                                backgroundColor: [
                                                    'rgb(242, 105, 33)',
                                                    'rgb(249, 164, 63)',
                                                    'rgb(252, 180, 20)',
                                                    'rgb(253, 187, 79)',
                                                    'rgb(242, 105, 33)',
                                                    'rgb(255, 213, 81)'
                                                ],

                                                borderWidth: 1
                                            }]
                                        },

                                        // Configuration options go here
                                        options: {}
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer>
                <p> © Léa Boutry </p>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- <script src="script_admin.js"></script> -->
</body>

</html>