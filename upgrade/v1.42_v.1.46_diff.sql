ALTER TABLE `aModule` ADD `aModuleVersion` VARCHAR(255) NULL DEFAULT NULL AFTER `aModuleDesc`; 
update aModule set aModuleVersion='1.46 (14.12.2015)' where aModule like 'a%';
