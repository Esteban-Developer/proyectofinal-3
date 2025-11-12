<!-- Sección del Pie de Página -->
<footer class="footer-section">
    <div class="container">
        <div class="row" style="padding-bottom: 40px;">
            <div class="col-lg-3">
                <div class="footer-left">
                    <div class="footer-logo">
                        <a href="index.php">
                            <span>Inferno Colombia</span>
                        </a>
                    </div>
                    <ul>
                        <li>+57 304 225 6789</li>
                        <li>InfernoColombia@gmail.com</li>
                        <li>Bogotá, Av. Chiminangos, local Apto 2, Local C</li>
                    </ul>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="https://www.instagram.com/?hl=es" target="_blank"><i class="fa fa-instagram"></i></a>
                        
                        <a href="https://www.pinterest.com/" target="_blank"><i class="fa fa-pinterest"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 offset-lg-1">
                <div class="footer-widget">
                    <h5>Información</h5>
                    <ul>
                        <li><a href="index.php">Sobre Nosotros</a></li>
                        <li><a href="contact.php">Contacto</a></li>
                        <li><a href="index.php">Política de Privacidad</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="footer-widget" style="display: <?php if ($active == 'Register' || $active == 'Login') { echo 'none'; } ?>;">
                    <h5>Mi Cuenta</h5>
                    <ul>
                        <?php
                        if (!($_SESSION['customer_email'] == 'unset')) {
                            echo "<li><a href='account.php?orders'>Mis Pedidos</a></li>";
                        }
                        ?>
                        <li><a href="<?php
                            if (!($_SESSION['customer_email'] == 'unset')) {
                                echo 'shopping-cart.php';
                            } else {
                                echo 'login.php';
                            }
                        ?>">Carrito de Compras</a></li>

                        <li><a href="<?php
                            if (!($_SESSION['customer_email'] == 'unset')) {
                                echo 'check-out.php';
                            } else {
                                echo 'login.php';
                            }
                        ?>">Finalizar Compra</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="newslatter-item">
                    <h5>Mantente en contacto</h5>
                    <p>Recibe actualizaciones y ofertas especiales por correo electrónico.</p>
                    <form action="index.php" class="subscribe-form">
                        <input type="text" placeholder="Ingresa tu correo electrónico">
                        <button type="button">Suscribirse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.4/umd/popper.min.js" integrity="sha512-eUQ9hGdLjBjY3F41CScH3UX+4JDSI9zXeroz7hJ+RteoCaY+GP/LDoM8AO+Pt+DRFw3nXqsjh9Zsts8hnYv8/A==" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.zoom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" integrity="sha512-8vfyGnaOX2EeMypNMptU+MwwK206Jk1I/tMQV4NkhOz+W8glENoMhGyU6n/6VgQUhQcJH8NqQgHhMtZjJJBv3A==" crossorigin="anonymous"></script>
<script src="js/jquery.slicknav.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>

<!-- Script del Modo Oscuro -->
<script src="js/dark-mode.js"></script>