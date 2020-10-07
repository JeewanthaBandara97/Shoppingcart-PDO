<?php

	// DB credentials.
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASS','');
	define('DB_NAME','Productdb');
	// Establish database connection.
	try
	{
	$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	}
	catch (PDOException $e)
	{
	exit("Error: " . $e->getMessage());
	}

	//start session
	 session_start();
	 error_reporting(0);
	 
	//get action string
	$action = isset($_GET['action'])?$_GET['action']:"";

	if($action=='addcart' && $_SERVER['REQUEST_METHOD']=='POST') {	
		//if(isset($_POST['add']))
		//{
		 $pid=$_GET['id'];	
		 $count = count($_SESSION['cart']);	
		 
		$query = "SELECT * FROM producttb WHERE id=:id";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam('id', $_POST['id']);
		$stmt->execute();
		$product = $stmt->fetch();
		
		$currentQty = $_SESSION['cart'][$_POST['id']]['qty']+1; 	
		$_SESSION['cart'][$_POST['id']] =array('qty'=>$currentQty,'id'=>$product['id'],'product_name'=>$product['product_name'],'product_price'=>$product['product_price'],'product_image'=>$product['product_image']);
		$product='';
				
		echo "<script>window.location = 'store.php'</script>";		
	 
		//}
	}
 
?>






<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Web</title>

    <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome  -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />

    <!-- Bootstrap CDN -->	
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

     <!-- Additional CSS -->	
     <link rel="stylesheet" href="assets/css/store-style.css">   

</head>

    <!-- Navigation Bar-->
<?php require_once("includes/navi.php"); ?>

 
<body>
	
<div class="container">
	<div class="row row-cols-1 row-cols-md-4 text-center py-5">

			<?php 
									
			$sql = "SELECT * FROM producttb ORDER BY id DESC  ";

			$query = $dbh -> prepare($sql);
			$query->execute();
			$results=$query->fetchAll(PDO::FETCH_OBJ);
			$cnt=1;
			if($query->rowCount() > 0)
			{
			foreach($results as $result)
			{  
			?> 
			
		<div class="col mb-4">			
			<form action="store.php?id=<?php echo htmlentities($result->id);?>&action=addcart" method="post">
				<div class="card shadow border-secondary">
					<div>
						<img src="<?php echo htmlentities($result->product_image);?>" alt="Image1" class="img-fluid card-img-top">
					</div>
					<div class="card-body">
						<h5 class="card-title"><?php echo htmlentities($result->product_name);?></h5><h6>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="fas fa-star"></i>
							<i class="far fa-star"></i>
						</h6>	
						<p class="card-text">
							some example text
						</p>
						<h5>
							<small><s class="text-secondary">$600</s></small>
							<span class="price">
								$<?php echo htmlentities($result->product_price);?>
							</span>
						</h5>	
						<button class="btn btn-warning my-3" type="submit" name="add">
							Add to Cart <i class="fas fa-shopping-cart"></i>
						</button>						 
                          <input type="hidden" name="id" value="<?php echo htmlentities($result->id);?>">								
					</div>					
				</div>
			</form>
		</div>			
	         <?php }} ?>
			 
	</div>	
</div>


 




    <!-- Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>