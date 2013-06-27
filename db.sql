CREATE TABLE IF NOT EXISTS `ingress_portals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lat` int(11) NOT NULL,
  `lng` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `owner` varchar(255) NOT NULL,
  `owner_since` datetime NOT NULL,
  `faction` varchar(1) NOT NULL,
  `discovered` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lat` (`lat`,`lng`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ingress_portal_watch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `portal` int(11) NOT NULL,
  `player` varchar(50) NOT NULL,
  `since` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`portal`,`player`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

