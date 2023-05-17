Cambios SQL RADIAN
==================
ALTER TABLE `operacion_factura` ADD `cufe` VARCHAR(100) NULL AFTER `prefijo`;
ALTER TABLE `operacion_factura` ADD `id_estado_transmision` INT NULL AFTER `cufe`;
ALTER TABLE `operacion_factura` ADD `id_estado_inscripcion` INT NULL AFTER `id_estado_transmision`, ADD `msj_inscripcion` TEXT NULL AFTER `id_estado_inscripcion`, ADD `id_estado_mandato` INT NULL AFTER `msj_inscripcion`, ADD `msj_mandato` TEXT NULL AFTER `id_estado_mandato`, ADD `id_estado_endoso` INT NULL AFTER `msj_mandato`, ADD `msj_endoso` TEXT NULL AFTER `id_estado_endoso`, ADD `id_estado_informe` INT NULL AFTER `msj_endoso`, ADD `msj_informe` TEXT NULL AFTER `id_estado_informe`, ADD `id_estado_pago` INT NULL AFTER `msj_informe`, ADD `msj_pago` TEXT NULL AFTER `id_estado_pago`;
ALTER TABLE `operacion_factura` ADD `emisor_xml` VARCHAR(200) NULL AFTER `cufe`, ADD `identificacion_emisor` VARCHAR(50) NULL AFTER `emisor_xml`, ADD `pagador_xml` VARCHAR(200) NULL AFTER `identificacion_emisor`, ADD `identificacion_pagador` VARCHAR(50) NULL AFTER `pagador_xml`;
ALTER TABLE `operacion_factura` ADD `porcentaje_descuento` FLOAT NULL AFTER `valor_bruto`;