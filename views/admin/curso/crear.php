
<main class="contenedor seccion">
    <h1>Crear Curso</h1>
    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>
    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php include __DIR__ . '/formularioCurso.php';?>
        <input type="submit" value="Crear Curso" class="boton boton-verde">
    </form>    
</main>