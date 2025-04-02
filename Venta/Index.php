<?php
include 'Conexion.php';
include 'Mostrar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <style>
        :root {
            --rosa-claro: #FFF0F5;
            --rosa-pastel: #FFD1DC;
            --rosa-medio: #FF85A2;
            --rosa-oscuro: #E75480;
            --texto-oscuro: #4A4A4A;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--rosa-claro);
            color: var(--texto-oscuro);
            margin: 0;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
            color: var(--rosa-oscuro);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--rosa-pastel);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(231, 84, 128, 0.1);
        }
        
        .btn-nuevo {
            background-color: var(--rosa-oscuro);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .btn-nuevo:hover {
            background-color: var(--rosa-medio);
            transform: translateY(-2px);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: var(--rosa-pastel);
            color: var(--rosa-oscuro);
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #F0F0F0;
        }
        
        tr:hover {
            background-color: rgba(255, 209, 220, 0.3);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: var(--rosa-oscuro);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        .btn-guardar {
            background-color: var(--rosa-oscuro);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .btn-cancelar {
            background-color: #ddd;
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .mensaje {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .exito {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Ventas</h1>
        
        <!-- Mensajes de éxito/error -->
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="mensaje exito">
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="mensaje error">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <button class="btn-nuevo" onclick="document.getElementById('modalNuevo').style.display='block'">
            Nueva Venta
        </button>
        
        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha</th>
                    <th>Factura</th>
                    <th>ID Cliente</th>
                    <th>Método Pago</th>
                    <th>Estado</th>
                    <th>Monto Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php mostrarVentas(); ?>
            </tbody>
        </table>
    </div>
    
    <!-- Modal para Nueva Venta -->
    <div id="modalNuevo" class="modal">
        <div class="modal-content">
            <span style="float:right;cursor:pointer" onclick="document.getElementById('modalNuevo').style.display='none'">&times;</span>
            <h2>Agregar Nueva Venta</h2>
            
            <form action="Insertar.php" method="POST">
                <div class="form-group">
                    <label for="fecha_venta">Fecha de Venta</label>
                    <input type="date" id="fecha_venta" name="fecha_venta" required>
                </div>
                
                <div class="form-group">
                    <label for="fact_code">Código de Factura</label>
                    <input type="text" id="fact_code" name="fact_code" required>
                </div>
                
                <div class="form-group">
                    <label for="id_cliente">ID Cliente</label>
                    <input type="number" id="id_cliente" name="id_cliente" required>
                </div>
                
                <div class="form-group">
                    <label for="monto_total">Monto Total</label>
                    <input type="number" step="0.01" id="monto_total" name="monto_total" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago</label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Entregada">Entregada</option>
                        <option value="Pagada">Pagada</option>
                        <option value="En proceso">En proceso</option>
                    </select>
                </div>
                
                <div style="margin-top:20px">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" class="btn-cancelar" onclick="document.getElementById('modalNuevo').style.display='none'">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            if (event.target == document.getElementById('modalNuevo')) {
                document.getElementById('modalNuevo').style.display = "none";
            }
        }
    </script>
</body>
</html>