
<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="professor"){
      header('Location: index.php?err=2');
    }
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
          <a class="navbar-brand" ><font color="white">Professor Portal</font></a>
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
                    
      <div class = "row">
        <div style="" class="col-md-3">
          <h3>Submit a grade</h3>

                  <form method="post" action="">
                    <div>
                      <label class="pull-left">Student Name: </label>
                      <input type="text" name="stuname" class="form-control">
                    </div><br>


                    <div>
                      <label class="pull-left">Grade: </label>
                      <input type="text" name="grade" class="form-control">
                    </div><br>


                    <div>
                      <label class="pull-left">Class: </label>
                      <input type="text" name="class" class="form-control"><br>
                    </div>

                    <input type="submit" class="btn btn-primary" value = "Submit Grade">
                  </form>
                

                    <?php
                    error_reporting(0);
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $stuname = $_REQUEST['stuname'];
                        $grade = $_REQUEST['grade'];
                        $class = $_REQUEST['class'];
                    }

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


                   if(isset($grade)&&isset($class)){ 
                      $sql = "UPDATE Grades
                          SET grade = '$grade'
                          WHERE student = '$stuname' AND subject='$class';";

                      if ($conn->query($sql) === TRUE) {
                          echo "<label> Grade updated successfully </label>";
                          $sql = "INSERT INTO adminlog 
                                  VALUES('Student $stuname had their $class grade changed to a $grade')";
                          $conn->query($sql);
                      } else {
                          echo "Error: " . $sql . "<br>" . $conn->error;
                      }
                  }
                    ?>
        </div>

        <div style="" class="col-md-1">
        </div>

        <div style="" class="col-md-3">
          <h3>Submitted assignments</h3>

                  <table class="table table-bordered" style="background-color:white"> 
                  <?php
                  $servername = "localhost";
                  $username = "root";
                  $password = "";
                  $dbname = "project";
                  $student = $_SESSION['sess_username'];
                  $role = $_SESSION['sess_userrole'];
                  $user = $_SESSION['sess_username'];
                  // Create connection
                  $conn = new mysqli($servername, $username, $password, $dbname);
                  if($role=='user'){
                  //echo "Your mom";
                  $sql = "SELECT professor FROM students WHERE student='$user'";
                  $result = $conn->query($sql);
                  $row = mysqli_fetch_assoc($result);
                  $prof = $row['professor'];
                  $directory = "$prof/*";
                  $phpfiles = glob($directory);
                  foreach($phpfiles as $phpfile)
                  {
                    echo "<tr><td><center><a href=$phpfile>".basename($phpfile)."</a></center></td></tr>";
                    //echo "<a href=$phpfile>".basename($phpfile)."</a><br><br>";
                  }}
                  if($role='professor')
                  {

                    $directory= $user . '_homework/*';
                    $phpfiles = glob($directory);



                    foreach($phpfiles as $phpfile)
                    {
                      echo "<tr><td><center><a href=$phpfile>".basename($phpfile)."</a></center></td></tr>";
                      //echo "<a href=$phpfile>".basename($phpfile)."</a><br><br>";
                    }
                  }

                  ?>
                </table>


        </div>


        <div style="" class="col-md-1">
        </div>



        <div style="" class="col-md-4">


          <div style="">

                <h3>Submit article</h3>
                <form action="" method="post">
                  <input type="text" name="name" class="form-control"><br>
                  <input type="submit" class="btn btn-primary" value="Upload Link">
                </form>
                <?php
                error_reporting(0);
                  //echo "<meta http-equiv='refresh' content='0'>";
                  $newlink = $_POST['name'];
                  $professor = $_SESSION['sess_username'];
                  $servername = "localhost";
                  $username = "root";
                  $password = "";
                  $dbname = "project";
                  // Create connection
                  $conn = new mysqli($servername, $username, $password, $dbname);
                  //$sql = "SELECT username, role FROM Users";
                  //$result = $conn->query($sql);
                  if(isset($_POST['name']))
                  {   
                      $sql = "INSERT INTO links VALUES('$newlink','$professor')";
                      if ($conn->query($sql) === TRUE) 
                      {
                          echo "New link added successfully";
                      } 
                      else 
                      {
                          echo "Error: " . $sql . "<br>" . $conn->error;
                      }
                  }
                $conn->close();
                ?>

          </div><br><br>

          <!-- post new assignment -->
          <div style="">

                <h3>Post new assignment</h3>
                <form action="" method="post" enctype="multipart/form-data" class="">
                  <div>
                    <input type="file" name="myfile" id="fileToUpload" class="form-control"><br>   
                    <input type="submit" name="submit" value="Upload File Now" class="btn btn-primary">
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

      </div><br><br>

      <div class = "row">
        <div style="" class="col-md-3">
          <h3>Delete an article</h3>


                      <table class="table table-bordered" style="background-color:white">  
                      <?php
                      $servername = "localhost";
                      $username = "root";
                      $password = "";
                      $dbname = "project";
                      $professor = $_SESSION['sess_username'];
                      // Create connection
                      $conn = new mysqli($servername, $username, $password, $dbname);

                      $sql = "SELECT link FROM links WHERE username=(SELECT professor FROM students WHERE professor='$professor')";
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
                      </table> <br>
                      <form action="" method="post">
                        Enter URL to delete article: <input type="text" name="article" class="form-control"><br>
                      <input type="submit" class="btn btn-danger" value="Delete Link">
                      </form>
                       <?php
                        error_reporting(0);
                          //echo "<meta http-equiv='refresh' content='0'>";
                          $newlink = $_POST['name'];
                          $professor = $_SESSION['sess_username'];
                          $servername = "localhost";
                          $username = "root";
                          $password = "";
                          $dbname = "project";
                          $deletelink=$_POST['article'];
                          // Create connection
                          $conn = new mysqli($servername, $username, $password, $dbname);
                          //$sql = "SELECT username, role FROM Users";
                          //$result = $conn->query($sql);
                          if(isset($_POST['name']))
                          {   
                            
                              $sql = "INSERT INTO links VALUES('$newlink','$professor')";

                              if($newlink!="")
                              {
                                $sql1 = "SELECT link FROM links WHERE link ='$newlink'";
                                $result = $conn->query($sql1);

                                if (mysqli_num_rows($result) == 0)
                                {
                               
                                  if ($conn->query($sql) === TRUE) 
                                  {
                                      echo "New link added successfully";
                                  } 
                                  else 
                                  {
                                      echo "Error: " . $sql . "<br>" . $conn->error;
                                  }
                                }
                              }
                          }
                          if(isset($_POST['article']))
                          {
                              $sql= "DELETE FROM links WHERE link ='$deletelink'";



                              if($deletelink!="")
                              {
                                if($conn->query($sql) === TRUE)
                                {
                                  echo "Link deleted";
                                }
                              }
                          }


                        $conn->close();
                        ?>




        </div>




      </div>
    </div> 

       
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    </body>
</html>

