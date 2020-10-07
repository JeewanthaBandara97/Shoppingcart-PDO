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
	 
	$total=0;

			
    //get action string
	$action = isset($_GET['action'])?$_GET['action']:"";
	
	//add one by one product
	/*
	if($action=='add') 
	{ 

		$id = $_GET['id'];

		$base = $_SESSION['cart'][$_GET['id']];

	
        //array_replace($_SESSION['cart'][$_GET['id']] ['product']['qty'],5);	

		header("Location:cart.php?i$base");	
	}	
	*/   	
	
	//Empty All
	if($action=='emptyall')
	{
		$_SESSION['cart'] =array();
		header("Location:cart.php");	
	}
   
	//Empty one by one
	if($action=='empty') 
	{
		$id = $_GET['id'];
		$cart = $_SESSION['cart'];
		unset($cart[$id]);
		$_SESSION['cart']= $cart;
		header("Location:cart.php");	
	}	   

   if(isset($_SESSION['cart']))
   {		   
		//print_r($_SESSION['cart']);	
		//echo "<br>" ;

		 //Get all Products
		$query = "SELECT * FROM producttb";
		$stmt = $dbh->prepare($query);
		$stmt->execute();
		$cart = $stmt->fetchAll();
   }
   else{
	   echo "<h5>Cart is Empty</h5>";
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
     <link rel="stylesheet" href="assets/css/cart-style.css ">   

</head>

   <!-- Navigation Bar -->
<?php require_once("includes/navi.php"); ?>

<body>

<div class="container-fluid">
    <div class="row px-5">
        <div class="col-md-7">
            <div class="shopping-cart">
				<h5>
                   My Cart <a href="Cart.php?action=emptyall" class="btn btn-outline-danger mx-2" role="button" aria-pressed="true">Empty cart</a>
				</h5>
				<!-- Test -  -->
                <hr>

	<?php foreach($_SESSION['cart'] as $key=>$product): ?>	
				<form action="cart.php?action=remove" method="post" class="cart-items">
					<div class="border rounded">
						<div class="row bg-white">
							<div class="col-md-3 pl-0">
								<img src="<?php print $product['product_image'];?>" alt="<?php print $product['product_image'];?>" class="img-fluid">
							</div>
							<div class="col-md-6">
								<h5 class="pt-2"><?php print $product['product_name']; ?></h5>
								<small class="text-secondary">Qty: <?php print $product['qty']; ?></small>
								<h5 class="pt-2">$ <?php print $product['product_price']; ?></h5>
								<button type="submit" class="btn btn-warning">Save for Later</button>

								<a href="Cart.php?action=empty&id=<?php print $product['id']; ?>" class="btn btn-danger mx-2" role="button" aria-pressed="true">Remove</a>								
							</div>
							<div class="col-md-3 py-5">
							
								<button type="button" class="btn bg-light border rounded-circle"><i class="fas fa-minus"></i> </button>

																
								<input type="text" value="<?php print $product['qty'];  ?>" class="form-control w-25 d-inline">
								
								<button type="button" class="btn bg-light border rounded-circle"><i class="fas fa-plus"></i></button>	
								
								<!--<a href="cart.php?action=add&id=<?php //print $product['id']; ?>" class="btn bg-light border rounded-circle"><i class="fas fa-plus"></i></a>-->						
							</div>
						</div>
					</div>
				</form>
    <?php $total = $total+ $product['qty'] * $product['product_price'];?>  				
	<?php endforeach;?>	

            </div>
        </div>
        <div class="col-md-4 offset-md-1 border rounded mt-5 bg-white h-25">

            <div class="pt-4">
                <h6>PRICE DETAILS</h6>
                <hr>
                <div class="row price-d etails">
                    <div class="col-md-6">
                        <?php
                            if (isset($_SESSION['cart'])){
                                $count  = count($_SESSION['cart']);
                                echo "<h6>Price ($count items)</h6>";
                            }else{
                                echo "<h6>Price (0 items)</h6>";
                            }
                        ?>
                        <h6>Delivery Charges</h6>
                        <hr>
                        <h6>Amount Payable</h6>
                    </div>
                    <div class="col-md-6">
                        <h6>$<?php echo $total; ?></h6>
                        <h6 class="text-success">FREE</h6>
                        <hr>
                        <h6>$<?php
                            echo $total;
                            ?></h6>
                    </div>
                </div>
            </div>

        </div>
  <br> <br><br>     
    </div>
</div>



    <!-- Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>