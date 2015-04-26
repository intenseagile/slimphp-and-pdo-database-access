# Running a basic query against a sqlite database with the Slim PHP framework

Slim PHP is a great micro framework; perfect for small applications or APIs where you don't need the convenience or convention of a more full blown PHP MVC framework. At some point, though, you'll want to interact with a database. That's what we are going to do here. It's only going to be a simple <code>select</code> statement, but it's a good start.

## Assumptions
I'm going to assume you have access to a development environment with php installed and composer working. I'll also assume you have installed sqlite3.

## Installing Slim with Composer
Find your way to your www directory (or the directory you are going to use to host this little app). Create a file called 'composer.json' and put the following in it:

{
  "require": {
    "slim/slim": "2.*"
  }
}

Fantastic. Now, at the command line, and in your relevant directory, run the following command:

php composer.phar install

This should pull down the Slim framework into a /vendor directory for you.

## Testing the Slim install
It's always a good idea to test the basic framework install before we dive into something more complex. A simple 'hello world' application will suffice to make sure Slim is properly installed.

In your www directory, create an index.php file and write the following:

<?php
require 'vendor/autoload.php';
$app = new \Slim\Slim();

$app->get('/', function () {
  echo "Hello World";
});

$app->run();

Now open your browser and point it at your localhost. Your browser should return a page saying "Hello World".

I like to add in one more route to make sure my .htaccess is correctly set up. Ammend your code as follows:

<?php
require 'vendor/autoload.php';
$app = new \Slim\Slim();

$app->get('/', function () {
  echo "Hello World";
});

$app->get('/route66', function() {
  echo "Get your kicks on route 66";
});

$app->run();

Again, open a browser, navigate to your localhost and append /rout66 to your url. Make sure the page returns correctly with "Get your kicks on route 66".

## Troubleshooting routes
If you are hitting problems with your routes make sure your .htaccess file is correct - you should have something like the following:

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !=-d
RewriteCond %{REQUEST_FILENAME} !=-f
RewriteRule ^ index.php

## Setting up the database

SQLite is a great little database engine. It is serverless and configuration free. That means you don't need to install a fully blown relational database management system such as MySql or Postgers just to get started with your database work. SQLite is also powerful, so don't be fooled into thinking it isn't robust. It's a great solution for many small to medium sized applications. 

To quickly create a database, use a terminal or command prompt. Navigate to the directory in which your are going to store your database and then do the following:

sqlite3 books_db.sqlite

This will create the file that will be your database and also fire up the SQLite command prompt which we can use to issue sql staments.

Let's create a table:

CREATE TABLE books(id INTEGER PRIMARY KEY, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL);

The <code>.schema</code> command in sqlite is useful for inspecting our current database structure; of course at the moment, our database is super simple and we only have one table, so it's not going to tell us much, but try it out anyway to make sure you have the table you expect.

.schema
>> CREATE TABLE books(id INTEGER PRIMARY KEY, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL);

Great. In the interest of keeping things simple, we'll ignore, for the moment, the fact that our databes could do with some normalization. Next up, we want to insert a record into our table. Again from the sqlite command prompt, type the following:

INSERT INTO books VALUES(null, 'On The Road', 'Jack Kerouac');

That should put a single record into your table. If you want to be sure, you can run a <code>select</code>:

SELECT * FROM books;

and you should get your row back:

1|On The Road|Jack Kerouac

Time to exit sqlite and get on with some PHP code. From the sqlite command prompt, type the following:

.exit


## Selecting data 

Having data in a database is no use if we can't get it out. To grab our data, we'll use php's built in PDO class to bring back our data.

Open up your index.php again and set up a new route to list our books and put some code in it:

$app->get('/books', function(){
    $db = new PDO("sqlite:/path/to/database/file.sqlite");
});

This gives us a simple reference to our database. Note that because sqlite is serverless, we must specify the full path name to our database as our connection string. Now, append to your code to select the data and display it:

$app->get('/books', function(){
    $db = new PDO("sqlite:/path/to/database/file.sqlite");
    $sql = "SELECT * FROM books";
    foreach($db->query($sql) as $row)
    {
        print $row['title'] . ' by ' . $row['author'] . '<br>';
    }
    $db = null;
});


The code is fairly self explanatory. Having initialised our PDO object ($db) we create a simple string variable to hold our SQL statement. In this case, <code>SELECT * FROM books</code> to return all our data (of course we only inserted one row earlier so we'll only get one row back). The <code>foreach</code> loop actually issues the query for us and returns iterates over the result set, popping each row successively into as an array called <code>$row</code>. We can access the columns in our row by specifying the column name as our array element. The last thing we do is set our database object to null, closing the connection to the database.

Opening your browser and navigate to the new route. You should see something like this:


## Conclusion

What have we done? We've created a simple barebones application using the Slim PHP framework to query a SQLite database and return a result set, outputting it in a web page. It might not seem like much, but mighty oaks from little acorns grow.



