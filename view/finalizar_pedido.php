<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>April</title>
    <link rel="stylesheet" href="css/finalizar-pedido.css">
</head>
<body>
    <header>
        <div class="header-contenedor">
            <div>
                <img src="img/Logo.png">   
            </div>
            <a ><img class="icono-bolsa" src="img/bolso-Carrito.png"></a>  
        </div>
    </header>
    <hr>
    <main>
    <form method="POST" action="?controller=producto&action=finalizarPedido">
        <label>Dirección</label>
        <input type="text" name="direccion" placeholder="Introduce tu dirección">
        <button type="submit">Finalizar pedido</button>
    </form>

    <form method="POST" action="?controller=producto&action=aplicarCupon">
        <label>Oferta</label>
        <input type="text" name="Oferta" placeholder="Introduce tu cupón">
        <button type="submit">Añadir cupón</button>
    </form>
    </main>

    <footer>
        
    </footer>
</body>
</html>