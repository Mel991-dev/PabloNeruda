CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `fk_usuario_origen` int(11) DEFAULT NULL COMMENT 'Usuario que originó la alerta, ej: Profesor',
  `fk_usuario_destino` int(11) DEFAULT NULL COMMENT 'Destinatario específico, si es null va a todos los del rol',
  `rol_destino` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Rol al que va dirigido, ej: Orientador',
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ej: Riesgo Académico, Falta Asistencia',
  `titulo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `enlace` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL para ver más detalles',
  `leida` tinyint(1) DEFAULT '0',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `fk_notif_origen` (`fk_usuario_origen`),
  KEY `fk_notif_destino` (`fk_usuario_destino`),
  CONSTRAINT `fk_notif_origen` FOREIGN KEY (`fk_usuario_origen`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_notif_destino` FOREIGN KEY (`fk_usuario_destino`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
