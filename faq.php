<!DOCTYPE html>

<?php
session_start();
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Greenlight">
    <meta name="author" content=";DROP TABLE team - RMIT University">

    <!-- icons (.png for apple and .ico for favicon) -->
    <link rel="apple-touch-icon" href="">
    <link rel="shortcut icon" href="">

    <title>Greenlight - FAQ</title>

    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- js / jquery -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/faq.js"></script>

</head>

<body>
    <!-- nav bar + header -->
    <?php
  
  if(!isset($_SESSION['user_name'])){ 
    include("modules/nav.php");
  }
  else{
    include("modules/nav-loggedin.php");
  }
  
  ?>

    <!-- content and footer -->
    <div class="container">

        <!-- misc test content -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    FAQ
                </h1>
            </div>
            <div class="col-md-12">


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Does my greenlight have to be public?</h3>
                    </div>
                    <div class="panel-body-hidden">
                        No, you can have a public greenlight that other people can see without a link or you can select your light to be a private greenlight, where you are the only person who sees it. Optionally your private greenlight can be share via a link with other people.
                        Only people who have this private link can see the greenlight.
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Question</h3>
                    </div>
                    <div class="panel-body-hidden">
                        Answer
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Question</h3>
                    </div>
                    <div class="panel-body-hidden">
                        Answer
                    </div>
                </div>

            </div>
        </div>
        <hr>

        <!-- footer -->
        <?php include("modules/footer.php") ?>

    </div>
    <!-- /contatiner -->
</body>

</html>
