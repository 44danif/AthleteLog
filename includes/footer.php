<?php

declare(strict_types=1);

// El año se calcula para no tener que editar el footer cada enero.
$anioActual = date('Y');
?>
</main>

<footer class="site-footer">
    <p>&copy; <?= e($anioActual) ?> AthleteLog · Hecho por Daniel Figuereo</p>
</footer>

</body>

</html>