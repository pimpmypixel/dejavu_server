-- Create syntax for TABLE 'configurations'
CREATE TABLE `configurations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `date` int(12) DEFAULT NULL,
  `folder` varchar(200) DEFAULT NULL,
  `samplerate` int(5) DEFAULT NULL,
  `window_size` int(11) DEFAULT NULL,
  `overlap_ratio` float DEFAULT NULL,
  `fan_value` int(11) DEFAULT NULL,
  `neighborhood_size` int(11) DEFAULT NULL,
  `min_hash` int(11) DEFAULT NULL,
  `max_hash` int(11) DEFAULT NULL,
  `fingerprint_redux` int(11) DEFAULT NULL,
  `fp_table` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'fingerprints'
CREATE TABLE `fingerprints` (
  `hash` binary(10) NOT NULL,
  `song_id` mediumint(8) unsigned NOT NULL,
  `offset` int(10) unsigned NOT NULL,
  UNIQUE KEY `unique_constraint` (`song_id`,`offset`,`hash`),
  KEY `hash` (`hash`),
  CONSTRAINT `fingerprints_ibfk_1` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'eventlog'
CREATE TABLE `eventlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session` int(50) DEFAULT NULL,
  `date` int(16) DEFAULT NULL,
  `key` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `session` (`session`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'match'
CREATE TABLE `match` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session` int(5) DEFAULT NULL,
  `song` int(11) DEFAULT NULL,
  `confidence` int(11) DEFAULT NULL,
  `offset` int(11) DEFAULT NULL,
  `offset_secs` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'sessions'
CREATE TABLE `sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(20) DEFAULT NULL,
  `config` int(11) DEFAULT NULL,
  `session` varchar(20) DEFAULT NULL,
  `vpn` varchar(20) DEFAULT NULL,
  `remote` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'songs'
CREATE TABLE `songs` (
  `song_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `song_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
  `fingerprinted` tinyint(4) DEFAULT '0',
  `file_sha1` binary(20) NOT NULL,
  `filecrdate` int(11) NOT NULL,
  `configuration` int(5) NOT NULL,
  `duration` varchar(25) NOT NULL,
  PRIMARY KEY (`song_id`),
  UNIQUE KEY `song_id` (`song_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'states'
CREATE TABLE `states` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'uploads'
CREATE TABLE `uploads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(12) DEFAULT NULL,
  `file` varchar(255) COLLATE latin1_danish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
