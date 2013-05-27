<footer>
    <a class="footer-subir" href="javascript:void(0);" title="Subir"><i class="icon-circle-arrow-up"></i></a>
    <div class="container-fluid footer-wrapper">
        <div class="container">
            <div class="row-fluid">
                <div class="span4">
                    <header>
                        <h1>
                            Cerrado por reforma
                        </h1>
                    </header>
                    <ul class="icons-ul">
                        <li>
                            <i class="icon-li icon-star"></i><a href="#" title="">Sobre el proyecto</a>
                        </li>
                        <li>
                            <i class="icon-li icon-star"></i><a href="#" title="">¿Por qué Cerrado por Reforma?</a>
                        </li>
                        <li>
                            <i class="icon-li icon-star"></i><a href="#" title="">Créditos y agradecimientos</a>
                        </li>
                    </ul>
                    <a class="developed-by" href="http://lostiumproject.com" title="Visite nuestro bar">
                        Es un proyecto de: <span>Lostium Project</span>
                    </a>
                </div>

                <div class="span5 offset3 footer-main">
                    <header>
                        <h1>
                            Las empresas, España y la crisis
                        </h1>
                    </header>
                    <p>La crisis se está llevando por delante los sueños e ilusiones de muchos españoles. Este proyecto pretende <strong>documentar y visualizar</strong> la desaparición de negocios y empresas en España a lo largo de los últimos años. Pero tambien dar voz a los que quieran contar la <strong>historia de unos negocios</strong> que, en ocasiones tras décadas de servicio, se han visto <strong>abocados al cierre</strong>.</p>
                    <img src="<?php echo get_template_directory_uri() ?>/img/footer-buildings.png" alt="Nos vamos al garete, sí" />
                </div>

            </div>
        </div>


    </div>
</div>
</footer>

<!-- Modal genérico -->
<div id="cerradoModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cerradoModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <header>
            <h1 id="cerradoModalLabel">Sube tu foto al mapa</h1>
        </header>
    </div>
    <div class="modal-body">
        <?php include_once "upload.php" ?>
    </div>
</div>

<?php wp_footer(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41235801-1', 'cerradoporreforma.org');
  ga('send', 'pageview');

</script>
</body>
</html>