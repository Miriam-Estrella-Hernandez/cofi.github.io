<?php
$upload_dir = "uploads";
if (is_dir($upload_dir) && is_writable($upload_dir)) {
    echo "La carpeta 'uploads' existe y tiene permisos de escritura.";
} else {
    echo "La carpeta 'uploads' no existe o no tiene permisos de escritura.";
}
?>
