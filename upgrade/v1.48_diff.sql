delete from aModule where aModule = 'aSetup' limit 1;
update aModule set aModuleVersion='1.48 (27.12.2015)' where aModule like 'a%';
