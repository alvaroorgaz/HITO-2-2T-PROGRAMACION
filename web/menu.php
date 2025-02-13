<nav>
    <ul>
        <?php if (isset($_SESSION["usuario_id"])): ?>
            <li><a href="tareas.php">Mis Tareas</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        <?php else: ?>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="login.php">Iniciar Sesión</a></li>
            <li><a href="registro.php">Registrarse</a></li>
        <?php endif; ?>
    </ul>
</nav>

<br>

<style>
    /* Estilos del menú */
    nav {
        background:rgb(29, 41, 54);
        height: 50px;
        padding-top: 25px;
    }

    ul {
        list-style: none;
        margin: 0;
        padding: 0;
        text-align: center;
    }

    li {
        display: inline;
        margin: 0 15px;
    }

    nav a {
        color: black;
        text-decoration: none;
        font-weight: bold;
    }

    nav a:hover {
        text-decoration: underline;
    }
</style>
