<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
</head>
<body>
    <main>
        <section>
            <h1>Crear cuenta</h1>
            <form action="?controller=producto&action=registrarte" method="POST">   
                <label for="nombre">Nombre</label>
                <br>
                <input type="text" name="nombre" required>
                <br>
                <label for="correo">Correo electrónico</label>
                <br>
                <input type="email" name="correo" required>
                <br>
                <label for="contraseña">Contraseña</label>
                <br>
                <input type="password" name="contraseña" required>
                <br>
                <br>
                <input type="submit" value="CREAR">
            </form>
        </section>
    </main>
</body>
</html>
