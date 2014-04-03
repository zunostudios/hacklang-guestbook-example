<?hh

include('inc/util.php');
$guestBook = new GuestBook();

// Get the data
$dataFile = new FlatFile("data.txt");

// If we get a comment we're going to redirect
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$dataFile->writeComment($_POST['name'], $_POST['comment']);
	header( 'Location: /guestbook.php' );
}

// Grab the data out of the comment file
$commentArray = $dataFile->linesToComments();
$commentArray->reverse();
$guestBook->addVector($commentArray);

echo "
	<!doctype html>
	<html>
		<style type=\"text/css\" media=\"screen\">
			body, html {
	background: #ccc;
	width: 100%;
	height: 100%;
	margin: 0;
	padding: 0;
}

body {
	font-family:sans-serif;
}

.wrapper {
	width: 70%;
	background: #fff;
	margin: 0 auto;
}

.comment {
	padding:10px 20px;
	border-bottom:1px solid #ddd;
}

form {
	padding: 10px 0;
}
form * {
 width:60%;
 display:block;
 margin:0 auto;
}
h1 {
	padding: 10px 25px;
	text-align:center;
}

form input[type=submit] {
	width:40%;
	margin: 0 0 0 20%;
}

		</style>		
		<head><title> Guestbook test</title></head>
	<body>

	<div class=\"wrapper\">
	    <h1>My lil' guestbook</h1>

";

$guestBook->html_out();

echo "
		<form action=\"http://demo.davidma.de:4321/guestbook.php\" method=\"post\">
			<input type=\"text\" name=\"name\" placeholder=\"Name\" /><br/>
			<textarea name=\"comment\" placeholder=\"Comment\" ></textarea><br/>
			<input type=\"submit\" value=\"Submit\" />
		</form>

</div></body></html>\n\n";