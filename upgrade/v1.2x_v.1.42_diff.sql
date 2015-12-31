alter table aModule
 drop column `sql_export_fields`,
 drop column `sql_export_order`,
 drop column `sql_print_fields`,
 drop column `sql_print_order`;

alter table aModule
 add column `sql_export1_fields` text COLLATE utf8_unicode_ci after sql_edit_fields,
 add column `sql_print1_fields` text COLLATE utf8_unicode_ci after sql_export1_fields,
 add column `sql_exportn_fields` text COLLATE utf8_unicode_ci after sql_print1_fields,
 add column `sql_exportn_order` text COLLATE utf8_unicode_ci after sql_exportn_fields,
 add column `sql_printn_fields` text COLLATE utf8_unicode_ci after sql_exportn_order,
 add column `sql_printn_order` text COLLATE utf8_unicode_ci after sql_printn_fields;

delete from aField where aField in ('sql_export_fields','sql_export_order','sql_print_fields','sql_print_order') limit 4;

INSERT INTO `aField` VALUES (58,4,'sql_export1_fields','Export 1 - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (59,4,'sql_print1_fields','Druck 1 - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (60,4,'sql_exportn_fields','Export n - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (61,4,'sql_exportn_order','Export n - Sortierung',1,NULL,NULL);
INSERT INTO `aField` VALUES (62,4,'sql_printn_fields','Druck n - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (63,4,'sql_printn_order','Druck n - Sortierung',1,NULL,NULL);
