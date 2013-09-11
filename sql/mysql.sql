# Quote all table names with '{' and '}', and prefix all system tables with 'core.'

CREATE TABLE `{protal}` (
  `id`              int(10) UNSIGNED               NOT NULL AUTO_INCREMENT,
  `name`           varchar(64)                    NOT NULL default '',
  `url`             varchar(255)                   NOT NULL default '',
  `time_create`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `user_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY  (`id`)
);

CREATE TABLE `{supplier}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `name`            varchar(64)                     NOT NULL DEFAULT '',
  `url`             varchar(255)                    NOT NULL DEFAULT '',
  `time_create`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `user_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY  (`id`)
);

CREATE TABLE `{channel}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `protal_id`       int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `name`            varchar(64)                     NOT NULL DEFAULT '',
  `time_create`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `user_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY  (`id`)
);

CREATE TABLE `{adinfo}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `protal_id`       int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `channel_id`      int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `adformat`        varchar(64)                     NOT NULL DEFAULT '',
  `url`             varchar(255)                    NOT NULL DEFAULT '',
  `supplier_id`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `content`         text,
  `ad_date`         int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_create`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `user_update`     int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY  (`id`)
);
