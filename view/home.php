<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home April</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <main>
        <section class="banner">

            <div class="contenedor-banner">
                <h2>LLENA TU MESA DE SABOR Y SALUD</h2>
                <h1>Los platos que<br> <i>nutren tu cuerpo y alma</i></h1>
                <a href="?controller=producto&action=carta"><button><b>VER CARTA</b></button></a>
            </div>
        </section>

        <section class="seccion1-titulo">
            <h2>Platos</h2>
            <p>Los mas vendidos</p>
        </section>

        <section class="container-fluid">
            <div class="row">
                <?php
                $contador = 0;
                foreach ($productos as $producto) {
                    if ($contador >= 4) break;
                ?>
                    <div class="col-md-3">
                        <div class="plato-card text-center">
                            <img src="img/<?= $producto->getImagen(); ?>" class="plato-img" alt="<?= $producto->getNombre(); ?>">
                            <form action="?controller=producto&action=addProducto" method="POST">
                                <input type="hidden" name="ID_Producto" value="<?= $producto->getID_Producto(); ?>">
                                <button type="submit" class="btn-anadir">AÑADIR AL CARRITO</button>
                            </form>
                        </div>
                        <div class="texto-producto">
                            <h5><?= $producto->getNombre(); ?></h5>
                            <p>DESDE €<?=$producto->getPrecio(); ?></p>
                        </div>
                    </div>
                <?php
                    $contador++;
                }
                ?>
                <div class="text-center my-5">
                    <button class="btn-ver-platos"><b>VER PLATOS</b></button>
                </div>
            </div>
        </section>

        <section class="especialidades-seccion my-5">
            <div class="row">
                <div class="col-md-4 d-flex flex-column justify-content-center">
                    <h2>¡TE CONTAMOS NUESTRAS ESPECIALIDADES!</h2>
                    <p>Todo lo que necesitas saber sobre nuestros platos y cómo disfrutar de una experiencia gastronómica única. Descubre nuestro menú y las delicias que ofrecemos en cada estación, diseñadas para satisfacer todos los gustos.</p>
                    <a href="#" class="ver-todos">VER TODOS</a>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="img/Seccion2.1.webp" class="card-img-top correcionImagen" alt="Terraza">
                            </div>
                            <h5 class="especialidades-seccion-titulo">Cómo disfrutar este otoño de nuestra terraza</h5>
                            <a href="#" class="ver-mas">VER MAS</a>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="img/Seccion2.2.avif" class="card-img-top correcionImagen" alt="Cena Perfecta">
                            </div>
                            <h5 class="especialidades-seccion-titulo">Cómo preparar una cena perfecta</h5>
                            <p class="especialidades-seccion-texto">Consejos de nuestros chefs para preparar una cena inolvidable en casa.</p>
                            <a href="#" class="ver-mas">VER MAS</a>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="img/Seccion2.3.jpg" class="card-img-top correcionImagen" alt="Platos recomendados">
                            </div>

                            <h5 class="especialidades-seccion-titulo">Los platos más recomendados para tu paladar</h5>
                            <p class="especialidades-seccion-texto">Una selección de los platos más populares que no te puedes perder.</p>
                            <a href="#" class="ver-mas">VER MAS</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="menu-seccion container-fluid my-5">
            <div class="row">
                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <img class="menu-seccion-img" src="img/Seccion3.png">
                </div>

                <div class="col-md-6 d-flex flex-column">
                    <h2 class="menu-seccion-titulo">Nuestros <i>menus</i> de <br>cada <i>estacion</i></h2>
                    <p class="menu-seccion-subtitulo">Durante todo el año disponemos de diferentes menus para que nuestros clientes <br>puedan degustar platos con ingredientes de temporada.</p>
                    <p class="menu-seccion-descuento ">20% de descuento</p>
                </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>