<?php

declare(strict_types=1);

/** @var int $visitas Calculado en includes/bootstrap.php */
// El año se calcula para no tener que editar el footer cada enero.
$anioActual = date('Y');
?>
</main>

<footer class="site-footer">
    <p>&copy; <?= e($anioActual) ?> AthleteLog · Hecho por Daniel Figuereo</p>
    <p class="visitas">Visitas: <?= e($visitas) ?></p>
</footer>

</body>

</html>