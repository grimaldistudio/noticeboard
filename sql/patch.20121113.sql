ALTER TABLE  `documents` ADD  `publication_number` VARCHAR( 128 ) NULL AFTER  `protocol_number`;
ALTER TABLE  `documents` ADD  `sync_file` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `relative_path`;