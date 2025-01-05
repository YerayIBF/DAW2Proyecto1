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
        <section class="seccion1-footer">
            <div class="ContenedorFooter">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <h3 class="tituloForm">Únete a la familia April</h3>
                        <p><strong>Cupones de descuento para tus próximas comidas.</strong> Descubre nuestras últimas promociones, platos exclusivos y beneficios solo para miembros.</p>
                    </div>
                    <div class="col-md-6">
                        <form class="formulario-Footer">
                            <input type="email" class="" placeholder="Dirección de correo electrónico">
                            <button class="boton-footer">SUSCRIBIRSE</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <div class="imagen-overlay">
            <img src="img/LetraALogo.png" alt="Imagen logo letra A" class="img-fluid logo-overlay">
        </div>

          <div class="ContenedorFooter">
            <section class="row text-center text-md-start  seccion2-footer footerLinks"  >
            <div class="col-md-2 ">
                <h6>Menu</h6>
                <p><a href="#">Preguntas frecuentes</a></p>
                <p><a href="#">Cuenta y usuario</a></p>
                <p><a href="#">Pedidos</a></p>
                <p><a href="#">Información nutricional</a></p>
                <p><a href="#">Métodos de pago</a></p>
                <p><a href="#">Reservas</a></p>
            </div>
            <div class="col-md-2">
                <h6>Experiencia April</h6>
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
                <h6>Fechas especiales</h6>
                <p><a href="#">Día del Padre</a></p>
                <p><a href="#">Sant Jordi</a></p>
                <p><a href="#">Día de la Madre</a></p>
                <p><a href="#">Todos los Santos</a></p>
                <p><a href="#">Navidad</a></p>
                <p><a href="#">Black Friday</a></p>
                <p><a href="#">San Valentín</a></p>
            </div>
            <div class="col-md-2">
                <h6>Tipo de platos</h6>
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
                <p><a href="#" class="mb-3">Ensalada del Medio Oriente</a></p>
            </div>
            <div class="col-md-4">
                <div class="iconos-sociales ">
                    <a href="#"><i class="bi bi-facebook"><img src="img/iconoFacebook.png" alt="icono facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"><img src="img/iconoInstagram.png" alt="icono instagram"></i></a>
                    <a href="#"><i class="bi bi-pinterest"><img src="img/iconoPinterest.png" alt="icono pinterest"></i></a>
                </div>
                <p class="footerDescripción">Nos especializamos en platos vegetarianos y veganos, utilizando ingredientes frescos y locales de proveedores cercanos. Cada plato está elaborado para ofrecer un sabor auténtico y una experiencia culinaria memorable.</p>
            </div>
            </section>
            </div>

            <div class="row footer-bottom">
            <div class="col-12 d-flex justify-content-center select-container">
                <div class="select-pais">
                    <select class=" d-inline w-auto" id="countrySelect">
                        <option value="es" data-image="img/BanderaEspaña.png" selected>España</option>
                        <option value="po" data-image="img/portugalBandera.svg">Portugal</option>
                    </select>
                    <select class=" d-inline w-auto">
                        <option selected>Español</option>
                        <option>Portugués</option>
                    </select>
                </div>
            </div>
        </div>
    </footer>
    <script src="js/select2footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>