<?php
require 'vendor/autoload.php';
$app = new \Slim\Slim();


$app->get('/books', function(){
    $db = new PDO("sqlite:/home/action/workspace/www/db/books.sqlite");
    $sql = "SELECT * FROM books";
    foreach($db->query($sql) as $row)
    {
        print $row['title'] . ' by ' . $row['author'] . '<br>';
    }
    $db = null;
});

$app->get('/', function () {
  echo "Hello<br>";
  foreach(PDO::getAvailableDrivers() as $driver)
    {
    echo $driver.'<br>';
  }
  try {
    $db = new PDO("sqlite:/home/action/workspace/www/db/books.sqlite");
    $pants = $db->query("select * from books");
    foreach ($pants as $row) {
      print $row['title'] . ' - ' . $row['author'] . '<br>';
    }
    $db = null;
  
  } 
  catch (PDOException $e)
    {
    echo $e->getMessage();
  }
});


$app->get('/insert/', function() {
  $db = new PDO("sqlite:/home/action/workspace/www/db/books.sqlite");
  $count = $db->exec("Insert into books(id, title,author) values(null, 'a book', 'an author')");
  echo $count.' row inserted';
  $db = null;
});



$app->run();