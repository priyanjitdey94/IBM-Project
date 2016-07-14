
<?php require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'header.html' ?>
<body>
  <script type="text/javascript">

  </script>
  <?php
      session_start();
      if(empty($_SESSION['login_user'])){
        header("location: index.php");
      }
      require_once './config.php';
      $con = mysqli_connect($hostname, $username, $password, $databasename);
      if (mysqli_connect_errno()) {
        //die("Failed to connect");
        header("location: error.html");
      }

      $u=$_SESSION['login_user'];
      $query="select msgbody,fromuser from message where touser='$u';";
      $res=mysqli_query($con,$query);
      if(mysqli_errno($con)){
        echo mysqli_errno($con).": ".mysqli_error($con);
        //header("location: error.php");exit();
      }

  ?>
  <style>
  .demo-charts:hover {
    box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
  }
  </style>
  <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <?php require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'header_bar.html' ?>
    <?php require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sidebar.php' ?>
    <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid demo-content">


            <div class="demo-separator mdl-cell--1-col"></div>
            <div class="demo-options mdl-card mdl-color--white-500 mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--3-col-tablet mdl-cell--12-col-desktop">
              <div class="mdl-card__supporting-text mdl-color-text--teal-500">
                <h3>Send Message</h3>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                <!-- Simple Textfield -->
              <form action="sentbox.php" method="post">
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" name="destination">
                  <label class="mdl-textfield__label" for="sample1">To...</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" name="message">
                  <label class="mdl-textfield__label" for="sample1">Message...</label>
                </div>

                <div class="mdl-card__actions mdl-card--border">
                  <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--teal-500">Send</button>
                  <div class="mdl-layout-spacer"></div>
                </div>
              </form>
              </div>
            </div>
          </div>
      </main>
            <!--<a href="https://github.com/google/material-design-lite/blob/master/templates/dashboard/" target="_blank" id="view-source" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast">View Source</a> -->
    <script src="../../material.min.js"></script>
  </body>
</html>
