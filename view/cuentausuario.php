<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Usuario April</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/cuentausuario.css">
</head>

<body>
    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?php echo $_SESSION['alert']['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['alert']['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
    <main>
        <section class="container-fluid">
            <div class="text-center mb-4">
                <h1 class="h1-cuenta">Mi cuenta</h1>
                <a href="?controller=cuenta&action=cerrarSession" class="a-cuenta">Cerrar sesión</a>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h2 class="h2-cuenta margen-h2-cuenta">HISTORIAL DE PEDIDOS</h2>
                    <?php if ($pedidos == null) { ?>
                        <p>No ha realizado algún pedido aún.</p>
                    <?php } else { ?>
                        <div id="pedidosContainer">
                            <?php foreach ($pedidos as $pedido) { ?>
                                <div class="pedido-card mb-3 p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h4>Pedido #<?= $pedido->getID_Pedido(); ?></h4>
                                            <p class="mb-1">Fecha: <?= $pedido->getFecha_Pedido(); ?></p>
                                            <p class="mb-1">Estado: <span class="badge bg-custom"><?= $pedido->getEstado(); ?></span></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="mb-1">Total: €<?= number_format($pedido->getPrecio_Total(), 2); ?></p>
                                            <p class="mb-1">Dirección: <?= $pedido->getDireccion(); ?></p>
                                            <?php if ($pedido->getDedicatoria()) { ?>
                                                <p class="mb-1">Dedicatoria: <?= $pedido->getDedicatoria(); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-4 DireccionEspaciocuenta">
                    <h2 class="h2-cuenta margen-h2-cuenta">DETALLES DE LA CUENTA</h4>
                        <a href="#" class="boton-cuenta">VER DIRECCIONES (0)</a>
                </div>
            </div>
        </section>
    </main>
 
</body>



</html>