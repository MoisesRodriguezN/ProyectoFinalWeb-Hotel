<?php

include_once '../../Model/datosHotel.php';
$idImagen = $_POST["id"];

if (isset($_FILES[$idImagen]) ||$_FILES[$idImagen] != null){
    $file = $_FILES[$idImagen];
    $nombre = $file["name"];
    
    switch ($idImagen) {
        case "logoHotel":
            $nombre = "logoHotelHeader.jpg";
            break;
        case "img1Galeria":
            $nombre = "img1Galeria.jpg";
            break;
        case "img2Galeria":
            $nombre = "img2Galeria.jpg";
            break;
        case "img3Galeria":
            $nombre = "img3Galeria.jpg";
            break;
        case "img4Galeria":
            $nombre = "img4Galeria.jpg";
            break;
        case "img5Galeria":
            $nombre = "img5Galeria.jpg";
            break;
        default:
            break;
    }
//    if($idImagen == "logoHotel"){
//        $nombre = "logoHotelHeader.jpg";
//        
//    }
//    
//    if($idImagen == "img1Galeria"){
//        $nombre = "img1Galeria.jpg";
//    }
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $dimensiones = getimagesize($ruta_provisional);
    $width = $dimensiones[0];
    $height = $dimensiones[1];
    $carpeta = "uploads/";
    
    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
    {
      echo "Error, el archivo no es una imagen"; 
    }
    else if ($size > 10024*10024)
    {
      echo "Error, el tamaño máximo permitido es un 10MB";
    }
    else if ($width > 500 || $height > 500)
    {
        echo "Error la anchura y la altura maxima permitida es 500px";
    }
    else if($width < 60 || $height < 60)
    {
        echo "Error la anchura y la altura mínima permitida es 60px";
    }
    else
    {
        $src = $carpeta.$nombre;
        unlink($src);
        move_uploaded_file($ruta_provisional, $src);
        datosHotel::setImagenHotel($idImagen, $src);
        echo $src;
    }
}