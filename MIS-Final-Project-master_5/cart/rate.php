<?php
include_once 'dbconnect.php';
session_start();
ob_start();

if (!isset($_SESSION['user'])) {
		header("Location: index.php");
		exit;
}

$res = mysqli_query($db, "SELECT * FROM users WHERE userId=".$_SESSION['user']);
$userRow = mysqli_fetch_array($res);

// print out all products
// should change to downloaded products
$allp = mysqli_query($db, "SELECT * FROM products WHERE id=".$_GET['id']);
$theproduct = mysqli_fetch_array($allp);

if (isset($_POST['rate'])) {
  $score = $_POST['score'];
  $feedback = $_POST['feedback'];

	// each rating record
  $query = "INSERT INTO rating(userName, product_id, score, feedback)
      VALUES('$userRow[1]', '$theproduct[0]', '$score', '$feedback')";
  $res = mysqli_query($db, $query);

	// sum the scores of the product
	$getscore = mysqli_query($db, "SELECT SUM(score) AS allscore FROM rating WHERE product_id = '$theproduct[0]' ");
	$row = mysqli_fetch_array($getscore);
	$totalscore = $row['allscore'];

	// count the no. of ratings of the product
	$getrates = mysqli_query($db, "SELECT COUNT(product_id) AS allrates FROM rating WHERE product_id = '$theproduct[0]' ");
	$row = mysqli_fetch_array($getrates);
	$totalrates = $row['allrates'];

	// calculate the average score
	$avgscore = $totalscore / $totalrates;

	// insert new average score to products
  $postrating = mysqli_query($db, "UPDATE products SET rating = '$avgscore'
      WHERE id = '$theproduct[0]' ");
echo "<script>
	    alert('評分成功！');
	    window.location.href='file_manager.php';
	    </script>";
}

?>

<!-- <!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.js"></script>
	<script type="text/javascript" src="/SScore/SScore/SScore.js"></script>
	<link rel="stylesheet" href="/SScore/SScore/SScore.css">
</head>
<body>
	<div>
		<button type="button" id="btn1">編輯</button>
	  	<form method="post">
	  		<div id="tab" style="display: none">
	            <div class="tab0">
	            	<div class="close" id="btn3"></div>
	            	<br>
	            	<h4>評分:</h4>

					<div id="div">
						<span id="0">☆</span><span id="1">☆</span><span id="2">☆</span><span id="3">☆</span><span id="4">☆</span>
					</div>
					<br>

					<h4>評論:</h4>

					<input id="score" type="" name="score" value="">

	    			<br/>
	    			<textarea class="post" rows="10" cols="100" name="feedback" placeholder="give some feedback..."></textarea><br/>
	    			<br>
	    			<br>
	    			<button id="postsend" type="submit" name="rate">確認評價</button>
	  			</div>
	  		</div>
	  	</form>

	</div>
</body>
</html> -->


<?php ob_end_flush(); ?>
