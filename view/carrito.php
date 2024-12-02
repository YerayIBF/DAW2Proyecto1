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
                <a class="subtitulo-carrito"> Seguir comprando</a>
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
                                                <div class="quitar-producto"> <a href="">Quitar</a></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-precio text-end">€<?= $producto->getPrecio() ?></td>
                                    <td class="td-Cantidad text-end">
                                        <input class="cantidad-input" type="number" value="<?= $producto->getCantidad() ?>" min="1">
                                    </td>
                                    <td class="td-Total text-end">€2</td> <!-- Calcular precio total por cantidad de producto -->
                                </tr>
                                <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <section>
                <form>
                            <label>Añade tu DEDICATORIA especial o comentarios del pedido.</label>
                            <p></p>
                        </div>
                        <div class="div-display-flexdiv-display-flex">
                            <p>subtotal</p>
                            <p>precio total </p>
                        </div>
                    </div>
                    <div>
                        <textarea></textarea>
                        <p>Impuesto incluido. Los gastos de envío se calculan en la pantalla de pagos.</p>
                    </div>
                    <button type="submit">CONTINUAR CON EL ENVÍO</button>
                </form>

            </section>


        <?php } ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </main>
</body>

</html>