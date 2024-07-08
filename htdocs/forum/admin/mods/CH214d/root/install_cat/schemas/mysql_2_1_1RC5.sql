
ALTER TABLE phpbb_forums ADD forum_subs_hidden TINYINT( 1 ) NOT NULL;

ALTER TABLE phpbb_users ADD user_dst smallint(1) NOT NULL DEFAULT '0';

DROP TABLE phpbb_users_cache;
CREATE TABLE phpbb_users_cache (
  user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
  cache_id VARCHAR(5) NOT NULL DEFAULT '',
  cache_data LONGTEXT,
  cache_time INT(11) DEFAULT '0',
  PRIMARY KEY  ( user_id, cache_id )
);
