CREATE TABLE IF NOT EXISTS `mailchimp_civicrm_account_import` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  `source` varchar(64) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_id` int(11) NOT NULL COMMENT 'The user contact id that created the record',
  `process_start` datetime NOT NULL,
  `process_end` datetime DEFAULT NULL,
  `handle` varchar(128) DEFAULT NULL,
  `fields` text,
  `count` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


