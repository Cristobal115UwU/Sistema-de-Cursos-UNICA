<h1>Crear Grupo</h1>
<main class="contenedor seccion">
    <h1>Crear Instructor</h1>
    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>
    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php include __DIR__ . '/formularioGrupo.php';?>
        <input type="submit" value="Crear Instructor" class="boton boton-verde">
    </form>    
</main>


<h1>Checar Grupos existentes</h1>