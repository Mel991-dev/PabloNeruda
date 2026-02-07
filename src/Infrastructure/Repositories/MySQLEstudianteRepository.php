<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Estudiante;
use App\Domain\Entities\InfoSalud;
use App\Domain\Entities\InfoSocioeconomica;
use App\Domain\Entities\AntecedenteAcademico;
use App\Domain\Entities\Familiar;
use App\Domain\Repositories\EstudianteRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLEstudianteRepository implements EstudianteRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM estudiantes WHERE estado != 'Retirado' ORDER BY apellido, nombre");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = Estudiante::fromArray($row);
        }
        return $result;
    }

    public function findById(int $id): ?Estudiante
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiantes WHERE id_estudiante = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) return null;

        $estudiante = Estudiante::fromArray($row);
        
        // Cargar datos V2 (Lazy Loading manual por ahora)
        $this->loadV2Data($estudiante);
        
        return $estudiante;
    }

    private function loadV2Data(Estudiante $estudiante): void
    {
        $id = $estudiante->getIdEstudiante();

        // 1. Info Salud
        $stmt = $this->db->prepare("SELECT * FROM estudiantes_info_salud WHERE fk_estudiante = :id");
        $stmt->execute(['id' => $id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estudiante->setInfoSalud(InfoSalud::fromArray($row));
        }

        // 2. Info Socioeconómica
        $stmt = $this->db->prepare("SELECT * FROM estudiantes_socioeconomico WHERE fk_estudiante = :id");
        $stmt->execute(['id' => $id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estudiante->setInfoSocioeconomica(InfoSocioeconomica::fromArray($row));
        }

        // 3. Antecedentes
        $stmt = $this->db->prepare("SELECT * FROM antecedentes_academicos WHERE fk_estudiante = :id");
        $stmt->execute(['id' => $id]);
        $antecedentes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $antecedentes[] = AntecedenteAcademico::fromArray($row);
        }
        $estudiante->setAntecedentesAcademicos($antecedentes);

        // 4. Padres (Familiar)
        if ($estudiante->getFkPadre()) {
            $stmt = $this->db->prepare("SELECT * FROM familiares WHERE id_familiar = ?");
            $stmt->execute([$estudiante->getFkPadre()]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $estudiante->setPadre(Familiar::fromArray($row));
            }
        }
        if ($estudiante->getFkMadre()) {
            $stmt = $this->db->prepare("SELECT * FROM familiares WHERE id_familiar = ?");
            $stmt->execute([$estudiante->getFkMadre()]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $estudiante->setMadre(Familiar::fromArray($row));
            }
        }

        // 5. Nombre del Curso Actual
        $stmt = $this->db->prepare("
            SELECT CONCAT(c.grado, ' - ', c.seccion) as nombre_curso
            FROM matriculas m
            INNER JOIN cursos c ON m.fk_curso = c.id_curso
            WHERE m.fk_estudiante = :id AND m.estado = 'Activo'
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estudiante->setNombreCurso($row['nombre_curso']);
        }
    }

    public function findByCurso(int $cursoId): array
    {
        $sql = "SELECT e.* FROM estudiantes e 
                INNER JOIN matriculas m ON e.id_estudiante = m.fk_estudiante 
                WHERE m.fk_curso = :curso_id AND m.estado = 'Activo' AND e.estado = 'Activo'
                ORDER BY e.apellido, e.nombre";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['curso_id' => $cursoId]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = Estudiante::fromArray($row);
        }
        return $result;
    }

    public function findByDocumento(string $numeroDocumento): ?Estudiante
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiantes WHERE numero_documento = :doc");
        $stmt->execute(['doc' => $numeroDocumento]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? Estudiante::fromArray($row) : null;
    }

    public function save(Estudiante $estudiante): int
    {
        $sql = "INSERT INTO estudiantes (
                    nombre, apellido, fecha_nacimiento, tipo_documento, numero_documento,
                    registro_civil, tarjeta_identidad, documento_pdf, tiene_alergias,
                    descripcion_alergias, numero_hermanos, estado, fecha_registro,
                    lugar_nacimiento, procedencia_institucion, fk_padre, fk_madre
                ) VALUES (
                    :nombre, :apellido, :fecha_nacimiento, :tipo_documento, :numero_documento,
                    :registro_civil, :tarjeta_identidad, :documento_pdf, :tiene_alergias,
                    :descripcion_alergias, :numero_hermanos, :estado, NOW(),
                    :lugar_nacimiento, :procedencia, :fk_padre, :fk_madre
                )";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $estudiante->getNombre(),
            'apellido' => $estudiante->getApellido(),
            'fecha_nacimiento' => $estudiante->getFechaNacimiento(),
            'tipo_documento' => $estudiante->getTipoDocumento(),
            'numero_documento' => $estudiante->getNumeroDocumento(),
            'registro_civil' => $estudiante->getRegistroCivil(),
            'tarjeta_identidad' => $estudiante->getTarjetaIdentidad(),
            'documento_pdf' => $estudiante->getDocumentoPdf(),
            'tiene_alergias' => $estudiante->tieneAlergias() ? 1 : 0,
            'descripcion_alergias' => $estudiante->getDescripcionAlergias(),
            'numero_hermanos' => $estudiante->getNumeroHermanos(),
            'estado' => $estudiante->getEstado(),
            'lugar_nacimiento' => $estudiante->getLugarNacimiento(),
            'procedencia' => $estudiante->getProcedenciaInstitucion(),
            'fk_padre' => $estudiante->getFkPadre(),
            'fk_madre' => $estudiante->getFkMadre()
        ]);
        
        $estudianteId = (int) $this->db->lastInsertId();
        
        // Guardar INFO SATÉLITE si existe
        if ($estudiante->getInfoSalud()) {
            $this->saveInfoSalud($estudianteId, $estudiante->getInfoSalud());
        }
        
        if ($estudiante->getInfoSocioeconomica()) {
            $this->saveInfoSocioeconomica($estudianteId, $estudiante->getInfoSocioeconomica());
        }
        
        foreach ($estudiante->getAntecedentesAcademicos() as $antecedente) {
            $this->saveAntecedente($estudianteId, $antecedente);
        }

        return $estudianteId;
    }

    private function saveInfoSalud(int $estudianteId, InfoSalud $info): void
    {
        $sql = "INSERT INTO estudiantes_info_salud (
            fk_estudiante, eps, tipo_sangre, limitaciones_fisicas, limitaciones_sensoriales,
            medicamentos_permanentes, vacunas_completas, toma_medicamentos, alergico_a, dificultad_salud
        ) VALUES (
            :id, :eps, :tipo_sangre, :lim_fisicas, :lim_sensoriales,
            :meds, :vacunas, :toma_meds, :alergias, :dificultad
        )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $estudianteId,
            'eps' => $info->getEps(),
            'tipo_sangre' => $info->getTipoSangre(),
            'lim_fisicas' => $info->getLimitacionesFisicas(),
            'lim_sensoriales' => $info->getLimitacionesSensoriales(),
            'meds' => $info->getMedicamentosPermanentes(),
            'vacunas' => $info->areVacunasCompletas() ? 1 : 0,
            'toma_meds' => $info->tomaMedicamentos() ? 1 : 0,
            'alergias' => $info->getAlergicoA(),
            'dificultad' => $info->getDificultadSalud()
        ]);
    }

    private function saveInfoSocioeconomica(int $estudianteId, InfoSocioeconomica $info): void
    {
        $sql = "INSERT INTO estudiantes_socioeconomico (
            fk_estudiante, sisben_nivel, estrato, barrio, sector, tipo_vivienda,
            tiene_internet, servicios_publicos_completo, victima_conflicto, victima_conflicto_detalle,
            grupo_etnico, resguardo_indigena, familias_en_accion, poblacion_desplazada, lugar_desplazamiento
        ) VALUES (
            :id, :sisben, :estrato, :barrio, :sector, :vivienda,
            :internet, :servicios, :victima, :detalle_victima,
            :etnia, :resguardo, :familias, :desplazado, :lugar_desp
        )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $estudianteId,
            'sisben' => $info->getSisbenNivel(),
            'estrato' => $info->getEstrato(),
            'barrio' => $info->getBarrio(),
            'sector' => $info->getSector(),
            'vivienda' => $info->getTipoVivienda(),
            'internet' => $info->tieneInternet() ? 1 : 0,
            'servicios' => $info->tieneServiciosCompletos() ? 1 : 0,
            'victima' => $info->esVictimaConflicto() ? 1 : 0,
            'detalle_victima' => $info->getDetalleVictima(),
            'etnia' => $info->getGrupoEtnico(),
            'resguardo' => $info->getResguardo(),
            'familias' => $info->esFamiliasAccion() ? 1 : 0,
            'desplazado' => $info->esDesplazado() ? 1 : 0,
            'lugar_desp' => $info->getLugarDesplazamiento()
        ]);
    }

    private function saveAntecedente(int $estudianteId, AntecedenteAcademico $ant): void
    {
        $sql = "INSERT INTO antecedentes_academicos (
            fk_estudiante, nivel_educativo, institucion, años_cursados, motivo_retiro, observaciones
        ) VALUES (
            :id, :nivel, :inst, :anios, :retiro, :obs
        )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $estudianteId,
            'nivel' => $ant->getNivelEducativo(),
            'inst' => $ant->getInstitucion(),
            'anios' => $ant->getAniosCursados(),
            'retiro' => $ant->getMotivoRetiro(),
            'obs' => $ant->getObservaciones()
        ]);
    }

    public function update(Estudiante $estudiante): bool
    {
        $sql = "UPDATE estudiantes SET 
                    nombre = :nombre,
                    apellido = :apellido,
                    fecha_nacimiento = :fecha_nacimiento,
                    tipo_documento = :tipo_documento,
                    numero_documento = :numero_documento,
                    registro_civil = :registro_civil,
                    tarjeta_identidad = :tarjeta_identidad,
                    documento_pdf = :documento_pdf,
                    tiene_alergias = :tiene_alergias,
                    descripcion_alergias = :descripcion_alergias,
                    numero_hermanos = :numero_hermanos,
                    estado = :estado,
                    lugar_nacimiento = :lugar_nacimiento,
                    procedencia_institucion = :procedencia,
                    fk_padre = :fk_padre,
                    fk_madre = :fk_madre
                WHERE id_estudiante = :id";
                
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'nombre' => $estudiante->getNombre(),
            'apellido' => $estudiante->getApellido(),
            'fecha_nacimiento' => $estudiante->getFechaNacimiento(),
            'tipo_documento' => $estudiante->getTipoDocumento(),
            'numero_documento' => $estudiante->getNumeroDocumento(),
            'registro_civil' => $estudiante->getRegistroCivil(),
            'tarjeta_identidad' => $estudiante->getTarjetaIdentidad(),
            'documento_pdf' => $estudiante->getDocumentoPdf(),
            'tiene_alergias' => $estudiante->tieneAlergias() ? 1 : 0,
            'descripcion_alergias' => $estudiante->getDescripcionAlergias(),
            'numero_hermanos' => $estudiante->getNumeroHermanos(),
            'estado' => $estudiante->getEstado(),
            'lugar_nacimiento' => $estudiante->getLugarNacimiento(),
            'procedencia' => $estudiante->getProcedenciaInstitucion(),
            'fk_padre' => $estudiante->getFkPadre(),
            'fk_madre' => $estudiante->getFkMadre(),
            'id' => $estudiante->getIdEstudiante()
        ]);

        if ($result) {
            $id = $estudiante->getIdEstudiante();
            
            // Actualizar/Crear Info Salud
            if ($estudiante->getInfoSalud()) {
                // Verificar si existe para hacer UPDATE o INSERT
                $exists = $this->db->query("SELECT 1 FROM estudiantes_info_salud WHERE fk_estudiante = $id")->fetchColumn();
                if ($exists) {
                    $this->updateInfoSalud($id, $estudiante->getInfoSalud());
                } else {
                    $this->saveInfoSalud($id, $estudiante->getInfoSalud());
                }
            }

            // Actualizar/Crear Info Socioeconómica
            if ($estudiante->getInfoSocioeconomica()) {
                $exists = $this->db->query("SELECT 1 FROM estudiantes_socioeconomico WHERE fk_estudiante = $id")->fetchColumn();
                if ($exists) {
                    $this->updateInfoSocioeconomica($id, $estudiante->getInfoSocioeconomica());
                } else {
                    $this->saveInfoSocioeconomica($id, $estudiante->getInfoSocioeconomica());
                }
            }

            // Antecedentes: Estrategia simple -> Borrar y Recrear
            $this->db->prepare("DELETE FROM antecedentes_academicos WHERE fk_estudiante = :id")->execute(['id' => $id]);
            foreach ($estudiante->getAntecedentesAcademicos() as $antecedente) {
                $this->saveAntecedente($id, $antecedente);
            }
        }

        return $result;
    }

    private function updateInfoSalud(int $estudianteId, InfoSalud $info): void
    {
        $sql = "UPDATE estudiantes_info_salud SET 
                eps = :eps, tipo_sangre = :tipo_sangre, limitaciones_fisicas = :lim_fisicas,
                limitaciones_sensoriales = :lim_sensoriales, medicamentos_permanentes = :meds,
                vacunas_completas = :vacunas, toma_medicamentos = :toma_meds,
                alergico_a = :alergias, dificultad_salud = :dificultad
                WHERE fk_estudiante = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'eps' => $info->getEps(),
            'tipo_sangre' => $info->getTipoSangre(),
            'lim_fisicas' => $info->getLimitacionesFisicas(),
            'lim_sensoriales' => $info->getLimitacionesSensoriales(),
            'meds' => $info->getMedicamentosPermanentes(),
            'vacunas' => $info->areVacunasCompletas() ? 1 : 0,
            'toma_meds' => $info->tomaMedicamentos() ? 1 : 0,
            'alergias' => $info->getAlergicoA(),
            'dificultad' => $info->getDificultadSalud(),
            'id' => $estudianteId
        ]);
    }

    private function updateInfoSocioeconomica(int $estudianteId, InfoSocioeconomica $info): void
    {
        $sql = "UPDATE estudiantes_socioeconomico SET 
                sisben_nivel = :sisben, estrato = :estrato, barrio = :barrio, sector = :sector,
                tipo_vivienda = :vivienda, tiene_internet = :internet, servicios_publicos_completo = :servicios,
                victima_conflicto = :victima, victima_conflicto_detalle = :detalle_victima,
                grupo_etnico = :etnia, resguardo_indigena = :resguardo, familias_en_accion = :familias,
                poblacion_desplazada = :desplazado, lugar_desplazamiento = :lugar_desp
                WHERE fk_estudiante = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'sisben' => $info->getSisbenNivel(),
            'estrato' => $info->getEstrato(),
            'barrio' => $info->getBarrio(),
            'sector' => $info->getSector(),
            'vivienda' => $info->getTipoVivienda(),
            'internet' => $info->tieneInternet() ? 1 : 0,
            'servicios' => $info->tieneServiciosCompletos() ? 1 : 0,
            'victima' => $info->esVictimaConflicto() ? 1 : 0,
            'detalle_victima' => $info->getDetalleVictima(),
            'etnia' => $info->getGrupoEtnico(),
            'resguardo' => $info->getResguardo(),
            'familias' => $info->esFamiliasAccion() ? 1 : 0,
            'desplazado' => $info->esDesplazado() ? 1 : 0,
            'lugar_desp' => $info->getLugarDesplazamiento(),
            'id' => $estudianteId
        ]);
    }

    public function delete(int $id): bool
    {
        // Eliminado lógico (cambiar estado a Retirado)
        $stmt = $this->db->prepare("UPDATE estudiantes SET estado = 'Retirado' WHERE id_estudiante = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countByCurso(int $cursoId): int
    {
        $sql = "SELECT COUNT(*) FROM matriculas WHERE fk_curso = :curso_id AND estado = 'Activo'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['curso_id' => $cursoId]);
        return (int) $stmt->fetchColumn();
    }

    public function getAcudientes(int $estudianteId): array
    {
        $sql = "SELECT a.*, ea.parentesco, ea.es_acudiente_principal, ea.con_quien_vive, ea.autorizado_recoger 
                FROM acudientes a
                INNER JOIN estudiante_acudiente ea ON a.id_acudiente = ea.fk_acudiente
                WHERE ea.fk_estudiante = :estudiante_id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['estudiante_id' => $estudianteId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAcudiente(int $estudianteId, int $acudienteId, string $parentesco, bool $principal, bool $viveCon, bool $autorizado): bool
    {
        $sql = "INSERT INTO estudiante_acudiente (
                    fk_estudiante, fk_acudiente, parentesco, es_acudiente_principal, con_quien_vive, autorizado_recoger
                ) VALUES (
                    :est_id, :acud_id, :parentesco, :principal, :vive_con, :autorizado
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'est_id' => $estudianteId,
            'acud_id' => $acudienteId,
            'parentesco' => $parentesco,
            'principal' => $principal ? 1 : 0,
            'vive_con' => $viveCon ? 1 : 0,
            'autorizado' => $autorizado ? 1 : 0
        ]);
    }

    public function removeAcudientes(int $estudianteId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM estudiante_acudiente WHERE fk_estudiante = :id");
        return $stmt->execute(['id' => $estudianteId]);
    }
}
