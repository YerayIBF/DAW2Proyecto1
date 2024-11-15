<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login April</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <main>
<h1 class="login-titulo">Ingresar</h1>
    <section class="contenedor">
       
        <form>
            <label class="label-login" for="correo">Correo electrónico</label>
            <br>
            <input type="email" name="correo" class="inputs-login"> 
            <br>
            <label class="label-login" for="contraseña">Contraseña</label>
            <br>
            <input type="password" name="contraseña" class="inputs-login">
            <br>
            <a class="a-login">¿Olvidó su contraseña?</a>
            <br>
            <input class="boton-login" type="submit" value="REGISTRARSE">
            <br>
            <a href="?controller=producto&action=registrarte" class="a-login">Crear cuenta</a>
        </form>
    </section>
</main>
</body>
</html>
