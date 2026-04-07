<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletín - <?php echo $data['estudiante']->getNombreCompleto(); ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        
        .info-box { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-box td { padding: 5px; border: 1px solid #ddd; }
        .label { font-weight: bold; background-color: #f5f5f5; width: 15%; }
        
        .grades-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .grades-table th { background-color: #333; color: white; padding: 8px; text-align: center; border: 1px solid #333; }
        .grades-table td { padding: 8px; border: 1px solid #ccc; text-align: center; }
        .grades-table tr:nth-child(even) { background-color: #f9f9f9; }
        .text-left { text-align: left !important; }
        
        .summary { margin-top: 30px; border: 1px solid #333; padding: 10px; break-inside: avoid; }
        .footer { margin-top: 50px; text-align: center; display: flex; justify-content: space-around; }
        .signature { border-top: 1px solid #333; padding-top: 5px; width: 30%; }
        
        @media print {
            @page { margin: 1cm; size: letter; }
            body { padding: 0; }
            .no-print { display: none; }
        }
        
        .badge { padding: 3px 6px; border-radius: 4px; color: white; font-weight: bold; font-size: 10px; }
        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; color: black; }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #106018; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">
            🖨️ Imprimir Boletín
        </button>
    </div>

    <div class="header">
        <h1>Escuela Pablo Neruda</h1>
        <h2>Sistema de Gestión Académica</h2>
        <p>Informe de Calificaciones - Periodo <?php echo $data['periodo']; ?> - Año <?php echo date('Y'); ?></p>
    </div>

    <table class="info-box">
        <tr>
            <td class="label">Estudiante:</td>
            <td><?php echo $data['estudiante']->getNombreCompleto(); ?></td>
            <td class="label">Documento:</td>
            <td><?php echo $data['estudiante']->getTipoDocumento() . ' ' . $data['estudiante']->getNumeroDocumento(); ?></td>
        </tr>
        <tr>
            <td class="label">Fecha Reporte:</td>
            <td><?php echo $data['fecha_generacion']; ?></td>
            <td class="label">Puesto:</td>
            <td>
                <?php
                $puesto = $data['estadisticas']['puesto'];
                if ($puesto === 'N/A') {
                    echo '<span style="color:#999;">N/A</span>';
                } elseif (str_starts_with($puesto, '1º')) {
                    echo '🥇 <strong style="color:#106018;">' . htmlspecialchars($puesto) . '</strong>';
                } elseif (str_starts_with($puesto, '2º')) {
                    echo '🥈 <strong style="color:#555;">' . htmlspecialchars($puesto) . '</strong>';
                } elseif (str_starts_with($puesto, '3º')) {
                    echo '🥉 <strong style="color:#8B6914;">' . htmlspecialchars($puesto) . '</strong>';
                } else {
                    echo '<strong>' . htmlspecialchars($puesto) . '</strong>';
                }
                ?>
            </td>
        </tr>
    </table>

    <table class="grades-table">
        <thead>
            <tr>
                <th width="30%">Asignatura</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Nota 3</th>
                <th>Nota 4</th>
                <th>Nota 5</th>
                <th width="10%">Definitiva</th>
                <th width="15%">Desempeño</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['notas'])): ?>
                <tr>
                    <td colspan="8" style="padding: 20px;">No hay notas registradas para este periodo.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data['notas'] as $nota): ?>
                <tr>
                    <td class="text-left"><strong><?php echo $nota['materia_nombre']; ?></strong></td>
                    <td><?php echo $nota['nota1'] > 0 ? $nota['nota1'] : '-'; ?></td>
                    <td><?php echo $nota['nota2'] > 0 ? $nota['nota2'] : '-'; ?></td>
                    <td><?php echo $nota['nota3'] > 0 ? $nota['nota3'] : '-'; ?></td>
                    <td><?php echo $nota['nota4'] > 0 ? $nota['nota4'] : '-'; ?></td>
                    <td><?php echo $nota['nota5'] > 0 ? $nota['nota5'] : '-'; ?></td>
                    <td>
                        <strong><?php echo number_format((float)$nota['promedio'], 1); ?></strong>
                    </td>
                    <td>
                        <?php 
                        $prom = (float)$nota['promedio'];
                        if ($prom >= 4.6) echo '<span class="badge bg-success">Superior</span>';
                        elseif ($prom >= 4.0) echo '<span class="badge bg-success">Alto</span>';
                        elseif ($prom >= 3.0) echo '<span class="badge bg-warning">Básico</span>';
                        else echo '<span class="badge bg-danger">Bajo</span>';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary">
        <h3 style="margin-top: 0; font-size: 14px;">Resumen del Periodo</h3>
        <p>
            <strong>Promedio General:</strong> <?php echo $data['estadisticas']['promedio_general']; ?><br>
            <strong>Materias Reprobadas:</strong> <?php echo $data['estadisticas']['materias_reprobadas']; ?> de <?php echo $data['estadisticas']['total_materias']; ?>
        </p>
        <p style="font-style: italic; font-size: 11px;">
            Observaciones: El estudiante ha <?php echo $data['estadisticas']['materias_reprobadas'] > 0 ? 'reprobado asignaturas, requiere plan de mejoramiento.' : 'aprobado satisfactoriamente todas las asignaturas.'; ?>
        </p>
    </div>

    <div class="footer">
        <div class="signature" style="text-align:center;color:#333;">
            Rector(a)
        </div>
        <div class="signature" style="text-align:center;color:#333;">
            Director(a) de Grupo
        </div>
    </div>

</body>
</html>
