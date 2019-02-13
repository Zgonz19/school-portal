
<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="student"){
      header('Location: index.php?err=2');
    }

    $username = $_SESSION
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
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" "><font color="white">Student Portal</font></a>
        </div>

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><font color="white"><?php echo $_SESSION['sess_username'];?></font></a></li>
            <li><a href="logout.php"><font color="white">Logout</font></a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container homepage">

      <!-- student area -->
      <div class="row" style="">
         
          


            <div class = "row" style="">

              <!-- Homework assignments -->
              <div style="" class="col-md-4">
                <h3> Homework assignments</h3>



                  <table class="table table-bordered" style="background-color:white"> 
                  <?php
                  $servername = "localhost";
                  $username = "root";
                  $password = "";
                  $dbname = "project";
                  $student = $_SESSION['sess_username'];
                  $role = $_SESSION['sess_userrole'];
                  $user = $_SESSION['sess_username'];
                 // echo $role;
                  // Create connection
                  $conn = new mysqli($servername, $username, $password, $dbname);
                  if($role='user'){
                  //echo "Your mom";
                  $sql = "SELECT professor FROM students WHERE student='$user'";
                  $result = $conn->query($sql);
                  $row = mysqli_fetch_assoc($result);
                  $prof = $row['professor'];

                  if(isset($prof)){
                    $directory = "$prof/*";
                    //echo $directory;
                    //echo "asdfadslf";
                    $phpfiles = glob($directory);
                    foreach($phpfiles as $phpfile)
                    {
                    //echo "<tr><td><center><a href='".$row["link"]."'>".basename($row["link"])."</a></center></td></tr>";
                      echo "<tr><td><center><a href=$phpfile>".basename($phpfile)."</a></center></td></tr>";
                    //echo "<a href=$phpfile>".basename($phpfile)."</a><br><br>";
                    }
                  }
                  else {
                          echo '<tr><td><center>';
                          echo "0 results";
                          echo '</tr></td></center>';
                  }


                  }

                  if($role=='professor')
                  {

                    $directory= $user . '_homework/*';
                    $phpfiles = glob($directory);
                    foreach($phpfiles as $phpfile)
                    {
                    //echo "<a href=$phpfile>".basename($phpfile)."</a><br><br>";
                      echo "<tr><td><center><a href=$phpfile>".basename($phpfile)."</a></center></td></tr>";
                    }
                  }
                  ?>
                  </table>

              </div>

              <!-- class links --> 
              <div class="col-md-4" style = "">
                <h3>Class links</h3>

                      <table class="table table-bordered" style="background-color:white">  
                      <?php
                      $servername = "localhost";
                      $username = "root";
                      $password = "";
                      $dbname = "project";
                      $student = $_SESSION['sess_username'];
                      // Create connection
                      $conn = new mysqli($servername, $username, $password, $dbname);

                      $sql = "SELECT link FROM links WHERE username=(SELECT professor FROM students WHERE student='$student')";
                      $result = $conn->query($sql);

                      if (mysqli_num_rows($result) > 0) {
                          while($row = mysqli_fetch_assoc($result)) {
                              //echo "<a href='".$row["link"]."'>".basename($row["link"])."</a><br><br>";
                              echo "<tr><td><center><a href='".$row["link"]."'>".basename($row["link"])."</a></center></td></tr>";

                              //echo '<tr><td scope="row"><center>' .$row["subject"]. '</center></td><td><center>' .$row["grade"]. '</center></td></tr>';
                              
                          }
                      } else {
                          echo '<tr><td><center>';
                          echo "0 results";
                          echo '</tr></td></center>';
                      }


                      $conn->close();
                      ?>
                      </table>
              </div>

              <!-- view grades --> 
              <div class="col-md-4" style = "">
                <h3>View grades</h3>




                  <table class="table table-bordered" style="background-color:white">  
                  <?php
                  error_reporting(0);
                  $servername = "localhost";
                  $username = "root";
                  $password = "";
                  $dbname = "project";
                  $conn = new mysqli($servername, $username, $password, $dbname);
                  $name = $_SESSION['sess_username'];
                  $sql = "SELECT subject, grade from grades WHERE student='$name'";
                  $result = $conn->query($sql);


                  if (mysqli_num_rows($result) > 0) {
                      // output data of each row
                      while($row = mysqli_fetch_assoc($result)) {

                          //echo $row["subject"].": " . $row["grade"]."<br>";
                          echo '<tr><td scope="row"><center>' .$row["subject"]. '</center></td><td><center>' .$row["grade"]. '</center></td></tr>';
                      }
                  } else {

                      echo '<tr><td><center>';
                      echo "0 results";
                      echo '</tr></td></center>';
                  }


                  $conn->close();
                  ?>

                  </table>


              </div>
            </div>



            <div style="" class = "row">


            <!-- empty spacer in front of join class -->
            <div style="" class="col-md-2">

            </div>                        

            <!-- join a class -->
            <div style="" class="col-md-4">
              <h3>Join a class</h3>

              <div class = "">
                <div style="" class="">
                <form method="POST" action="">
                <input type="submit" name="name" class="btn btn-primary" value="Enroll In History"/>
                </form>
                </div> <label>or</label>
                <div class="" style = "">
                  <form method="POST" action="">
                  <input type="submit" name="name2" class="btn btn-primary" value="Enroll In English"/>
                  </form>
                </div>

              </div>
               <?php
                  $servername = "localhost";
                  $username = "root";
                  $password = "";
                  $dbname = "project";
                  // Create connection
                  $conn = new mysqli($servername, $username, $password, $dbname);


                 $userid = $_SESSION['sess_user_id'];
                 $nameofuser = $_SESSION['sess_username'];
                 $userrole = $_SESSION['sess_userrole'];
                 //$userid = $_SESSION['sess_user_id'];
                 $usergrade = "";
                 //echo "<h3>aaaa</h3>" . $userid;
                  if(isset($_POST['name']))
                  {   
                      $class = "history";
                      //$class2 = "english";
                      $classprofh = "hilford";
                      $sql = "INSERT INTO grades VALUES('$nameofuser','$class','$usergrade')";
                      $sql2 = "INSERT INTO students VALUES('$nameofuser','$classprofh')";
                      if ($conn->query($sql) === TRUE) 
                      {   
                          $conn->query($sql2);
                          echo "<label>";
                          echo "You have been enrolled";
                          echo "</label>";
                      } 
                      else 
                      {   
                          echo "<label>";
                          echo "Error: already enrolled";
                          echo "</label>";
                          //echo "Error: " . $sql . "<br>" . $conn->error;

                      }
                  }
                  if(isset($_POST['name2']))
                  {   
                      $class2 = "english";
                      $classprofe = "long";
                      $sql = "INSERT INTO grades VALUES('$nameofuser','$class2','$usergrade')";
                      $sql2 = "INSERT INTO students VALUES('$nameofuser','$classprofe')";
                      if ($conn->query($sql) === TRUE) 
                      {
                          //echo "<br><br><br>";
                          $conn->query($sql2);
                          echo "<label>";
                          echo "You have been enrolled";
                          echo "</label>";
                      } 
                      else 
                      {
                         // echo "Error: " . $sql . "<br>" . $conn->error;
                          //echo "<br><br><br>";
                          echo "<label>";
                          echo "Error: already enrolled";
                          echo "</label>";
                      }
                  }                  
              ?>
            </div>


            <!-- Upload assignments -->
            <div style = "" class="col-md-4">
              <h3>Upload assignments</h3>

                <form action="" method="post" enctype="multipart/form-data" style = "" class = "">
                
                  <div style = "">
                    <input type="file" name="myfile" id="fileToUpload" class="form-control"><br>
                    <input type="submit" name="submit" value="Upload File Now" class = "btn btn-primary" >
                  </div>

                </form>


                  <?php
                      error_reporting(0);

                      $servername = "localhost";
                      $username = "root";
                      $password = "";
                      $dbname = "project";
                      $conn = new mysqli($servername, $username, $password, $dbname);

                      $role= $_SESSION['sess_userrole'];   
                      $profname= $_SESSION['sess_username'];
                      
                      $currentDir = getcwd();

                      if($role =='professor'){
                      $uploadDirectory = "/$profname/";
                      $_POST['filename']=$profname;
                      $path = "C:/xampp/htdocs/finalproject/{$_POST['filename']}";
                      if (!file_exists('$path')) {
                        mkdir($path, 0777, true);
                      }}

                      if($role =='student')
                      {
                          $user=$_SESSION['sess_username'];
                          $sql = "SELECT professor FROM students WHERE student='$user'";
                          $result = $conn->query($sql);
                          $row = mysqli_fetch_assoc($result);
                          $prof = $row['professor'];

                          $uploadDirectory='/'. $prof . '_homework' . '/';
                          $_POST['filename']=$uploadDirectory;
                          $path = "C:/xampp/htdocs/finalproject/{$_POST['filename']}";
                          if (!file_exists('$path')) {
                            mkdir($path, 0777, true);
                      }}
                      

                      $errors = []; // Store all foreseen and unforseen errors here

                      $fileExtensions = ['jpeg','jpg','png','txt','pdf']; // Get all the file extensions

                      $fileName = $_FILES['myfile']['name'];
                      $fileSize = $_FILES['myfile']['size'];
                      $fileTmpName  = $_FILES['myfile']['tmp_name'];
                      $fileType = $_FILES['myfile']['type'];
                      $fileExtension = strtolower(end(explode('.',$fileName)));

                      $uploadPath = $currentDir . $uploadDirectory . basename($fileName); 

                      if (isset($_POST['submit'])) {

                          if (! in_array($fileExtension,$fileExtensions)) {
                              $errors[] = "This file extension is not allowed.";
                          }

                          if ($fileSize > 2000000) {
                              $errors[] = "This file is too big.";
                          }

                          if (empty($errors)) {
                              $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

                              if ($didUpload) {
                                  echo "The file " . basename($fileName) . " has been uploaded";
                              } else {
                                  echo "An error occurred somewhere. Try again or contact the admin";
                              }
                          } else {
                              foreach ($errors as $error) {
                                  echo $error . "These are the errors" . "\n";
                              }
                          }
                      }


                  ?>
            </div>
            </div>
          <div class="col-md-3"></div>
        </div>
    </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    </body>
</html>