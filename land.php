<!--
    ### STOCKHAWK ###
    land.php :
    Homepage where user lands after login. Shows the portfolio, lists all transaction.
    For admins, a notification is provided if there are any unallocated users.

-->

<?php require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'land_chart.html' ?>
  <body>
    <?php
        session_start();
        if(empty($_SESSION['login_user'])){
          header("location: index.php");exit();
        }
        require_once './config.php';
        $con = mysqli_connect($hostname, $username, $password, $databasename);  // Setup connection with the database.
        if (mysqli_connect_errno()) {
          header("location: error.html");exit();
        }


        $u=$_SESSION['login_user'];
        $query="select cash from user where uemail='$u'";                       //Get cash Balance of user
        $res=mysqli_query($con,$query);
        /*if(mysqli_errno($con)){
          header("location: error.php");exit();
        }*/
        $row=mysqli_fetch_array($res);
        $availableCash=$row[0];                                                 // Need to print this one.


        /*
              Method - updateCash
              Update the cash balance of the user according to money spent/received.

              Arguements -
                    $val            - Money Amount.
                    $type           - Type of transaction. 1-> Deposit. 2-> Withdraw.
                    $availableCash  - Current Cash balance of user.

              Returns -
                    Null
        */
        function updateCash($val,$type,$availableCash){
          //1-> deposit cash, 2-> withdraw cash
          if($type==1){
              $q1="update user set cash='$availableCash'+'$val' where uemail='$u'";
              $r1=mysqli_query($con,$q1);
              if(mysqli_errno($con)){
                header("location: error.php");exit();
              }else{
                header("location: land.php");exit();
              }
          }else{
              if($val>$availableCash){
                  echo '<script type="text/javascript">alert("Enough Cash not available.");</script>';
              }else{
                  $q1="update user set cash='$availableCash'-'$val' where uemail='$u'";
                  $r1=mysqli_query($con,$q1);
                  if(mysqli_errno($con)){
                    header("location: error.php");exit();
                  }else{
                    header("location: land.php");exit();
                  }
                }
            }
        }

     ?>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
      <?php require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'header_bar.html' ?>
      <?php require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sidebar.php' ?>
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid demo-content">
          <div class="demo-charts mdl-color--white mdl-shadow--8dp mdl-cell mdl-cell--12-col mdl-grid">
            <div class="mdl-card__supporting-text mdl-color-text--teal-500 mdl-cell mdl-cell--12-col mdl-grid">
              <h5>Portfolio Information<h5>
            </div>
            <div class="mdl-card__supporting-text mdl-color-text--black-500 mdl-cell mdl-cell--8-col ">
            <?php
                /*
                      Show User details.
                */
                $q1="select * from user where uemail='".$_SESSION['login_user']."'";
                $rs1=mysqli_query($con, $q1);
                if(mysqli_errno($con)){
                    header("location: error.html");exit();
                } ?>
              <?php
                while($row = mysqli_fetch_assoc($rs1)) {
                  echo '<h6><b> User ID:</b> '.$row["uemail"].'</h6>';
                  echo "<br /><b>User Name:</b> ".$row["uname"];
                  echo "<br /><b>Cash Balance:</b> $".$row["cash"];
                  echo "<br /><b>Allocated To:</b> ".$row["allotedto"];
                }
              ?>
              </div>
              <div class="mdl-card__media mdl-shadow--6dp">
                <img src="./images/user.jpg" height="180px" width="180px"></img>
              </div>

          </div>
          <div class="demo-charts mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--8-col">
            <div class="mdl-card__supporting-text mdl-color-text--teal-500">
              <h6>Your Stock Trends</h6>
            </div>
            <div class="mdl-grid demo-content" id = "show_graph">

            </div>
          </div>
          <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
            <div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">

              <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
                <h2 class="mdl-card__title-text">Updates</h2>
              </div>
              <?php
                  /*
                      Check if user is an admin. If yes, show notification if there are any unallocated users. Else, provide a news window.
                  */
                  if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='pm'){
                      $q1="select * from user where allotedto='no'";
                      $rs1=mysqli_query($con,$q1);
                      if(mysqli_errno($con)){
                          header("location: error.html");exit();
                      }
                      $cnt=mysqli_num_rows($rs1);
                      if($cnt!=0){
                          echo '<div class="mdl-card__supporting-text mdl-color-text--grey-600">
                            Welcome '.$_SESSION['login_user'].'. There is/are '.$cnt.' unallocated user(s).
                          </div>
                          <div class="mdl-card__actions mdl-card--border">
                            <a href="allocateuser.php" class="mdl-button mdl-js-button mdl-js-ripple-effect">Allocate</a>
                          </div>';
                      }else{
                          echo '<div class="mdl-card__supporting-text mdl-color-text--grey-600">
                            Welcome '.$_SESSION['login_user'].'. Here are the latest updates about stock market.
                          </div>
                          <div class="mdl-card__actions mdl-card--border">
                            <a href="http://economictimes.indiatimes.com/markets/stocks" target="_blank" class="mdl-button mdl-js-button mdl-js-ripple-effect">Read More</a>
                          </div>';
                      }
                  }else{
                      echo '<div class="mdl-card__supporting-text mdl-color-text--grey-600">
                        Welcome '.$_SESSION['login_user'].'. Here are the latest updates about stock market.
                      </div>
                      <div class="mdl-card__actions mdl-card--border">
                        <a href="http://economictimes.indiatimes.com/markets/stocks" target="_blank" class="mdl-button mdl-js-button mdl-js-ripple-effect">Read More</a>
                      </div>';
                  }
            ?>

            </div>
          </div>
            <div class="demo-charts mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">
              <div class="mdl-card__supporting-text mdl-color-text--black-500">
                <h6 class="mdl-color-text--teal-500">Your Transactions</h6>
                <?php
                /*
                      List all the transaction of user.
                */
                  $query="select * from utransaction where uemail='$u' order by tdate desc";
                  $res=mysqli_query($con,$query);
                  if($res==false){
                    echo mysqli_errno($con).": ".mysqli_error($con);
                    //header("location: error.php");exit();
                  }else{
                    $count=mysqli_num_rows($res);
                    if($count==0){
                      echo '<h6>sorry, no transaction found</h6>';
                    }else{
                  ?>
                      <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp mdl-cell mdl-cell--12">
                        <thead>
                          <tr>
                            <th class="mdl-data-table__cell--non-numeric">User Name</th>
                            <th class="mdl-data-table__cell--non-numeric">Manager Name</th>
                            <th class="mdl-data-table__cell--non-numeric">Transaction Date</th>
                            <th class="mdl-data-table__cell--non-numeric">Company</th>
                            <th class="mdl-data-table__cell--non-numeric">Transaction Type</th>
                            <th>Number of Stocks</th>
                            <th>Unit price</th>
                          </tr>
                        </thead>
                        <tbody>
                 <?php
                      while($row=mysqli_fetch_assoc($res)){
                        //print the transactions in place of the graphs. Please do it in a sexy way.
                        if ($row["ttype"] == 1)
                          echo '<tr>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["uemail"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["uPMemail"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["tdate"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["company"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">Buy</td>
                            <td>'.$row["quantity"].'</td>
                            <td>'.$row["price"].'</td>
                          </tr>';
                        else {
                          echo '<tr>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["uemail"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["uPMemail"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["tdate"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">'.$row["company"].'</td>
                            <td class="mdl-data-table__cell--non-numeric">Sell</td>
                            <td>'.$row["quantity"].'</td>
                            <td>'.$row["price"].'</td>
                          </tr>';
                        }
                      }
                      echo "</tbody>
                    </table>";
                    }
                  }
                 ?>

              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

      <!--<a href="https://github.com/google/material-design-lite/blob/master/templates/dashboard/" target="_blank" id="view-source" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast">View Source</a> -->
    <script src="../../material.min.js"></script>
  </body>
</html>
