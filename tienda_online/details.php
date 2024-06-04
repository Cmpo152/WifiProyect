<?php

/**
 * Pantalla individual para mostrar el producto
 * Autor: WifiTeam
 */

require 'config/config.php';

$db = new Database();
$con = $db->conectar();

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if ($slug == '') {
    echo 'Error al procesar la petición';
    exit;
}

$sql = $con->prepare("SELECT count(id) FROM productos WHERE slug=? AND activo=1");
$sql->execute([$slug]);
if ($sql->fetchColumn() > 0) {

    $sql = $con->prepare("SELECT id, nombre, descripcion, precio, descuento FROM productos WHERE slug=? AND activo=1");
    $sql->execute([$slug]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    $id = $row['id'];
    $descuento = $row['descuento'];
    $precio = $row['precio'];
    $precio_desc = $precio - (($precio * $descuento) / 100);
    $dir_images = 'images/productos/' . $id . '/';

    $rutaImg = $dir_images . 'principal.jpg';

    if (!file_exists($rutaImg)) {
        $rutaImg = 'images/no-photo.jpg';
    }

    $imagenes = array();
    $dirint = dir($dir_images);

    while ($archivo = $dirint->read()) {
        if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
            $image = $dir_images . $archivo;
            $imagenes[] = $image;
        }
    }

    $dirint->close();
}

?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en linea</title>

    <link href="<?php echo SITE_URL; ?>css/bootstrap.min.css" rel="stylesheet">
    <base href="<?php echo SITE_URL; ?>">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

    <?php include 'menu.php'; ?>

    <!-- Contenido -->
    <main class="flex-shrink-0">
        <div class="container">
            <div class="row">
                <div class="col-md-5 order-md-1">
                    <!--Carrusel-->
                    <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <!--Imagenes-->
                            <div class="carousel-item active">
                                <img src="<?php echo $rutaImg; ?>" class="d-block w-100">
                            </div>

                            <?php foreach ($imagenes as $img) { ?>
                                <div class="carousel-item">
                                    <img src="<?php echo $img; ?>" class="d-block w-100">
                                </div>
                            <?php } ?>

                            <!--Imagenes-->
                        </div>

                        <!--Controles-->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                        <!--Controles carrusel-->
                    </div>
                    <!--Carrusel-->
                </div>

                <div class="col-md-7 order-md-2">
                    <h2><?php echo $row['nombre']; ?></h2>

                    <?php if ($descuento > 0) { ?>

                        <p><del><?php echo MONEDA; ?> <?php echo number_format($precio, 2, '.', ','); ?></del></p>
                        <h2><?php echo MONEDA; ?> <?php echo number_format($precio_desc, 2, '.', ','); ?> <small class=" text-success"><?php echo $descuento; ?>% descuento</small></h2>

                    <?php } else { ?>

                        <h2><?php echo MONEDA . ' ' . number_format($precio, 2, '.', ','); ?></h2>

                    <?php } ?>

                    <p class="lead"><?php echo $row['descripcion']; ?></p>

                    <div class="col-3 my-3">
                        <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1">
                    </div>

                    <div class="d-grid gap-3 col-7">
                        <button class="btn btn-primary" type="button" onClick="comprarAhora(<?php echo $id; ?>, cantidad.value)">Comprar ahora</button>
                        <button class="btn btn-outline-primary" type="button" onClick="addProducto(<?php echo $id; ?>, cantidad.value)">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>

    <script>
        function addProducto(id, cantidad) {
            var url = 'clases/carrito.php';
            var formData = new FormData();
            formData.append('id', id);
            formData.append('cantidad', cantidad);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors',
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero;
                    }
                })
        }

        function comprarAhora(id, cantidad) {
            var url = 'clases/carrito.php';
            var formData = new FormData();
            formData.append('id', id);
            formData.append('cantidad', cantidad);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors',
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero;
                        location.href ='checkout.php';
                    }
                })
        }
    </script>
</body>

</html>