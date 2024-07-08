ALTER TABLE phpbb_icons ADD icon_auth VARCHAR(255) NOT NULL DEFAULT '';
UPDATE phpbb_icons SET icon_auth = '';
UPDATE phpbb_icons SET icon_auth = 'auth_post' WHERE icon_acl = 1;
UPDATE phpbb_icons SET icon_auth = 'auth_mod' WHERE icon_acl = 3;
UPDATE phpbb_icons SET icon_auth = 'auth_manage' WHERE icon_acl = 5;
ALTER TABLE phpbb_icons DROP icon_acl;

ALTER TABLE phpbb_topics ADD topic_sub_type mediumint(5) NOT NULL DEFAULT '0';

CREATE TABLE phpbb_topics_attr (
  attr_id mediumint(5) unsigned NOT NULL auto_increment,
  attr_name varchar(50) NOT NULL DEFAULT '',
  attr_fname varchar(50) DEFAULT NULL,
  attr_fimg varchar(50) DEFAULT NULL,
  attr_tname varchar(50) DEFAULT NULL,
  attr_timg varchar(50) DEFAULT NULL,
  attr_order mediumint(8) NOT NULL DEFAULT '0',
  attr_field varchar(50) DEFAULT NULL,
  attr_cond char(2) DEFAULT NULL,
  attr_value smallint(3) NOT NULL DEFAULT '0',
  attr_auth varchar(50) DEFAULT NULL,
  PRIMARY KEY  (attr_id)
);
