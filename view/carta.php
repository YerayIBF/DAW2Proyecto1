<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/carta.css">

</head>
<body>
    <main>
    <section class="Seccion-titulo-carta">
        <h1>Todos los platos</h1>
        <p>Disfruta <b>platos gourmet</b> y transforma cualquier espacio en una experiencia culinaria única.</p>
        <a>Leer más>></a>
    </section>
    <hr>
    <section class="container-sm">
            <div class="row">
                <?php foreach ($productos as $producto) {?>
                    <div class="col-md-3">
                        <div class="plato-card text-center">
                            <img src="img/<?= htmlspecialchars($producto->getImagen()); ?>" class="plato-img" alt="<?= htmlspecialchars($producto->getNombre()); ?>">
                            <button class="btn-anadir">VER PRODUCTO</button>
                        </div>
                        <div class="texto-producto">
                            <h5><?= htmlspecialchars($producto->getNombre()); ?></h5>
                            <p>DESDE €<?= htmlspecialchars($producto->getPrecio()); ?></p>
                        </div>
                    </div>
                <?php }?>
            </div>
    </section>>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        </main>
</body>
</html>