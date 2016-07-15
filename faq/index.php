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

    <!-- favicons -->
  <link rel="shortcut icon" href="/images/favicon/favicon.ico">
  <link rel="icon" sizes="16x16 32x32 64x64" href="/images/favicon/favicon.ico">
  <link rel="icon" type="image/png" sizes="196x196" href="/images/favicon/favicon-192.png">
  <link rel="icon" type="image/png" sizes="160x160" href="/images/favicon/favicon-160.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96.png">
  <link rel="icon" type="image/png" sizes="64x64" href="/images/favicon/favicon-64.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16.png">
  <link rel="apple-touch-icon" href="/images/favicon/favicon-57.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/favicon-114.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/favicon-72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/favicon-144.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/favicon-60.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/favicon-120.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/favicon-76.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/favicon-152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/favicon-180.png">
  <meta name="msapplication-TileColor" content="#FFFFFF">
  <meta name="msapplication-TileImage" content="/images/favicon/favicon-144.png">
  <meta name="msapplication-config" content="/browserconfig.xml">

    <title>Greenlight - FAQ</title>

    <!-- css -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

    <!-- js / jquery -->
    <script src="/js/jquery-2.2.4.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/faq.js"></script>

</head>

<body>
    <!-- nav bar + header -->
    <?php
  
  if(!isset($_SESSION['user_name'])){ 
    include("../modules/nav.php");
  }
  else{
    include("../modules/nav-loggedin.php");
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
                        <h3 class="panel-title">How do I change my account details?</h3>
                    </div>
                    <div class="panel-body-hidden">
                        When you are logged in, go to the Navagation Bar at the top of the screen then to 'user name -> settings'. The dashboard also has an option button where settings can be changed.
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
        <?php include("../modules/footer.php") ?>

    </div>
    <!-- /contatiner -->
</body>

</html>