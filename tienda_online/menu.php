<!-- Menu de navegación -->
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container my-1">
            <a class="navbar-brand" href="index.php">Tienda online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarTop" aria-controls="navBarTop" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navBarTop">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Catalogo</a>
                    </li>
                </ul>

                <form method="get" action="index.php" autocomplete="off">
                    <div class="input-group pe-3">
                        <input type="text" name="q" class="form-control" placeholder="Buscar..." aria-describedby="icon-buscar">
                        <button class="btn btn-outline-info" type="submit" id="icon-buscar">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <a href="checkout.php" class="btn btn-primary me-2">
                    <i class="fas fa-shopping-cart"></i> Carrito <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                </a>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> &nbsp; <?php echo $_SESSION['user_name']; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn_session">
                            <li><a class="dropdown-item" href="compras.php">Mis compras</a></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-success">
                        <i class="fas fa-user"></i> Ingresar
                    </a>
                <?php } ?>
            </div>
        </div>
    </nav>
</header>