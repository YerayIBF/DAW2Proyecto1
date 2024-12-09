<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito April</title>
    <link rel="stylesheet" href="css/carrito.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <main>
        <?php if (empty($_SESSION['carrito']) or count($_SESSION['carrito']) == 0) { ?>
            <section class="No-Productos">
                <h1 class="titulo-carrito-seguir">Carrito</h1>
                <p class="subtitulo-seguir">Su carrito actualmente está vacío.</p>
                <a><button class="boton-seguir">SEGUIR COMPRANDO -></button></a>
            </section>
        <?php } else { ?>
            <section class="titulo-subtitulo">
                <h1 class="titulo-carrito">Carrito</h1>
                <a class="subtitulo-carrito" href="?controller=producto&action=carta"> Seguir comprando</a>
            </section>

            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="card border-0">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th class="th-producto">PRODUCTO</th>
                                    <th class="th-precio text-end">PRECIO</th>
                                    <th class="th-Cantidad text-end">CANTIDAD</th>
                                    <th class="th-Total text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($_SESSION['carrito'])) { ?>
                                    <?php foreach ($_SESSION['carrito'] as $producto) { ?>
                                        <tr>
                                            <td class="td-producto">
                                                <div class="d-flex align-items-center">
                                                    <div class="tarjeta-espaciado">
                                                        <div class="tarjeta-carrito">
                                                            <img src="img/<?= $producto->getImagen(); ?>" alt="<?= $producto->getNombre(); ?>" class="img-carta">
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <h5 class="nombre-producto"><?= $producto->getNombre(); ?></h5>
                                                        <ul class="descripcion-producto">
                                                            <li><?= $producto->getDescripcion(); ?></li>
                                                        </ul>
                                                        <div class="quitar-producto">
                                                            <a href="?controller=producto&action=quitarProductoCarrito&id=<?= $producto->getID_Producto(); ?>">Quitar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="td-precio text-end">€<?= $producto->getPrecio() ?></td>
                                            <td class="td-Cantidad text-end">
                                                <form method="POST" action="?controller=producto&action=actualizarCantidad">
                                                    <input type="hidden" name="id_producto" value="<?= $producto->getID_Producto(); ?>">
                                                    <input
                                                        class="cantidad-input"
                                                        type="number"
                                                        name="cantidad"
                                                        value="<?= $producto->getCantidad(); ?>"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="td-Total text-end">€<?= number_format($producto->totalProducto, 2) ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form method="POST" action="?controller=producto&action=paginaFinalizarPedido">
                <div class="container-fluid ajuste-carrito">
                    <div class="row">

                        <div class="col-lg-6 ">
                            <label for="dedicatoria" class="form-label  label-dedicatoria">Añade tu DEDICATORIA especial o comentarios del pedido.</label>
                            <textarea id="dedicatoria" name="dedicatoria" class="form-control" placeholder=""></textarea>
                        </div>

                        <div class="col-lg-6 text-end">
                            <div class="mb-3">
                                <span class="Subtotal-carrito">Subtotal</span>
                                <span class="Subtotal-precio-carrito">€<?= number_format($totalCarrito, 2) ?></span>
                            </div>
                            <div class=" text-muted impuesto-texto">
                                Impuesto incluido. <a href="#" class=" a-impuesto-texto">Los gastos de envío</a> se calculan en la pantalla de pagos.
                            </div>
                            <input type="submit" class=" boton-finalizar-compra"  value="CONTINUAR CON EL ENVÍO">
                           
                        </div>
                    </div>
                </div>
            </form>


        <?php } ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </main>
</body>

</html>