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
    <main>
        <section class="container-fluid">
            <div class="text-center mb-4">
                <h1 class="h1-cuenta">Mi cuenta</h1>
                <a href="?controller=producto&action=cerrarSession" class="a-cuenta">Cerrar sesión</a>
            </div>
            <div class="row">
                <div class="col-md-8  ">
                    <h2 class="h2-cuenta margen-h2-cuenta">HISTORIAL DE PEDIDOS</h4>
                    <?php if ($pedidos == null){ ?>
                        <p>No ha realizado algún pedido aún.</p>
                    <?php } else{ ?>
                        <?php foreach ($pedidos as $pedido) {?>
                        <h2>Pedido: </h2>
                        <h5><?= $pedido->getFecha_Pedido(); ?></h5>
                        <h5><?= $pedido->getPrecio_Total(); ?></h5>
                        <h5><?= $pedido->getDireccion(); ?></h5>
                        <h5><?= $pedido->getDedicatoria(); ?></h5>  
                        <h5><?= $pedido->getEstado(); ?></h5>
                    <?php }?>
                    <?php }?>
                </div>

                <div class="col-md-4">
                    <h2 class="h2-cuenta margen-h2-cuenta">DETALLES DE LA CUENTA</h4>
                    <a href="#" class="boton-cuenta">VER DIRECCIONES (0)</a>
                </div>
            </div>
        </section>
    </main>
</body>

</html>