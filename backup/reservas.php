<?php
  session_start(); // Inicio de sesión
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Administración Hotel - Reservas</title>
      <link href="http://meyerweb.com/eric/tools/css/reset/reset.css" rel="stylesheet">
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="../css/main.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
     
      <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
  </head>
  <body>
    <?php
      if ( $_SESSION['logueadoAdmin'] == true){
      try {
        $conexion = new PDO("mysql:host=localhost;dbname=hotel;charset=utf8", "root");
      } catch (PDOException $e) {
        echo "No se ha podido establecer conexión con el servidor de bases de datos.<br>";
        die ("Error: " . $e->getMessage());
      }
      
      //Cálculos paginación
      $consultaTotal = $conexion->query("SELECT * FROM reserva");
      $totalFilas = $consultaTotal->rowCount();
      
      $TAMANO_PAGINA = 10;
      $pagina = $_GET["pagina"];
      if (!isset($pagina)) {
         $inicio = 0;
         $pagina = 1;
      }
      else {
         $inicio = ($pagina - 1) * $TAMANO_PAGINA;
      }
      //calculo el total de páginas
      $totalPaginas = ceil($totalFilas / $TAMANO_PAGINA);
      //Fin cálculos paginación
      $consulta = $conexion->query("SELECT * FROM reserva r "
        . "JOIN habitacion h ON (r.codHabitacion = h.codHabitacion) "
        . "JOIN cliente c ON (c.codCliente = r.codCliente) "
        . "ORDER BY r.fechaEntrada ASC, r.codHabitacion LIMIT " . $inicio . "," . $TAMANO_PAGINA);
    ?>
  
    <div class="container">
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Administración Hotel</a>
          </div>
          <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
              <li><a href="index.php">Clientes</a></li>
              <li><a href="adminHabitaciones.php">Habitaciones</a></li>
              <li class="active"><a href="reservas.php">Reservas</a></li>
             <!-- <li><a href="#">Page 3</a></li>-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php"><span class="glyphicon glyphicon-user"></span> <?= ucfirst($_SESSION['nombreAdmin'])?></a></li>
              <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></li>
            </ul>
          </div>
        </div>
      </nav>
     <div class="centrado">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
              <tr class="bg-primary">
                <th>codHabitacion</th>
                <th>Nombre</th>
                <th>Apellido1</th>
                <th>Apellido2</th>
                <th>DNI</th>
                <th>Fecha Entrada</th>
                <th>Fecha Salida</th>
                <th>Edición</th>
                <th>Borrado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                while ($reserva = $consulta->fetchObject()) {
              ?>
              <tr>
                <td><?= $reserva->codHabitacion ?></td>
                <td><?= $reserva->nombre ?></td>
                <td><?= $reserva->apellido1 ?></td>
                <td><?= $reserva->apellido2 ?></td>
                <td><?= $reserva->DNI ?></td>
                <td><?= $reserva->fechaEntrada ?></td>
                <td><?= $reserva->fechaSalida ?></td>
                <td>
                  <form name="modificarReserva" action="modificarReserva.php" method="POST">
                    <input type="hidden"  name="codHabitacion" value="<?= $reserva->codHabitacion ?>">
                    <input type="hidden"  name="codCliente" value="<?= $reserva->codCliente ?>">
                    <input type="hidden"  name="fechaEntrada" value="<?= $reserva->fechaEntrada ?>">
                    <input type="submit" class="btn btn-info" value="Editar" />
                  </form>
                </td>
                <td>
                  <form name="eliminarReserva" action="eliminarReserva.php" method="POST">
                    <input type="hidden"  name="codHabitacion" value="<?= $reserva->codHabitacion ?>">
                    <input type="hidden"  name="codCliente" value="<?= $reserva->codCliente ?>">
                    <input type="hidden"  name="fechaEntrada" value="<?= $reserva->fechaEntrada ?>">
                    <input type="submit" class="btn btn-danger" value="Eliminar" />
                  </form>
                </td>
              </tr>
              <?php } ?>
              <tr>
              <?php
              //Paginación
              $url = "reservas.php";
              if ($totalPaginas > 1) {
                if ($pagina != 1){
                  echo '<a href="'.$url.'?pagina='.($pagina-1).'">Anterior </a>';
                }
                for ($i=1;$i<=$totalPaginas;$i++) {
                  if ($pagina == $i){
                     //si muestro el índice de la página actual, no coloco enlace
                     echo $pagina;
                  }else{
                    //si el índice no corresponde con la página mostrada actualmente,
                    //coloco el enlace para ir a esa página
                    echo '  <a href="'.$url.'?pagina='.$i.'">'.$i.' </a>  ';
                  }
                }
                if ($pagina != $totalPaginas){
                  echo '<a href="'.$url.'?pagina='.($pagina+1).'"> Siguiente</a>';
                }
              }
              ?>
              </tr>
            </tbody>
        </table>
      </div>
      </div>
    </div>
    <?php
      }else{
        header("location:login.php");
      }
    ?>
  </body>
</html>
