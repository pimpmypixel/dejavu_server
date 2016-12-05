CREATE TABLE `configurations` (
  `id`                INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(50) DEFAULT NULL,
  `date`              INT(12) DEFAULT NULL,
  `folder`            VARCHAR(200) DEFAULT NULL,
  `samplerate`        INT(5) DEFAULT NULL,
  `window_size`       INT(11) DEFAULT NULL,
  `overlap_ratio`     FLOAT DEFAULT NULL,
  `fan_value`         INT(11) DEFAULT NULL,
  `neighborhood_size` INT(11) DEFAULT NULL,
  `min_hash`          INT(11) DEFAULT NULL,
  `max_hash`          INT(11) DEFAULT NULL,
  `fingerprint_redux` INT(11) DEFAULT NULL,
  `fp_table`          VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  AUTO_INCREMENT =3
  DEFAULT CHARSET =latin1;


CREATE TABLE `states` (
  `id`     INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date`   INT(11) DEFAULT NULL,
  `active` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1;

  CREATE TABLE `uploads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `file` varchar(255) COLLATE latin1_danish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
