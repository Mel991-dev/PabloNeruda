<?php
$baseDir = __DIR__;
$filesToMerge = [
    $baseDir . '/database/bd_escuela_pablo_neruda.sql',
    $baseDir . '/database/01_expansion_datos_v2.sql',
    $baseDir . '/database/02_modulo_orientacion.sql',
    $baseDir . '/script_auditoria.sql',
    $baseDir . '/horarios.sql',
    $baseDir . '/script_notificaciones.sql'
];

$outputFile = $baseDir . '/database/bd_escuela_pablo_neruda_V3_FERIA.sql';
$finalContent = "-- SCRIPT CONSOLIDADO V3 (FERIA TECNOLOGICA)\n-- Generado automáticamente\n\n";

foreach ($filesToMerge as $file) {
    if (file_exists($file)) {
        $finalContent .= "-- =========================================\n";
        $finalContent .= "-- ARCHIVO: " . basename($file) . "\n";
        $finalContent .= "-- =========================================\n\n";
        $finalContent .= file_get_contents($file) . "\n\n";
    } else {
        echo "Aviso: Archivo no encontrado - $file\n";
    }
}

file_put_contents($outputFile, $finalContent);
echo "Script consolidado creado con éxito en: $outputFile\n";
