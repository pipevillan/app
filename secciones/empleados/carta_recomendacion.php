<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {   
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    $sentencia=$conexion->prepare("SELECT *, (SELECT nombredelpuesto 
    FROM tbl_puestos 
    WHERE tbl_puestos.id=tbl_empleados.idpuesto limit 1)  as puesto FROM tbl_empleados WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);



    $primernombre=$registro["primernombre"];
    $segundonombre=$registro["segundonombre"];
    $primerapellido=$registro["primerapellido"];
    $segundoapellido=$registro["segundoapellido"];

$nombreCompleto=$primernombre." ".$segundonombre." ".$primerapellido." ".$segundoapellido;

    $foto=$registro["foto"];
    $cv=$registro["cv"];
    $idpuesto=$registro["idpuesto"];
    $puesto=$registro["puesto"];

    $fechadeingreso=$registro["fechadeingreso"];

    $fechaInicio= new DateTime($fechadeingreso);
    $fechaFin= new DateTime(date('Y-m-d'));
    $diferencia=date_diff($fechaInicio,$fechaFin);


    
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de recomendacion</title>
</head>
<body>
    <h1>Carta de Recomendacion Laboral</h1>
<br><br>
Santiago de Cali, Colombia a <strong><?php echo date ('d M Y');?></strong>
<br><br>
A quien pueda interesar:
<br><br>
Cordual y respetuoso saludo.
<br><br>
A traves de estas lineas deseo hacer de su conocimiento que Sr(a) <strong><?php echo $nombreCompleto; ?></strong>,
a quien laboro en mi organizacion durante <strong><?php echo $diferencia->y;?> años</strong>
es un ciudadano con una conducta intachable. Ha demostrado ser un gran trabajador,
comprometido,  responsable y fiel cumplidor de sus tareas.
Siempre ha manifestado preocupacion por mejorar, capacitarse y actualizar sus conocimientos.
<br><br>
Durante estos años se ha desempeñado como: <strong><?php echo $puesto; ?></strong>
Es por ello le sugiero considere esta recomendacion, con la confianza de que estara siempre a la altura de sus compromisos y responsabilidades.
<br><br>
Sin mas nada a que referirme y, esperando que esta misiva sea tomada en cuenta, dejo mi numero de contacto para cualquier informacion de interes.
<br><br><br><br><br><br><br><br>

_______________________<br>
Atentamente
<br>
Ing. Felipe Villan


</body>
</html>
<?php
$HTML=ob_get_clean();
require_once("../../libs/autoload.inc.php");
use Dompdf\Dompdf;
$dompdf= new Dompdf();
$opciones=$dompdf->getOptions();
$opciones->set(array("isRemoteEnabled"=>true));
$dompdf->setOptions($opciones);

$dompdf->loadHTML($HTML);

$dompdf->setPAper('letter');
$dompdf->render();
$dompdf->stream("archivo.pdf", array("Attachment"=>false));

?>