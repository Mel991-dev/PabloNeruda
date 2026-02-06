<?php

namespace App\Domain\Services;

use App\Domain\Entities\Estudiante;
use App\Domain\Entities\Acudiente;
use App\Domain\Entities\InfoSalud;
use App\Domain\Entities\InfoSocioeconomica;
use App\Domain\Entities\AntecedenteAcademico;
use App\Domain\Repositories\EstudianteRepositoryInterface;
use App\Domain\Repositories\AcudienteRepositoryInterface;
use App\Domain\Repositories\CursoRepositoryInterface;
use App\Core\Database;

class EstudianteService
{
    private EstudianteRepositoryInterface $estudianteRepo;
    private AcudienteRepositoryInterface $acudienteRepo;
    private CursoRepositoryInterface $cursoRepo;
    private \App\Domain\Repositories\FamiliarRepositoryInterface $familiarRepo;

    public function __construct(
        EstudianteRepositoryInterface $estudianteRepo,
        AcudienteRepositoryInterface $acudienteRepo,
        CursoRepositoryInterface $cursoRepo,
        \App\Domain\Repositories\FamiliarRepositoryInterface $familiarRepo
    ) {
        $this->estudianteRepo = $estudianteRepo;
        $this->acudienteRepo = $acudienteRepo;
        $this->cursoRepo = $cursoRepo;
        $this->familiarRepo = $familiarRepo;
    }

    /**
     * Obtener todos los estudiantes
     */
    public function listarEstudiantes(): array
    {
        return $this->estudianteRepo->findAll();
    }

    /**
     * Obtener lista de cursos
     */
    public function listarCursos(): array
    {
        return $this->cursoRepo->findAll();
    }

    /**
     * Registrar un nuevo estudiante
     */
    public function registrarEstudiante(array $datosEstudiante, array $datosAcudiente, array $archivoPdf): int
    {
        $db = Database::getInstance()->getConnection();
        
        try {
            $db->beginTransaction();

            // 1. Validar Capacidad del Curso
            $cursoId = (int) $datosEstudiante['curso_id'];
            $curso = $this->cursoRepo->findById($cursoId);
            $totalInscritos = $this->estudianteRepo->countByCurso($cursoId);

            if ($totalInscritos >= $curso['capacidad_maxima']) {
                throw new \Exception("El curso seleccionado no tiene cupos disponibles.");
            }

            // 2. Manejar Subida de Archivo PDF
            $documentoPath = null;
            if ($archivoPdf && $archivoPdf['error'] === UPLOAD_ERR_OK) {
                $documentoPath = $this->subirDocumento($archivoPdf);
                $datosEstudiante['documento_pdf'] = $documentoPath;
            }

            // 3. Crear o Actualizar Estudiante
            $estudiante = Estudiante::fromArray($datosEstudiante);
            
            // Asignar Datos Extendidos V2
            if (isset($datosEstudiante['info_salud'])) {
                $estudiante->setInfoSalud(InfoSalud::fromArray($datosEstudiante['info_salud']));
            }
            if (isset($datosEstudiante['info_socio'])) {
                $estudiante->setInfoSocioeconomica(InfoSocioeconomica::fromArray($datosEstudiante['info_socio']));
            }
            if (isset($datosEstudiante['antecedentes']) && is_array($datosEstudiante['antecedentes'])) {
                $antecedentes = [];
                foreach ($datosEstudiante['antecedentes'] as $antData) {
                    $antecedentes[] = AntecedenteAcademico::fromArray($antData);
                }
                $estudiante->setAntecedentesAcademicos($antecedentes);
            }

            // Validar si ya existe
            $existente = $this->estudianteRepo->findByDocumento($estudiante->getNumeroDocumento());
            if ($existente) {
                throw new \Exception("Ya existe un estudiante con el documento {$estudiante->getNumeroDocumento()}");
            }

            $erroresEstudiante = $estudiante->validar();
            if (!empty($erroresEstudiante)) {
                throw new \Exception(implode(", ", $erroresEstudiante));
            }

            $idEstudiante = $this->estudianteRepo->save($estudiante);
            $estudiante->setIdEstudiante($idEstudiante);

            // 3.5 Gestionar Padres (Familiar) V2
            if (isset($datosEstudiante['padre'])) {
                $idPadre = $this->gestionarFamiliar($datosEstudiante['padre']);
                $estudiante->setFkPadre($idPadre);
            }
            if (isset($datosEstudiante['madre'])) {
                $idMadre = $this->gestionarFamiliar($datosEstudiante['madre']);
                $estudiante->setFkMadre($idMadre);
            }
            // Actualizar el estudiante con los IDs de los padres recién creados/buscados
            if ($estudiante->getFkPadre() || $estudiante->getFkMadre()) {
                $this->estudianteRepo->update($estudiante);
            }

            // 4. Crear o Buscar Acudiente
            $acudiente = Acudiente::fromArray($datosAcudiente);
            
            // Buscar si ya existe el acudiente
            $acudienteExistente = $this->acudienteRepo->findByDocumento($acudiente->getNumeroDocumento());
            
            $idAcudiente = null;
            if ($acudienteExistente) {
                $idAcudiente = $acudienteExistente->getIdAcudiente();
                // Opcional: Actualizar datos del acudiente si es necesario
            } else {
                $erroresAcudiente = $acudiente->validar();
                if (!empty($erroresAcudiente)) {
                    throw new \Exception(implode(", ", $erroresAcudiente));
                }
                $idAcudiente = $this->acudienteRepo->save($acudiente);
            }

            // 5. Vincular Estudiante - Acudiente
            $this->estudianteRepo->addAcudiente(
                $idEstudiante,
                $idAcudiente,
                $datosAcudiente['parentesco'],
                true, // Es acudiente principal
                isset($datosAcudiente['con_quien_vive']) && $datosAcudiente['con_quien_vive'] == '1',
                true  // Autorizado recoger por defecto
            );

            // 6. Matricular en el curso
            // Insertamos manualmente en la tabla pivote de matrículas
            $stmt = $db->prepare("INSERT INTO matriculas (fk_estudiante, fk_curso, año_lectivo, fecha_matricula, estado) VALUES (:est_id, :curso_id, :anio, CURDATE(), 'Activo')");
            $stmt->execute([
                'est_id' => $idEstudiante,
                'curso_id' => $cursoId,
                'anio' => date('Y') // Asumimos año actual
            ]);

            $db->commit();
            return $idEstudiante;

        } catch (\Exception $e) {
            $db->rollBack();
            // Si se subió archivo, eliminarlo
            if (isset($documentoPath)) {
                @unlink(__DIR__ . '/../../../public/' . $documentoPath);
            }
            throw $e;
        }
    }

