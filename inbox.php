<!--
    ### STOCKHAWK ###
    inbox.php :
    Provides functionality to send messages and keep track of all received messages.

-->

<?php require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'header.html' ?>
<body>
  <script type="text/javascript">

  </script>
  <?php
      /*
          Check if the existing session expired or not. If expired, redirect to index.php.
      */
      session_start();
      if(empty($_SESSION['login_user'])){
        header("location: index.php");exit();
      }
      require_once './config.php';
      $con = mysqli_connect($hostname, $username, $password, $databasename);    // Setup connection with the database.
      if (mysqli_connect_errno()) {
        //die("Failed to connect");
        header("location: error.html");
      }

      //Error Checking
      if(isset($_GET['Message'])){
        $msg=$_GET['Message'];
        unset($_GET['Message']);
        echo '<script type="text/javascript">alert("'.$msg.'");</script>';
      }

      //Send Message
      if($_SERVER["REQUEST_METHOD"]=="POST"){
        $to=$_POST['destination'];
        $msg=$_POST['message'];
        sendMsg($con,$to,$msg);
      }
      function sendMsg($con,$to,$msg){
          $q1="select * from user where uemail='$to'";
          $rs1=mysqli_query($con,$q1);
          $cnt=mysqli_num_rows($rs1);
          if($cnt==0){
            $m="No such user exists.";
            header("location:inbox.php?Message=".urlencode($m));exit();
          }else{
            $u=$_SESSION['login_user'];
            $q2="insert into message values('$u','$to','$msg')";
            $rs2=mysqli_query($con,$q2);
            if(mysqli_errno($con)){
              header("location:error.php");exit();
            }else{
              $m="Message sent successfully.";
              header("location:inbox.php?Message=".urlencode($m));exit();
            }

          }
      }
      /*
          List all the messages received by this user.
      */
      $u=$_SESSION['login_user'];
      $query="select msgbody,fromuser from message where touser='$u';";
      $res=mysqli_query($con,$query);
      if(mysqli_errno($con)){
        header("location: error.php");exit();
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
          <div class="demo-cards mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-grid mdl-grid--no-spacing">
               <!--<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">-->
                 <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
                   <h2 class="mdl-card__title-text">Message Updates</h2>
                 </div>
                  <?php
                  $row = 0;
                  while ($row = mysqli_fetch_assoc($res)) {
                    echo '
                    <div class="demo-charts mdl-color--white mdl-shadow--8dp mdl-cell mdl-cell--12-col mdl-grid">
                      <div class="mdl-card__supporting-text mdl-color-text--black-500">From: '.$row['fromuser'].'
                       <br />'.
                       $row['msgbody'].'
                     </div>
                     <br />
                     <hr />
                   </div>';
                  }
                  ?>
               </div>

            <div class="demo-separator mdl-cell--1-col"></div>
            <div class="demo-options mdl-card mdl-color--white-500 mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--3-col-tablet mdl-cell--12-col-desktop">
              <div class="mdl-card__supporting-text mdl-color-text--teal-500">
                <h3>Send Message</h3>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                <!-- Simple Textfield -->
              <form method="post" name="send_message">
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" id='destination' name="destination">
                  <label class="mdl-textfield__label" for="sample1">To...</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield">
                  <input class="mdl-textfield__input" type="text" id='message' name="message">
                  <label class="mdl-textfield__label" for="sample1">Message...</label>
                </div>

                <div class="mdl-card__actions mdl-card--border">
                  <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--teal-500" >Send</button>
                  <div class="mdl-layout-spacer"></div>
                </div>
              </form>
              </div>
            </div>
          </div>
          <?php

          ?>
          <script>
              function sendMessage(){
                  var receiver= document.getElementById('destination').value;
                  var msg=document.getElementById('message').value;
                  receiver=receiver.trim();
                  msg=msg.trim();
                  if(receiver==''){
                      document.getElementById('destination').value="";
                      document.getElementById('message').value="";
                      var data="Please provide the address of a receiver.";
                      alert(data);
                  }else{
                    document.send_message.submit();
                  }
              }
          </script>
      </main>
            <!--<a href="https://github.com/google/material-design-lite/blob/master/templates/dashboard/" target="_blank" id="view-source" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast">View Source</a> -->
    <script src="../../material.min.js"></script>
  </body>
</html>
