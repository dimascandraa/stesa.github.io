<?php
session_start();
include 'dbconnect.php';

$idproduk = $_GET['idproduk'];

if(isset($_POST['addprod'])){
	if(!isset($_SESSION['log']))
		{	
			header('location:login.php');
		} else {
				$ui = $_SESSION['id'];
				$cek = mysqli_query($conn,"select * from cart where userid='$ui' and status='Cart'");
				$liat = mysqli_num_rows($cek);
				$f = mysqli_fetch_array($cek);
				$orid = $f['orderid'];
				$warna = $_POST['warna'];
				$qty = $_POST['qty'];
				
				//kalo ternyata udeh ada order id nya
				if($liat>0){
							
							//cek barang serupa
							$cekbrg = mysqli_query($conn,"select * from detailorder where idproduk='$idproduk' and orderid='$orid'");
							$liatlg = mysqli_num_rows($cekbrg);
							$brpbanyak = mysqli_fetch_array($cekbrg);
							$jmlh = $brpbanyak['qty'];
							
							//kalo ternyata barangnya ud ada
							if($liatlg>0){
								$i=1;
								$baru = $jmlh + $i;
								
								$updateaja = mysqli_query($conn,"update detailorder set qty='$baru' where orderid='$orid' and idproduk='$idproduk'");
								
								if($updateaja){
									echo " <div class='alert alert-success'>
								Barang sudah pernah dimasukkan ke keranjang, jumlah akan ditambahkan
							  </div>
							  <meta http-equiv='refresh' content='1; url= product.php?idproduk=".$idproduk."'/>";
								} else {
									echo "<div class='alert alert-warning'>
								Gagal menambahkan ke keranjang
							  </div>
							  <meta http-equiv='refresh' content='1; url= product.php?idproduk=".$idproduk."'/>";
								}
								
							} else {
							
							$tambahdata = mysqli_query($conn,"insert into detailorder (orderid,idproduk,idwarna,qty) values('$orid','$idproduk','$warna',$qty)");
							if ($tambahdata){
							echo " <div class='alert alert-success'>
								Berhasil menambahkan ke keranjang
							  </div>
							<meta http-equiv='refresh' content='1; url= product.php?idproduk=".$idproduk."'/>  ";
							} else { echo "<div class='alert alert-warning'>
								Gagal menambahkan ke keranjang
							  </div>
							 <meta http-equiv='refresh' content='1; url= product.php?idproduk=".$idproduk."'/> ";
							}
							};
				} else {
					
					//kalo belom ada order id nya
						$oi = crypt(rand(22,999),time());
						
						$bikincart = mysqli_query($conn,"insert into cart (orderid, userid) values('$oi','$ui')");
						
						if($bikincart){
							$tambahuser = mysqli_query($conn,"insert into detailorder (orderid,idproduk,idwarna,qty) values('$oi','$idproduk','$warna','$qty')");
							if ($tambahuser){
							echo " <div class='alert alert-success'>
								Berhasil menambahkan ke keranjang
							  </div>
							<meta http-equiv='refresh' content='1; url= product.php?idproduk=".$idproduk."'/>  ";
							} else { echo "<div class='alert alert-warning'>
								Gagal menambahkan ke keranjang
							  </div>
							 <meta http-equiv='refresh' content='1; url= product.php?idproduk=".$idproduk."'/> ";
							}
						} else {
							echo "gagal bikin cart";
						}
				}
				
		}
};
?>

<!DOCTYPE html>
<html>

<head>
	<title>STESAOFFICIAL | Product</title>
	<link rel="shortcut icon" href="assetss/logo.png" />
	<!-- for-mobile-apps -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="Falenda Flora, Ruben Agung Santoso" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- //for-mobile-apps -->
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<!-- font-awesome icons -->
	<link href="css/font-awesome.css" rel="stylesheet">
	<!-- //font-awesome icons -->
	<!-- js -->
	<script src="js/jquery-1.11.1.min.js"></script>
	<!-- //js -->
	<link
		href='//fonts.googleapis.com/css?family=Raleway:400,100,100italic,200,200italic,300,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic'
		rel='stylesheet' type='text/css'>
	<link
		href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic'
		rel='stylesheet' type='text/css'>
	<!-- start-smoth-scrolling -->
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$(".scroll").click(function (event) {
				event.preventDefault();
				$('html,body').animate({ scrollTop: $(this.hash).offset().top }, 1000);
			});
		});
	</script>
	<!-- start-smoth-scrolling -->
</head>