    /**
     * Subir documento PDF
     */
    private function subirDocumento(array $archivo): string
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/documentos/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        if (strtolower($extension) !== 'pdf') {
            throw new \Exception("Solo se permiten archivos PDF.");
        }

        $filename = uniqid('doc_') . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($archivo['tmp_name'], $destination)) {
            throw new \Exception("Error al subir el archivo.");
        }

        return 'uploads/documentos/' . $filename;
    }
    
    /**
     * Actualizar estudiante y acudiente
     */
    public function actualizarEstudiante(int $id, array $datosEstudiante, array $datosAcudiente, ?array $archivoPdf): void
    {
        $db = Database::getInstance()->getConnection();
        
        try {
            $db->beginTransaction();

            $estudiante = $this->estudianteRepo->findById($id);
            if (!$estudiante) {
                throw new \Exception("Estudiante no encontrado.");
            }

            // 1. Actualizar PDF si se subió uno nuevo
            if ($archivoPdf && $archivoPdf['error'] === UPLOAD_ERR_OK) {
                // Eliminar anterior
                if ($estudiante->getDocumentoPdf()) {
                    @unlink(__DIR__ . '/../../../public/' . $estudiante->getDocumentoPdf());
                }
                $documentoPath = $this->subirDocumento($archivoPdf);
                $datosEstudiante['documento_pdf'] = $documentoPath;
            } else {
                $datosEstudiante['documento_pdf'] = $estudiante->getDocumentoPdf();
            }

            // 2. Actualizar datos básicos
            $estudiante->setNombre($datosEstudiante['nombre'])
                       ->setApellido($datosEstudiante['apellido'])
                       ->setFechaNacimiento($datosEstudiante['fecha_nacimiento'])
                       ->setTipoDocumento($datosEstudiante['tipo_documento'])
                       ->setNumeroDocumento($datosEstudiante['numero_documento'])
                       ->setRegistroCivil($datosEstudiante['registro_civil'] ?? null)
                       ->setTarjetaIdentidad($datosEstudiante['tarjeta_identidad'] ?? null)
                       ->setDocumentoPdf($datosEstudiante['documento_pdf'])
                       ->setTieneAlergias($datosEstudiante['tiene_alergias'])
                       ->setDescripcionAlergias($datosEstudiante['descripcion_alergias'])
                       ->setNumeroHermanos($datosEstudiante['numero_hermanos'])
                       ->setLugarNacimiento($datosEstudiante['lugar_nacimiento'] ?? null)
                       ->setProcedenciaInstitucion($datosEstudiante['procedencia'] ?? null);

            // Actualizar Datos Extendidos V2
            if (isset($datosEstudiante['info_salud'])) {
                $estudiante->setInfoSalud(InfoSalud::fromArray($datosEstudiante['info_salud']));
            }
            if (isset($datosEstudiante['info_socio'])) {
                $estudiante->setInfoSocioeconomica(InfoSocioeconomica::fromArray($datosEstudiante['info_socio']));
            }
            if (isset($datosEstudiante['antecedentes']) && is_array($datosEstudiante['antecedentes'])) {
                $antecedentes = [];
                foreach ($datosEstudiante['antecedentes'] as $antData) {
                    $antecedentes[] = AntecedenteAcademico::fromArray($antData);
                }
                $estudiante->setAntecedentesAcademicos($antecedentes);
            }

            // Gestionar Padres (Familiar) V2
            if (isset($datosEstudiante['padre'])) {
                $idPadre = $this->gestionarFamiliar($datosEstudiante['padre']);
                $estudiante->setFkPadre($idPadre);
            }
            if (isset($datosEstudiante['madre'])) {
                $idMadre = $this->gestionarFamiliar($datosEstudiante['madre']);
                $estudiante->setFkMadre($idMadre);
            }

            $this->estudianteRepo->update($estudiante);

            // 3. Gestionar Acudiente (Simplificado: Siempre es uno principal por ahora en el form)
            $acudiente = Acudiente::fromArray($datosAcudiente);
            
            // Buscar si ya existe (por documento) o si se está actualizando el mismo
            $acudienteExistente = $this->acudienteRepo->findByDocumento($acudiente->getNumeroDocumento());
            
            $idAcudiente = null;
            if ($acudienteExistente) {
                $idAcudiente = $acudienteExistente->getIdAcudiente();
                // Actualizar datos del acudiente existente
                $acudiente->setIdAcudiente($idAcudiente);
                $this->acudienteRepo->update($acudiente);
            } else {
                // Crear nuevo acudiente
                $idAcudiente = $this->acudienteRepo->save($acudiente);
            }

            // 4. Actualizar Vinculación
            // Eliminamos vinculaciones previas y creamos la nueva para corregir parentescos
            $this->estudianteRepo->removeAcudientes($id);
            
            $this->estudianteRepo->addAcudiente(
                $id,
                $idAcudiente,
                $datosAcudiente['parentesco'],
                true, // Principal
                isset($datosAcudiente['con_quien_vive']) && $datosAcudiente['con_quien_vive'] == '1',
                true // Autorizado
            );

            // 5. Actualizar Curso (Matrícula) - Opcional, si cambió de curso
            // Por simplicidad, asumimos que el curso se gestiona en otro módulo o se mantiene
            // Si se requiere cambiar curso, se debería actualizar la tabla matriculas para el año actual

            $db->commit();

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Obtener estudiante por ID con acudientes
     */
    public function obtenerEstudiante(int $id): array
    {
        $estudiante = $this->estudianteRepo->findById($id);
        if (!$estudiante) {
            return [];
        }
        
        $acudientes = $this->estudianteRepo->getAcudientes($id);
        
        return [
            'estudiante' => $estudiante,
            'acudientes' => $acudientes
        ];
    }

    /**
     * Auxiliar para gestionar familiares (Padre/Madre)
     */
    private function gestionarFamiliar(array $datos): int
    {
        if (empty($datos['nombre']) || empty($datos['apellido'])) return 0;

        $familiar = \App\Domain\Entities\Familiar::fromArray($datos);
        
        // Buscar por documento si tiene
        if ($familiar->getNumeroDocumento()) {
            $existente = $this->familiarRepo->findByDocumento($familiar->getNumeroDocumento());
            if ($existente) {
                $familiar->setId($existente->getId());
                $this->familiarRepo->update($familiar);
                return (int)$existente->getId();
            }
        }

        return $this->familiarRepo->save($familiar);
    }
}
