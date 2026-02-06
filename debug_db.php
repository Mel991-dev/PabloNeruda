<?php
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config/config.php';
use App\Core\Database;

ob_start();
$db = Database::getInstance()->getConnection();

echo "\n--- USUARIO 'profesor' ---\n";
$stmt = $db->prepare("SELECT id_usuario, username, rol, fk_profesor FROM usuarios WHERE username = 'profesor'");
$stmt->execute();
$profUser = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($profUser);

if ($profUser && $profUser['fk_profesor']) {
    echo "\n--- DATOS DEL PROFESOR VINCULADO ---\n";
    $stmt = $db->prepare("SELECT * FROM profesores WHERE id_profesor = ?");
    $stmt->execute([$profUser['fk_profesor']]);
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
}

echo "\n--- TODAS LAS MATRICULAS POR CURSO ---\n";
$stmt = $db->query("SELECT c.id_curso, c.grado, c.seccion, COUNT(*) as total 
                   FROM matriculas m 
                   JOIN cursos c ON m.fk_curso = c.id_curso 
                   WHERE m.estado = 'Activo'
                   GROUP BY c.id_curso");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- TODAS LAS ASIGNACIONES DE PROFESORES ---\n";
$stmt = $db->query("SELECT p.nombre, c.grado, c.seccion, ma.nombre as materia, pmc.año_lectivo 
                   FROM profesor_materia_curso pmc 
                   JOIN profesores p ON pmc.fk_profesor = p.id_profesor 
                   JOIN cursos c ON pmc.fk_curso = c.id_curso 
                   JOIN materias ma ON pmc.fk_materia = ma.id_materia");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));


echo "\n--- ASIGNACIONES DEL PROFESOR ---\n";
if ($profUser && $profUser['fk_profesor']) {
    $stmt = $db->prepare("SELECT pmc.*, ma.nombre as materia, c.grado, c.seccion 
                       FROM profesor_materia_curso pmc 
                       JOIN materias ma ON pmc.fk_materia = ma.id_materia 
                       JOIN cursos c ON pmc.fk_curso = c.id_curso 
                       WHERE pmc.fk_profesor = ?");
    $stmt->execute([$profUser['fk_profesor']]);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}

$output = ob_get_clean();
file_put_contents('debug_report.txt', $output);
echo "Report generated in debug_report.txt\n";

