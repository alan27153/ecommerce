<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <title>Inicio</title>
    
</head>
<body>

    <div class="header-container">
        <div class="header-title">FERJO SEGURIDAD</div>
        <span class="material-symbols-outlined menu-icon">menu</span>
    </div>

    <div class="espaciador-header"></div>

    <div class="container-principal">

        <nav class="sidebar">
            <a class="sidebar-inicio sidebar-opcion" href="#">
                <span class="material-symbols-outlined sidebar-icon">home</span>
                Inicio
            </a>
            <a class="sidebar-productos sidebar-opcion" href="#">
                <span class="material-symbols-outlined sidebar-icon">inventory_2</span>
                Productos
            </a>
            <a class="sidebar-categorias sidebar-opcion" href="#">
                <span class="material-symbols-outlined sidebar-icon">category</span>
                Categorías
            </a>
            <a class="sidebar-pedidos sidebar-opcion" href="#">
                <span class="material-symbols-outlined sidebar-icon">assignment</span>
                Pedidos
            </a>
        </nav>

        <div class="espaciador-sidebar"></div>

        <main class="container-main">

            <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
            <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        <h1 class="item">Panel de contenido</h1>
            <p class="item">Este es el área donde va el contenido principal de la aplicación. Puedes agregar tablas, gráficos, formularios o lo que necesites.</p>
            <p class="item">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum, urna ut tincidunt dignissim, sapien lorem fringilla dolor, a pretium nulla metus a risus.</p>
        
        </main>
        
    </div>

    <style>
/* #ff9d0a */
/* #016dbc */
        * {
            margin: 0;
            padding: 0;
            font-size: 16px;
            box-sizing: border-box;

        }

        .espaciador-header {
            height: 10vh; /* misma altura que el header */
        }

        .header-container {
            width: 100%;
            height: 10vh;
            display: flex;
            align-items: center;    
            background: #6464ac;
            position: fixed;
        }

        .header-title{
            width: 20%;
            padding-left: 0.625rem;
            font-size: 1.25rem;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 600;
            color: #fff;
            cursor: default;
        }

        .menu-icon {
            padding-left: 0.625rem;
            font-size: 1.875rem; 
            color: #fff;
            cursor: pointer; 
        }

        .container-principal{
            width: 100%;
            height: 90vh;
            display: flex;
        }

        .sidebar{
            width: 20%;
            height: 90vh;
            background-color: #202c34;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 20px; 
        }

        .espaciador-sidebar {
            width: 20%; 
            height: 90vh; 
        }


        .sidebar-opcion{
            color: #fff;
            text-decoration: none;
            height: 50px;
            display: flex;
            align-items: center;

        }

        .sidebar-icon{
            padding: 0 10px 0 0;
        }

        .sidebar.oculto {
            display: none;
        } 

        .container-main{
            background-color: blue;
            width: 80%;
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* máximo 2 columnas, cada una toma igual espacio */
            gap: 10px; /* espacio entre elementos */

        }

        .item {
            background-color: #4caf50;
            color: white;
            padding: 20px;
            text-align: center;
        }




        @media (max-width: 600px) {
            /* .container {
                flex-direction: column;
            } */
        }

    </style>

    <script>
        const menuIcon = document.querySelector('.menu-icon');
        const sidebar = document.querySelector('.sidebar');

        menuIcon.addEventListener('click', () => {
            sidebar.classList.toggle('oculto');
        });
    </script>

</body>
</html>
