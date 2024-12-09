<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="css/registro.css">
</head>
<body>
    <main>
    <h1 class="registro-titulo">Crear cuenta</h1>
        <section class="contenedor">
            
            <form  class="form-registro"  action="?controller=producto&action=registrarte" method="POST">   
                <label class="label-registro" for="nombre">Nombre</label>
                <br>
                <input class="inputs-registro" type="text" name="nombre" required>
                <br>
                <label class="label-registro" for="correo">Correo electrónico</label>
                <br>
                <input class="inputs-registro" type="email" name="correo" required>
                <br>
                <label  class="label-registro" for="contraseña">Contraseña</label>
                <br>
                <input  class="inputs-registro" type="password" name="contraseña" required>
                <br>
                <br>
                <div class="centrar-boton">
                    <input type="submit" value="CREAR" class="boton-registro">
                </div>
            </form>
        </section>
    </main>
</body>
</html>
