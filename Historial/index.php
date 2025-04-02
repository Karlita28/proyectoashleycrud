<?php
include 'conexion.php';
include 'mostrar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Ventas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --rosa-claro: #FFF0F5;
            --rosa-pastel: #FFD1DC;
            --rosa-medio: #FF85A2;
            --rosa-oscuro: #E75480;
            --verde: #28a745;
            --rojo: #dc3545;
            --azul: #007bff;
            --texto-oscuro: #4A4A4A;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--rosa-claro);
            color: var(--texto-oscuro);
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 20px auto;
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(231, 84, 128, 0.1);
        }
        
        h1 {
            text-align: center;
            color: var(--rosa-oscuro);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--rosa-pastel);
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .btn-nuevo {
            background-color: var(--rosa-oscuro);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        .btn-nuevo:hover {
            background-color: var(--rosa-medio);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 8px rgba(231, 84, 128, 0.2);
        }
        
        .search-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .search-filter input, 
        .search-filter select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 14px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        
        th {
            background-color: var(--rosa-pastel);
            color: var(--rosa-oscuro);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        
        tr:hover {
            background-color: rgba(255, 209, 220, 0.2);
        }
        
        .estado-pendiente {
            background-color: #fff3cd;
        }
        
        .estado-pagada {
            background-color: #d4edda;
        }
        
        .estado-cancelada {
            background-color: #f8d7da;
        }
        
        .mensaje {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .exito {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid var(--verde);
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--rojo);
        }
        
        .btn-accion {
            padding: 6px 10px;
            margin: 0 3px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            transition: all 0.2s;
        }
        
        .btn-editar {
            background-color: var(--azul);
        }
        
        .btn-eliminar {
            background-color: var(--rojo);
        }
        
        .btn-detalle {
            background-color: var(--verde);
        }
        
        .btn-accion:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-end {
            text-align: right;
        }
        
        .py-4 {
            padding-top: 16px;
            padding-bottom: 16px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-cash-register"></i> Gestión de Ventas</h1>
        
        <!-- Mensajes de éxito/error -->
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="mensaje exito">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="mensaje error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="header-actions">
            <a href="insertar.php" class="btn-nuevo">
                <i class="fas fa-plus"></i> Nueva Venta
            </a>
            
            <div class="search-filter">
                <input type="text" placeholder="Buscar venta..." id="buscar">
                <select id="filtro-estado">
                    <option value="">Todos los estados</option>
                    <option value="Pendiente">Pendientes</option>
                    <option value="Pagada">Pagadas</option>
                    <option value="Cancelada">Canceladas</option>
                </select>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="tabla-ventas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Pago</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php mostrarVentas(); ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Función para filtrar la tabla
        document.getElementById('filtro-estado').addEventListener('change', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tabla-ventas tbody tr');
            
            filas.forEach(fila => {
                const estado = fila.cells[6].textContent.toLowerCase();
                if (filtro === '' || estado.includes(filtro)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
        
        // Función para buscar en la tabla
        document.getElementById('buscar').addEventListener('input', function() {
            const busqueda = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tabla-ventas tbody tr');
            
            filas.forEach(fila => {
                let mostrar = false;
                for (let i = 0; i < fila.cells.length; i++) {
                    if (fila.cells[i].textContent.toLowerCase().includes(busqueda)) {
                        mostrar = true;
                        break;
                    }
                }
                fila.style.display = mostrar ? '' : 'none';
            });
        });
    </script>
</body>
</html>