<body>
	<!-- header/navigation -->
	<div class="navigation-agileits">
		<nav class="navbar navbar-default">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header nav_2">
				<button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse"
					data-target="#bs-megadropdown-tabs">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
				<ul class="nav navbar-nav">
					<li class="active"><a href="index.php" class="act">Home</a></li>
					<!-- Mega Menu -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Kategori Produk<b
								class="caret"></b></a>
						<ul class="dropdown-menu multi-column columns-3">
							<div class="row">
								<div class="multi-gd-img">
									<ul class="multi-column-dropdown">

										<?php 
														$kat=mysqli_query($conn,"SELECT * from kategori order by idkategori ASC");
														while($p=mysqli_fetch_array($kat)){

															?>
										<li><a href="kategori.php?idkategori=<?php echo $p['idkategori'] ?>">
												<?php echo $p['namakategori'] ?>
											</a></li>

										<?php
																	}
														?>
									</ul>
								</div>
							</div>
						</ul>
					</li>
					<li><a href="cart.php">Keranjang Saya</a></li>
					<li><a href="daftarorder.php">Daftar Order</a></li>
				</ul>
				<div class="agile-login">
					<ul>
						<?php
				if(!isset($_SESSION['log'])){
					echo '
					<li><a href="registered.php"> Daftar</a></li>
					<li><a href="login.php">Masuk</a></li>
					';
				} else {
					
					if($_SESSION['role']=='Member'){
					echo '
					<li style="color:white">Halo, '.$_SESSION["name"].'
					<li><a href="logout.php">Keluar?</a></li>
					';
					} else {
					echo '
					<li style="color:white">Halo, '.$_SESSION["name"].'
					<li><a href="admin">Admin Panel</a></li>
					<li><a href="logout.php">Keluar?</a></li>
					';
					};
					
				}
				?>
						<div class="product_list_header">
							<a href="cart.php"><button class="w3view-cart" type="submit" name="submit" value=""><i
										class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
							</a>
						</div>
					</ul>
				</div>
				<div class="w3l_search">
					<form action="search.php" method="post">
						<input type="search" name="Search" placeholder="Cari produk...">
						<button type="submit" class="btn btn-default search" aria-label="Left Align">
							<i class="fa fa-search" aria-hidden="true"> </i>
						</button>
						<div class="clearfix"></div>
					</form>
				</div>
			</div>
		</nav>
	</div>
	<!-- header/navigation -->

	<!-- breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
				<li class="active">
					<?php 
				$p = mysqli_fetch_array(mysqli_query($conn,"Select * from produk where idproduk='$idproduk'"));
				echo $p['namaproduk'];
				?>
				</li>
			</ol>
		</div>
	</div>
	<!-- //breadcrumbs -->
	<div class="products">
		<div class="container">
			<div class="agileinfo_single">
				<div class="col-md-1">
					<h5>Pilihan warna:</h5>
					<a href="<?php echo $p['gambarwarna']?>" target="blank"><img id="example"
							src="<?php echo $p['gambarwarna']?>" alt=" " class="img-responsive"></a>
				</div>
				<div class="col-md-4 agileinfo_single_left">
					<img id="example" src="<?php echo $p['gambar']?>" alt=" " class="img-responsive">
				</div>
				<div class="col-md-6 agileinfo_single_right">
					<h2>
						<?php echo $p['namaproduk'] ?>
					</h2>
					<div class="rating1">
					</div>
					<div class="w3agile_description">
						<h4>Deskripsi :</h4>
						<p>
							<?php echo $p['deskripsi'] ?>
						</p>
					</div>
					<div class="snipcart-item block">
						<div class="snipcart-thumb agileinfo_single_right_snipcart">
							<h4 class="m-sing">Rp
								<?php echo number_format($p['hargaafter']) ?>
							</h4>
						</div>
						<div class="snipcart-details agileinfo_single_right_details">
							<form action="#" method="post">
								<fieldset>
									<input type="text" name="warna" placeholder="warna" required>
									<br>
									<br>
									<input type="text" name="qty" placeholder="qty" required>
									<hr>
									<input type="hidden" name="idprod" value="<?php echo $idproduk ?>">
									<input type="submit" name="addprod" value="Add to cart" class="button">
								</fieldset>
							</form>
						</div>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>

	<!-- //footer -->
	<div class="footer">
		<div class="container">
			<div class="w3_footer_grids">
				<div class="col-md-4 w3_footer_grid">
					<h3>Hubungi Kami</h3>

					<ul class="address">
						<li><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>JLN. KAYU VIII C NO.9
							PONGANGANREJO<p>MANYAR GRESIK,JAWA TIMUR</p>
						</li>
						<li><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i><a
								href="mailto:info@email">stefkanisa@yahoo.com</a></li>
						<li><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i>+628113394660</li>
					</ul>
				</div>
				<div class="col-md-3 w3_footer_grid">
					<h3>Tentang Kami</h3>
					<ul class="info">
						<li><i class="fa fa-arrow-right" aria-hidden="true"></i><a href="about.html">About Us</a></li>
					</ul>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<div class="footer-botm">
		<div class="container">
			<div class="w3layouts-foot">
				<ul>
					<li><a href="https://www.instagram.com/stesaofficial/" class="w3_agile_instagram"><i
								class="fa fa-instagram" aria-hidden="true"></i></a></li>
					<li><a href="https://www.facebook.com/stefka.nisa" class="w3_agile_facebook"><i
								class="fa fa-facebook" aria-hidden="true"></i></a></li>
					<li><a href="https://wa.me/+628113394660" class="agile_twitter"><i class="fa fa-whatsapp"
								aria-hidden="true"></i></a></li>
					<li><a href="https://mail.google.com/mail/u/0/?view=cm&tf=1&fs=1&to=stefkanisa@yahoo.com"
							class="agile_twitter"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
					<li><a href="https://shopee.co.id/stesahijab" class="agile_twitter"><i class="fa fa-shopping-cart"
								aria-hidden="true"></i></a></li>
				</ul>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<!-- //footer -->

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- top-header and slider -->
	<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function () {

			var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 4000,
				easingType: 'linear'
			};


			$().UItoTop({ easingType: 'easeOutQuart' });

		});
	</script>
	<!-- //here ends scrolling icon -->

	<!-- main slider-banner -->
	<script src="js/skdslider.min.js"></script>
	<link href="css/skdslider.css" rel="stylesheet">
	<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery('#demo1').skdslider({ 'delay': 5000, 'animationSpeed': 2000, 'showNextPrev': true, 'showPlayButton': true, 'autoSlide': true, 'animationType': 'fading' });

			jQuery('#responsive').change(function () {
				$('#responsive_wrapper').width(jQuery(this).val());
			});

		});
	</script>
	<!-- //main slider-banner -->
</body>

</html>