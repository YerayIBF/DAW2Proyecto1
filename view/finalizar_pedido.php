<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>April</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/finalizar-pedido.css">

</head>

<body>
<?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?php echo $_SESSION['alert']['type']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['alert']['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
    <header>
        <div class="header-contenedor">
            <div class="logo">
                <img src="img/Logo.png">
            </div>
            <div class="icono">
                <a href="?controller=producto&action=carta">
                    <img class="icono-bolsa" src="img/bolso-Carrito.png">
                </a>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <section class="container-fluid">
            <div class="row ">

                <div class="col-md-6 d-flex flex-column formulario-finalizar align-items-end ">
                    <div class="contenedorAjusteForm contenedorTamañoForm ">
                        <h2 class="formulario-H2">Entrega</h2>
                        <form method="POST" action="?controller=producto&action=finalizarPedido">
                            <select class="PaisSelect w-100 mb-3">
                                <option>España</option>
                                <option>Portugal</option>
                            </select>
                            <div class="d-flex formularioajusteGAP ">
                                <input type="text" class="inputNombre" name="nombre" placeholder="nombre" required>
                                <input type="text" name="apellido" placeholder="apellido" required>
                            </div>
                            <input type="text" class="w-100 mb-3" name="direccion" placeholder="Dirección" required>


                            <input type="text" class="w-100 mb-3" name="vivienda" placeholder="Casa, apartamento, etc. (opcional)">


                            <div class="d-flex formularioajusteGAP">
                                <input type="text" name="codigoP" placeholder="Código postal" required>
                                <input type="text" name="ciudad" placeholder="Ciudad" required>
                                <input type="text" name="provincia/estado" placeholder="Provincia/Estado" required>
                            </div>

                            <input type="tel" class="w-100 mb-4" name="teléfono" placeholder="Teléfono" required>

                            <h2 class="formulario-H2">Pago</h2>
                            <p class="p-formulario">Todas las transacciones son seguras y están encriptadas.</p>

                            <input class="w-100 mb-3" placeholder="Número de tarjeta" name="numeroTarjeta" required>
                            <div class="d-flex formularioajusteGAP">
                                <input placeholder="Fecha de vencimiento (MM / AA)" name="FechaV" required>
                                <input placeholder="Código de seguridad" name="CodigoSeguridad" required>
                            </div>
                            <input class="w-100 mb-4" placeholder="Nombre del titular" name="numeroTarjeta" required>
                            <button type="submit" class="w-100">Realizar pago</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-6 d-flex flex-column ContenedorCupones ">
                    <div class="contenedorTamaño">
                        <?php if (!empty($_SESSION['carrito'])) { ?>
                            <?php foreach ($_SESSION['carrito'] as $producto) { ?>
                                <div class="producto-info d-flex">
                                    <div class="imagen-con-cantidad position-relative">
                                        <img class="img-finalizar-compra" src="img/<?= $producto->getImagen(); ?>" alt="<?= $producto->getNombre(); ?>">
                                        <span class="cantidad-producto"><?= $producto->getCantidad(); ?></span>
                                    </div>
                                    <div class="detalles-producto ms-3">
                                        <div class="d-flex justify-content-between">
                                            <p class="precio-producto"><?= $producto->getPrecio(); ?> €</p>
                                        </div>
                                        <p class="nombre-producto"><?= $producto->getNombre(); ?></p>
                                        <p class="descripcion-producto"><?= $producto->getDescripcion(); ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <form method="POST" action="?controller=producto&action=aplicarCupon" class="FormCupon">
                            <input type="text" name="Oferta" placeholder="AÑADE TU DESCUENTO AQUI" required>
                            <button type="submit">APLICAR</button>
                        </form>
                        <div class="ajusteDisplayTexto">
                            <p class="p-cupones">Subtotal </p>
                            <p><?= number_format($subtotal, 2) ?>€</p>
                        </div>
                        <div class="ajusteDisplayTexto">
                            <p class="p-cupones">Cupon Aplicado:</p>
                            <p class="p-cupones"><?= isset($_SESSION['nombre_oferta']) ? $_SESSION['nombre_oferta'] : 'Sin cupón' ?></p>
                        </div>
                        <div class="ajusteDisplayTexto">
                            <p class="p-cupones">Descuento de </p>
                            <p class="p-cupones"> -<?= number_format($descuentoAplicado, 2) ?> €</p>
                        </div>
                        <div class="ajusteDisplayTexto">
                            <p class="p-cupones">Envío</p>
                            <p class="p-cupones"><?= number_format($envio, 2) ?>€</p>
                        </div>
                        <div class="ajusteDisplayTexto">
                            <p class="p-cuponesTotal">Total sin impuestos</p>
                            <p class="p-cuponesTotal">EUR <?= number_format($totalSinImpuestos, 2) ?> €</p>
                        </div>
                        <p class="p-cupones">Incluye <?= number_format($impuestos, 2) ?> € de impuestos</p>
                        <div class="ajusteDisplayTexto">
                            <p class="p-cuponesTotal">Total</p>
                            <p class="p-cuponesTotal">EUR <?= number_format($totalConImpuestos, 2) ?> €</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer>

    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>