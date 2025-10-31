<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Preguntados</title>
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f97316 0%, #ef4444 100%);
            display: flex;
            justify-content: center;
        }

        .navegador {
            display: flex;
            justify-content: center;
            column-gap: 5px;
        }

        .card {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 16px;
        }

        .container {
            margin-top: 50px;
        }

        table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        .table-responsive {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 10px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-dark text-white">
    <header class="bg-primary p-3 mb-4">
        <h1>Preguntados</h1>
        <nav class="navegador">
            <a class="text-decoration-none text-white" href="/">Inicio</a> |
            <a class="text-decoration-none text-white" href="/login">Inicio de sesión</a> |
            <a class="text-decoration-none text-white" href="/login/registro">Registro</a>
        </nav>
    </header>v