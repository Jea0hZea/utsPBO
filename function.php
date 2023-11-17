<?php
function pdo_connect_mysql()
{
  $DATABASE_HOST = 'localhost';
  $DATABASE_USER = 'root';
  $DATABASE_PASS = '';
  $DATABASE_NAME = 'kehadiran';
  try {
    return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
  } catch (PDOException $exception) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to database!');
  }
}
function template_header($title, $style) {
    echo <<<EOT
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>$title</title>
            <link href="$style" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="index.js"></script>
        </head>
      <body>
        <header>
          <div class="navbar">
            <div class="logo"><a href="#"> Survey Pembelajaran Via Daring</a></div>
            <ul class="links">
              <li><a href="index.php">Create</a></li>
              <li><a href="read.php">Admin</a></li>
            </ul>
            <div class="toggle_btn">
              <i class="fa-solid fa-bars"></i>
            </div>
          </div>
    
          <div class="dropdown_menu">
            <li><a href="index.php">Create</a></li>
            <li><a href="read.php">Admin</a></li>
          </div>
        </header>
    EOT;
    }
    
    function template_footer() {
      echo <<<EOT
      </body>
      </html>
      EOT;
    }
?>