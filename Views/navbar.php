<nav class="navbar navbar-expand-lg bg-body-tertiary" id="nav" data-bs-theme="dark">
	<div class="container-fluid">
		<a class="navbar-brand" href="index.php">
			<img src="../recursos/Logo.jfif" alt="" width="35" height="35" id="logo" class="d-inline-block align-text-top">

		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">

				<li class="nav-item dropdown">
					<a class="nav-link vactie dropdown-toggle " href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Categoria
					</a>
					<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<li><a class="dropdown-item" href="hogar.php">Hogar</a></li>
						<li><a class="dropdown-item" href="jardineria.php">Jardineria</a></li>
						<li><a class="dropdown-item" href="carpinteria.php">Carpinteria</a></li>
					</ul>
				</li>
				<li class="nav-item">
					<a class="nav-link " aria-current="page" href="#fot">Sobre nosotros</a>
				</li>
				<li class="nav-item">
					<a class="nav-link " aria-current="page" href="#fot" id="Contacto">Contacto</a>
				</li>
			</ul>
			

			<?php if (isset($_SESSION['user_id'])) { ?>
				<div class="dropdown">
					<button class="btn btn-outline-primary dropdown-toggle me-2 btn-sm" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa-solid fa-user"></i>&nbsp;
						<?php echo $_SESSION['user_name']; ?>
					</button>
					<ul class="dropdown-menu small" aria-labelledby="btn_session">
						<li>
							<h6><a class="dropdown-item" href="CerrarSesion.php">Cerrar sesión</a></h6>
							<h6><a class="dropdown-item" href="compras.php">Mis compas</a></h6>
						</li>
					</ul>
				</div>


			<?php } else { ?>
				<a href="../Views/login.php" class="btn btn-outline-primary me-2 btn-sm"><i class="fa-solid fa-user"></i> Login</a>
			<?php } ?>

			<a href="../Views/checkout.php" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-cart-shopping"></i>
				carrito <span id="num_cart" class="badge bg_secondary"><?php echo $num_cart; ?></span>
			</a>
		</div>
	</div>
</nav>