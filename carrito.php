<?php
session_start();

$mensaje="";

if(isset($_POST['btnAccion'])){

    switch($_POST['btnAccion']){

        case 'Agregar':
            if(is_numeric( openssl_decrypt($_POST['id_producto'],COD,KEY ))){
                $id_producto=openssl_decrypt($_POST['id_producto'],COD,KEY);
                $mensaje.="OK id_producto correcto".$id_producto."<br/>";

            }else{
                $mensaje.="Ups... id_producto incorrecto".$id_producto."<br/>";   
            }

            if(is_string(openssl_decrypt($_POST['nombre'],COD,KEY))){
                $NOMBRE=openssl_decrypt($_POST['nombre'],COD,KEY);
                $mensaje.="Ok nombre".$NOMBRE."<br/>";
            }else{ $mensaje.="Ups...algo pasa con el nombre"."<br/>"; break;}

            if(is_numeric(openssl_decrypt($_POST['cantidad'],COD,KEY))){
                $CANTIDAD=openssl_decrypt($_POST['cantidad'],COD,KEY);
                $mensaje.="Ok cantidad".$CANTIDAD."<br/>";
            }else{ $mensaje.="Ups...algo pasa con la cantidad"."<br/>"; break;}

            if(is_numeric(openssl_decrypt($_POST['precio'],COD,KEY))){
                $PRECIO=openssl_decrypt($_POST['precio'],COD,KEY);
                $mensaje.="Ok precio".$PRECIO."<br/>";
            }else{ $mensaje.="Ups...algo pasa con el precio"."<br/>"; break;}

            if(!isset($_SESSION['CARRITO'])){

                $producto=array(
                    'id_producto'=>$id_producto,
                    'NOMBRE'=>$NOMBRE,
                    'CANTIDAD'=>$CANTIDAD,              
                    'PRECIO'=>$PRECIO            
                );
                $_SESSION['CARRITO'][0]=$producto;
                $mensaje= "Producto agregado al carrito";

            }else{

                $idProductos=array_column($_SESSION['CARRITO'],"id_producto");

                if(in_array($id_producto,$idProductos)){
                    echo"<script>alert('El producto ya ha sido seleccionado...');</script>";
                    $mensaje="";
                }else{

                $NumeroProductos=count($_SESSION['CARRITO']);
                $producto=array(
                    'id_producto'=>$id_producto,
                    'NOMBRE'=>$NOMBRE,
                    'CANTIDAD'=>$CANTIDAD,              
                    'PRECIO'=>$PRECIO            
                );

                $_SESSION['CARRITO'][$NumeroProductos]=$producto;
                $mensaje= "Producto agregado al carrito";
        }
    }
        //$mensaje= print_r($_SESSION,true);
      

    break;
    case "Eliminar":
        if(is_numeric( openssl_decrypt($_POST['id_producto'],COD,KEY ))){
            $id_producto=openssl_decrypt($_POST['id_producto'],COD,KEY );
            
            foreach($_SESSION['CARRITO'] as $indice=>$producto){
                if($producto['id_producto']==$id_producto){
                    unset($_SESSION['CARRITO'][$indice]);
                    echo "<script>alert('Elemento borrado...');</script>";

                }

            }

        }else{
            $mensaje.="Ups... id_producto incorrecto".$id_producto."<br/>";   
        }
    break;

    }
}
?>