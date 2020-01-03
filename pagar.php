<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>

<?php
if($_POST){
    $total=0;
    $SID=session_id();
    $correo=$_POST['email'];


    foreach($_SESSION['CARRITO'] as $indice=>$producto){

        $total=$total+($producto['PRECIO']*$producto['CANTIDAD']);

    }
    $sentencia=$pdo->prepare("INSERT INTO `pedido` 
    (`id_pedido`, `claveTransaccion`, `paypalDatos`, `fecha`, `correo`, `total`, `status`) 
    VALUES (NULL,:claveTransaccion, '', NOW(),:correo,:total, 'Pendiente');");
    
    $sentencia->bindParam(":claveTransaccion",$SID);
    $sentencia->bindParam(":correo",$correo);
    $sentencia->bindParam(":total",$total);
    $sentencia->execute();
    $id_pedido=$pdo->lastInsertId();

    foreach($_SESSION['CARRITO'] as $indice=>$producto){

        $sentencia=$pdo->prepare("INSERT INTO `detalle_pedido` 
        (`id_detalle_pedido`, `id_pedido`, `id_producto`, `valor_unidad`, `cantidad`, `descargado`) 
        VALUES (NULL,:id_pedido,:id_producto,:valor_unidad,:cantidad, '0');");

    $sentencia->bindParam(":id_pedido",$id_pedido);
    $sentencia->bindParam(":id_producto",$producto['id_producto']);
    $sentencia->bindParam(":valor_unidad",$producto['PRECIO']);
    $sentencia->bindParam(":cantidad",$producto['CANTIDAD']);
    $sentencia->execute();

    }

    //echo"<h3>".$total."</h3>";
}
?>

<div class="jumbotron text-center">
    <h1 class="display-4">¡ Paso final !</h1>
    <hr class="my-4">
    <p class="lead">Estás a punto de pagar con Paypal, la cantidad de:
      <h4>$<?php echo number_format($total,2); ?></h4>
    </p>
        <p>Los productos podrán ser descargados, una vez que se procese el pago. <br/>
        <strong>(Para aclaraciones :catalinarestrepogomez@yahoo.es)</strong>
        </p>
</div>

<?php include 'templates/pie.php'; ?>