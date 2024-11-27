<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito April</title>
    <link rel="stylesheet" href="css/carrito.css">
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


            <table>
             <tbody>

             </tbody>
            </table>



        <?php } ?>

    </main>
</body>

</html>