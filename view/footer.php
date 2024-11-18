<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer April</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/footer.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>

<body>
    <footer class="footer">
        <div class="container-fluid">
            <section class="row mb-5 seccion1-footer">
                <div class="col-md-6">
                    <h2>Únete a la familia April</h2>
                    <p><strong>Cupones de descuento para tus próximas comidas.</strong> Descubre nuestras últimas promociones, platos exclusivos y beneficios solo para miembros.</p>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <input type="email" class="form-control" placeholder="Dirección de correo electrónico">
                    <button class="boton-footer">SUSCRIBIRSE</button>
                </div>
            </section>

            <div class="container my-4">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <img src="" alt="Imagen logo letra A" class="img-fluid">
                    </div>
                </div>
            </div>

            <div class="row text-center text-md-start mb-5">
                <div class="col-md-2">
                    <h5>Menu</h5>
                    <p><a href="#">Preguntas frecuentes</a></p>
                    <p><a href="#">Cuenta y usuario</a></p>
                    <p><a href="#">Pedidos</a></p>
                    <p><a href="#">Información nutricional</a></p>
                    <p><a href="#">Métodos de pago</a></p>
                    <p><a href="#">Reservas</a></p>
                </div>
                <div class="col-md-2">
                    <h5>Experiencia April</h5>
                    <p><a href="#">Contacto</a></p>
                    <p><a href="#">Preguntas Frecuentes</a></p>
                    <p><a href="#">Envíos y Devoluciones</a></p>
                    <p><a href="#">Términos y condiciones</a></p>
                    <p><a href="#">Pago con SeQura</a></p>
                    <p><a href="#">Política de Privacidad</a></p>
                    <p><a href="#">Política de Cookies</a></p>
                    <p><a href="#">Sobre Nosotros</a></p>
                    <p><a href="#">Blog</a></p>
                </div>
                <div class="col-md-2">
                    <h5>Fechas especiales</h5>
                    <p><a href="#">Día del Padre</a></p>
                    <p><a href="#">Sant Jordi</a></p>
                    <p><a href="#">Día de la Madre</a></p>
                    <p><a href="#">Todos los Santos</a></p>
                    <p><a href="#">Navidad</a></p>
                    <p><a href="#">Black Friday</a></p>
                    <p><a href="#">San Valentín</a></p>
                </div>
                <div class="col-md-2">
                    <h5>Tipo de platos</h5>
                    <p><a href="#">Tzatziki</a></p>
                    <p><a href="#">Patatas Fritas</a></p>
                    <p><a href="#">Curry Tailandés</a></p>
                    <p><a href="#">Baba Ganoush</a></p>
                    <p><a href="#">Mezcla de Tempura</a></p>
                    <p><a href="#">Tacos</a></p>
                    <p><a href="#">Pastel de Arroz</a></p>
                    <p><a href="#">Albahaca Tailandesa</a></p>
                    <p><a href="#">Tortitas</a></p>
                    <p><a href="#">Pad-thai</a></p>
                    <p><a href="#">Pasta Boloñesa</a></p>
                    <p><a href="#">Ensalada del Medio Oriente</a></p>
                </div>
                <div class="col-md-4">
                    <div class="iconos-sociales mb-3">
                        <a href="#"><i class="bi bi-facebook"><img src="img/iconoFacebook.png" alt="icono facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"><img src="img/iconoInstagram.png" alt="icono instagram"></i></a>
                        <a href="#"><i class="bi bi-pinterest"><img src="img/iconoPinterest.png" alt="icono pinterest"></i></a>
                    </div>
                    <p>Nos especializamos en platos vegetarianos y veganos, utilizando ingredientes frescos y locales de proveedores cercanos. Cada plato está elaborado para ofrecer un sabor auténtico y una experiencia culinaria memorable.</p>
                </div>
            </div>


            <div class="row footer-bottom justify-content-center">
                <div class="col-md-4 text-center">
                    <div class="select-pais">
                        <select class="form-select d-inline w-auto" id="countrySelect">
                            <option value="es" data-image="img/BanderaEspaña.png"  selected>España</option>
                            <option value="fr" data-image="">Francia</option>
                            <option value="de" data-image="">Alemania</option>
                        </select>

                        <select class="form-select d-inline w-auto">
                            <option selected>Español</option>
                            <option>Francés</option>
                            <option>Alemán</option>
                        </select>
                    </div>
                </div>
            </div>
    </footer>
    <script src="js/select2footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>