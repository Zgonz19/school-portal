
<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin"){
      header('Location: index.php?err=2');
    }
    error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Portal</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background-color:#ebf2f9">
    
    <div class="navbar navbar-default navbar-fixed-top" role="navigation" style = "background-color:#336699">
      
      <div class="container" >
        <div class="navbar-header" >
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand"><font color="white">Admin Portal</font></a>
        </div>

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><font color="white">  <?php echo $_SESSION['sess_username'];?></font></a></li>
            <li><a href="logout.php"><font color="white">Logout</font></a></li>
          </ul>
        </div>
      </div>
      
    </div>

    <div class="container homepage">
      <div class="row">
         <div class="col-md-3"></div>
            <div class="col-md-6 welcome-page">
              
            </div>
                        <?php
                        // Include config file
                        require 'database-config.php';
                         
                        // Define variables and initialize with empty values
                        $username = $password = $confirm_password = $role ="";
                        $username_err = $password_err = $confirm_password_err = $role_err ="";
                         
                        // Processing form data when form is submitted
                        if($_SERVER["REQUEST_METHOD"] == "POST")
                        {
                         
                            // Validate username
                            if(empty(trim($_POST["username"]))){
                                $username_err = "Please enter a username.";
                            } 
                            else
                            {
                                // Prepare a select statement
                                $sql = "SELECT id FROM users WHERE username = ?";
                                
                                if($stmt = mysqli_prepare($link, $sql))
                                {
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "s", $param_username);
                                    
                                    // Set parameters
                                    $param_username = trim($_POST["username"]);
                                    $param_role = trim($_POST["role"]);
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt))
                                    {
                                        /* store result */
                                        mysqli_stmt_store_result($stmt);
                                        
                                        if(mysqli_stmt_num_rows($stmt) == 1)
                                        {
                                            $username_err = "This username is already taken.";
                                        } 
                                        else
                                        {
                                            $username = trim($_POST["username"]);
                                        }
                                    } 
                                    else
                                    {
                                        echo "Oops! Something went wrong. Please try again later.";
                                    }
                                }
                                 
                                // Close statement
                                mysqli_stmt_close($stmt);
                            }
                            
                            // Validate password
                            if(empty(trim($_POST["password"]))){
                                $password_err = "Please enter a password.";     
                            } elseif(strlen(trim($_POST["password"])) < 6){
                                $password_err = "Password must have atleast 6 characters.";
                            } else{
                                $password = trim($_POST["password"]);
                            }
                            
                            // Validate confirm password
                            if(empty(trim($_POST["confirm_password"])))
                            {
                                $confirm_password_err = "Please confirm password.";     
                            } 
                            else
                            {
                                $confirm_password = trim($_POST["confirm_password"]);
                                if(empty($password_err) && ($password != $confirm_password))
                                {
                                    $confirm_password_err = "Password did not match.";
                                }
                            }
                            

                            if(empty(trim($_POST["role"])))
                            {
                                $role_err = "Please enter a role.";  
                            }
                            else
                            {
                                $a = "student";
                                $b = "professor";
                                $c = "admin";
                                $role = trim($_POST["role"]);
                                if(empty($role_err) && ($role != $a) && ($role != $b) && ($role != $c)) 
                                {
                                  $role_err = "incorrect input: only 'student' 'admin' and 'professor' are valid";
                                }
                            }

                            // Check input errors before inserting in database
                            if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)){
                                
                                // Prepare an insert statement
                                $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
                                 
                                if($stmt = mysqli_prepare($link, $sql)){
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_role);
                                    
                                    // Set parameters
                                    $param_username = $username;
                                    // Creates a password hash
                                    $param_password = $password ;
                                    $param_role = $role; 
                                    
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        // Redirect to login page
                                       //header("location: login.php");
                                    } else{
                                        echo "Something went wrong. Please try again later.";
                                    }
                                }
                                 
                                // Close statement
                                mysqli_stmt_close($stmt);
                            }
                            
                            // Close connection
                            mysqli_close($link);
                        }
                        ?>   
              <div class="container fill"> 
                <div class="pull-left" style="">
                    <p> <h3>Add a new account </h3></p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label class="pull-left">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err; ?></span>
                        </div>    

                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label class="pull-left">Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <label class="pull-left">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                            <label class="pull-left">Role</label>
                            <input type="text" name="role" class="form-control" value="<?php echo $role; ?>">
                            <span class="help-block"><?php echo $role_err; ?></span>
                        </div>          

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <input type="reset" class="btn btn-default" value="Reset">
                        </div>
                        <!-- <p>Already have an account? <a href="login.php">Login here</a>.</p> -->
                    </form>
                </div>

                <div class="col-xs-offset-1" style = " float: left">
                  
                    <table class="table table-bordered" style="background-color:white">                     
                        <div>
                            <thead> 
                              
                                <tr>
                                  <h3> Current users </h3>
                                  <font size="1" color="#ebf2f9">___________________________________________________________________________________</font>
                                  <th><center>id</center></th>
                                  <th><center>username</center></th>
                                  <th><center>role</center></th>
                                </tr>
                            </thead>
                            <tbody>
                          <?php 
                          //require 'database-config.php';

                          $sql = "SELECT id, username, role FROM users";
                          $result = $conn->query($sql);
                          if (mysqli_num_rows($result) > 0) 
                          {
                            // output data of each row
                            while($row = mysqli_fetch_assoc($result)){
                              echo '<tr>
                                    <td scope="row"><center>' . $row["id"]. '</center></td>
                                                <td><center>' . $row["username"] .'</center></td>
                                                
                                                <td><center> ' .$row["role"] .'</center></td>
                                                </tr>';
                            }
                          } 
                          else {
                            echo "0 results";
                          } 
                          ?>
                            </tbody>
                        </div>
                    </table>
                </div>
              </div>

          <div class="col-md-3"></div>
            <?php //<div style="">?> 
            <h3> Delete user from database </h3>
              <?php
              error_reporting(0);

              // php code to Delete data from mysql database  background-color:lightblue

              if(isset($_POST['delete']))
              {
                  $hostname = "localhost";
                  $username = "root";
                  $password = "";
                  $databaseName = "project";
                  
                  // get id to delete
                  $id = $_POST['id'];
                  
                  // connect to mysql
                  $connect = mysqli_connect($hostname, $username, $password, $databaseName);
                  
                  // mysql delete query 
                  $query = "DELETE FROM `users` WHERE `id` = $id";
                  
                  $result = mysqli_query($connect, $query);
                  
                  if($result)
                  {
                      echo "<meta http-equiv='refresh' content='0'>";
                      echo 'Data Deleted';
                  }else{
                      echo 'Data Not Deleted';
                  }
                  mysqli_close($connect);
              }

              ?>            
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/style.css" rel="stylesheet">
            <form action="" method="post" ><label>
              Enter ID of user you wish to delete:&nbsp;</label><input type="text" name="id" required>
            <input type="submit" name="delete" value="Delete User"  class="btn btn-danger">
            </form>
          </div><br><br>



          <div style="" class="col-md-2">
          </div>

          <div style="" class="col-md-5">
            <h3> Admin log activity </h3>
                  <table class="table table-bordered" style="background-color:white"> 
                  <?php
                  $servername = "localhost";
                  $username = "root";
                  $password = "";
                  $dbname = "project";

                  // Create connection
                  $conn = new mysqli($servername, $username, $password, $dbname);
                  // Check connection
                  if ($conn->connect_error) {
                      die("Connection failed: " . $conn->connect_error);
                  } 

                  $sql = "SELECT log FROM adminlog";
                  $result = $conn->query($sql);

                  if (mysqli_num_rows($result) > 0) {
                      // output data of each row
                      while($row = mysqli_fetch_assoc($result)) {
                          echo '<tr><td>';
                          echo $row["log"]. nl2br("\r\n");
                          echo '</td></tr>';
                      }
                  } else {
                      echo '<tr><td>';
                      echo "0 results";
                      echo '</td></tr>';
                  }


                  $conn->close();

                  ?>
                  </table>


          </div>

        </div>
    </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    </body>
</html>

