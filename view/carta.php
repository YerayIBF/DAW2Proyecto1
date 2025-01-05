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
            <a>Leer más >></a>
        </section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <div class="filtro-sidebar">
                        <div class="filtro-seccion">
                            <div class="filtro-titulo"><span class="flecha-filtro">›</span>Estilo del plato </div>
                            <div class="filtro-contenido">
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="estilo1">
                                    <label for="estilo1">Entrantes</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="estilo2">
                                    <label for="estilo2">Principales</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="estilo3">
                                    <label for="estilo3">Postres</label>
                                </div>
                            </div>
                        </div>
                        <div class="filtro-seccion">
                            <div class="filtro-titulo"><span class="flecha-filtro">›</span>Método de cocción</div>
                            <div class="filtro-contenido">
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="metodo1">
                                    <label for="metodo1">Al horno</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="metodo2">
                                    <label for="metodo2">A la plancha</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="metodo3">
                                    <label for="metodo3">Frito</label>
                                </div>
                            </div>
                        </div>
                        <div class="filtro-seccion">
                            <div class="filtro-titulo"><span class="flecha-filtro">›</span>Acompañamientos</div>
                            <div class="filtro-contenido">
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="acomp1">
                                    <label for="acomp1">Con arroz</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="acomp2">
                                    <label for="acomp2">Con patatas</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="acomp3">
                                    <label for="acomp3">Con salsa</label>
                                </div>
                            </div>
                        </div>
                        <div class="filtro-seccion">
                            <div class="filtro-titulo"><span class="flecha-filtro">›</span>Propiedades nutricionales</div>
                            <div class="filtro-contenido">
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="prop1">
                                    <label for="prop1">Sin gluten</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="prop2">
                                    <label for="prop2">Alta en calorías</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="prop3">
                                    <label for="prop3">Bajo en calorías</label>
                                </div>
                            </div>
                        </div>
                        <div class="filtro-seccion">
                            <div class="filtro-titulo"><span class="flecha-filtro">›</span>Cantidad de porciones</div>
                            <div class="filtro-contenido">
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="porc1">
                                    <label for="porc1">Individual</label>
                                </div>
                                <div class="filtro-opcion">
                                    <input type="checkbox" id="porc2">
                                    <label for="porc2">Para compartir</label>
                                </div>
                            </div>
                        </div>
                        <div class="filtro-seccion">
                            <div class="filtro-titulo"><span class="flecha-filtro">›</span>Rango de Precio</div>
                            <div class="filtro-contenido">
                                <div class="rango-filtro">
                                    <input type="number" class="entrada-rango" placeholder="Min €">
                                    <span>-</span>
                                    <input type="number" class="entrada-rango" placeholder="Max €">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="contenedor-seleccion-ordenar mb-3">
                        <select class="seleccion-ordenar">
                            <option>ORDENAR POR:</option>
                            <option>Precio: menor a mayor</option>
                            <option>Precio: mayor a menor</option>
                            <option>Más populares</option>
                        </select>
                    </div>
                    <hr class="hr-margen">
                    <div class="row">
                        <?php foreach ($productos as $producto) { ?>
                            <div class="col-md-3 mb-3">
                                <div class="plato-card text-center">
                                    <img src="img/<?= htmlspecialchars($producto->getImagen()); ?>" class="plato-img" alt="<?= htmlspecialchars($producto->getNombre()); ?>">
                                    <form action="?controller=producto&action=addProducto" method="POST">
                                        <input type="hidden" name="ID_Producto" value="<?= $producto->getID_Producto(); ?>">
                                        <button type="submit" class="btn-anadir">AÑADIR AL CARRITO</button>
                                    </form>
                                </div>
                                <div class="texto-producto">
                                    <h5><?= htmlspecialchars($producto->getNombre()); ?></h5>
                                    <p>DESDE €<?= htmlspecialchars($producto->getPrecio()); ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/filtroDropdown-carta.js"></script>
</body>
</html>