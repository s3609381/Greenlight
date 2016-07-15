<!DOCTYPE html>


<!-- a couple lines of code to redirect the user to the dashboard instead of the landing page if they are logged in -->
<?php
session_start();
if(isset($_SESSION['user_name'])){ //if login in session is not set
    header("Location: /dashboard");
}
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

    <title>Greenlight</title>

    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- js / jquery -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

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

        <!-- content -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Greenlight
                </h1>
            </div>
            <div class="col-md-12">
                <div class="panel panel-no-border">
                    <div class="panel-body">
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam
                            voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi
                            consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
                        <a href="/signup" class="btn btn-lg btn-success">Sign Up</a>
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
