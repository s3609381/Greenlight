<nav class="navbar navbar-default navbar-gl navbar-fixed-top" role="navigation">
  <div class="container">

    <!-- site name and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">GREENLIGHT</a>
      <!-- placeholder - replace with logo -->
    </div>

    <!-- all nav items within this div toggle for mobile -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="/about.php">ABOUT</a>
        </li>
        <li>
          <a href="/faq.php">FAQ</a>
        </li>
        <li>
          <a href="/search.php">SEARCH</a>
        </li>

        <!-- if logged in: -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['login_success']; ?><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li>
              <a href="/dashboard.php">Dashboard</a>
            </li>
            <li>
              <a href="">Settings</a>
            </li>
            <li>
              <a href="/logout.php">Log Out</a>
            </li>
          </ul>
        </li>

      </ul>
    </div>
    <!-- /navbar-collapse -->
  </div>
</nav>
