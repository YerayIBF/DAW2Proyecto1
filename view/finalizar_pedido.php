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
    <header>
        <div class="header-contenedor">
            <div>
                <img src="img/Logo.png">
            </div>
            <a><img class="icono-bolsa" src="img/bolso-Carrito.png"></a>
        </div>
    </header>
    <hr>
    <main>
        <section class="container-fluid my-5">
            <div class="row">
                <div class="col-md-6 d-flex flex-column">
                    <form method="POST" action="?controller=producto&action=finalizarPedido">
                     
                        <input type="text" name="direccion" placeholder="Introduce tu dirección">
                        <button type="submit">Finalizar pedido</button>
                    </form>
                </div>

                <div class="col-md-6 d-flex flex-column">
                <?php if (!empty($_SESSION['carrito'])) { ?>
                    <?php foreach ($_SESSION['carrito'] as $producto) { ?>
                        <div class="">
                            <img class="img-finalizar-compra" src="img/<?= $producto->getImagen(); ?>" alt="<?= $producto->getNombre(); ?>">
                            <p class="">€<?= $producto->getPrecio() ?></p>
                        </div>
                        <?php }?>
                    <?php }?>
                    <form method="POST" action="?controller=producto&action=aplicarCupon">
                        <input type="text" name="Oferta" placeholder="Añade tu descuento aqui">
                        <button type="submit">Añadir cupón</button>
                    </form>
                    <h2>Subtotal • <?= $cantidadArticulos ?> artículos: €<?= number_format($subtotal, 2) ?></h2>
                    <h2>Descuento en pedidos</h2>
                    <h2><?= $codigoCupon ? $codigoCupon : 'Sin cupón' ?></h2>
                    <h2>-€<?= number_format($descuentoAplicado, 2) ?></h2>
                    <h2>Envío</h2>
                    <h2>€<?= number_format($envio, 2) ?></h2>
                    <h2>Total sin impuestos</h2>
                    <h2>€<?= number_format($totalSinImpuestos, 2) ?></h2>
                    <h2>Incluye €<?= number_format($impuestos, 2) ?> de impuestos</h2>
                    <h2>Total</h2>
                    <h2>€<?= number_format($totalConImpuestos, 2) ?></h2>
                    <h2>AHORRO TOTAL</h2>
                    <h2>€<?= number_format($ahorroTotal, 2) ?></h2>
                </div>
            </div>
        </section>
                <?php if (isset($_SESSION['alerta'])): ?>
            <div class="alert alert-<?= $_SESSION['alerta']['tipo'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['alerta']['mensaje'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['alerta']); // Eliminar la alerta después de mostrarla ?>
        <?php endif; ?>
        
    </main>

    <footer>

    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>