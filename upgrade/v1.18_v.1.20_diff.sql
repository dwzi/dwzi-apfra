alter table aModule
 add column `refid_aModuleType` int(11) unsigned DEFAULT NULL after aModuleDesc,
 add column `sql_table_perpage` int(11) unsigned DEFAULT NULL after refid_aModuleType,
 add column `sql_table_search` text COLLATE utf8_unicode_ci after sql_table_perpage,
 add column `sql_table_fields` text COLLATE utf8_unicode_ci after sql_table_search,
 add column `sql_table_order` text COLLATE utf8_unicode_ci after sql_table_fields,
 add column `sql_edit_legend` text COLLATE utf8_unicode_ci after sql_table_order,
 add column `sql_edit_fields` text COLLATE utf8_unicode_ci after sql_edit_legend,
 add column `sql_export_fields` text COLLATE utf8_unicode_ci after sql_edit_fields,
 add column `sql_export_order` text COLLATE utf8_unicode_ci after sql_export_fields,
 add column `sql_print_fields` text COLLATE utf8_unicode_ci after sql_export_order,
 add column `sql_print_order` text COLLATE utf8_unicode_ci after sql_print_fields,
 add column `file_perpage` int(11) unsigned DEFAULT NULL after sql_print_order,
 add column `file_path` varchar(255) CHARACTER SET utf16 DEFAULT NULL after file_perpage,
 add column `file_search` varchar(255) CHARACTER SET utf8 DEFAULT NULL after file_path;
 
CREATE TABLE `aModuleType` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aModuleType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aLastUpdate` datetime DEFAULT NULL,
  `refid_aUser_update` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `aModuleType` VALUES (1,'sql',NULL,NULL);
INSERT INTO `aModuleType` VALUES (2,'file',NULL,NULL);

INSERT INTO `aRef` VALUES (10,13,'refid_aModuleType','Modultyp','aModuleType','aModuleType','aModuleType',NULL,NULL);

INSERT INTO `aTable` VALUES (13,'aModuleType','Modultypen',NULL,NULL);

INSERT INTO `aField` VALUES (46,4,'refid_aModuleType','',2,NULL,NULL);
INSERT INTO `aField` VALUES (47,13,'aModuleType','Modultyp',1,NULL,NULL);
INSERT INTO `aField` VALUES (48,4,'file_perpage','pro Seite',2,NULL,NULL);
INSERT INTO `aField` VALUES (49,4,'file_path','Pfad',1,NULL,NULL);
INSERT INTO `aField` VALUES (50,4,'file_search','Suchmaske',1,NULL,NULL);
INSERT INTO `aField` VALUES (51,4,'sql_table_perpage','Tabelle - pro Seite',2,NULL,NULL);
INSERT INTO `aField` VALUES (53,4,'sql_table_search','Tabelle - Suchfelder',1,NULL,NULL);
INSERT INTO `aField` VALUES (54,4,'sql_table_fields','Tabelle - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (55,4,'sql_table_order','Tabelle - Sortierung',1,NULL,NULL);
INSERT INTO `aField` VALUES (56,4,'sql_edit_legend','Edit - Legende (Felder)',1,NULL,NULL);
INSERT INTO `aField` VALUES (57,4,'sql_edit_fields','Edit - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (58,4,'sql_export_fields','Export - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (59,4,'sql_export_order','Export - Sortierung',1,NULL,NULL);
INSERT INTO `aField` VALUES (60,4,'sql_print_fields','Druck - Felder',1,NULL,NULL);
INSERT INTO `aField` VALUES (61,4,'sql_print_order','Druck - Sortierung',1,NULL,NULL);