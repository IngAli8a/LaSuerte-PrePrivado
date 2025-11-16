<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SISTEMA_NOMBRE; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body>
    <div class="contenedor-principal">
  
        <nav class="navbar">
            <div class="navbar-container">
                <div class="navbar-brand">
                    <h1><?php echo EMPRESA; ?></h1>
                    <p class="sistema-subtitulo">Sistema de Loterías</p>
                </div>
                
                <?php if ($vendedor): ?>
               
                
                <div class="navbar-user">
                    <span class="user-info">
                        Hola, <strong><?php echo htmlspecialchars($vendedor['nombre']); ?></strong>
                    </span>
                    <a href="<?php echo BASE_URL; ?>auth/logout" class="btn-logout">Salir</a>
                </div>
                <?php endif; ?>
            </div>
        </nav>
        

        <main class="main-content"></main>