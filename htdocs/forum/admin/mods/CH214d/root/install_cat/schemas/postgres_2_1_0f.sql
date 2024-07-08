
ALTER TABLE phpbb_icons ADD COLUMN icon_auth varchar(255);
UPDATE phpbb_icons SET icon_auth = '';
UPDATE phpbb_icons SET icon_auth = 'auth_post' WHERE icon_acl = 1;
UPDATE phpbb_icons SET icon_auth = 'auth_mod' WHERE icon_acl = 3;
UPDATE phpbb_icons SET icon_auth = 'auth_manage' WHERE icon_acl = 5;

ALTER TABLE phpbb_icons ALTER COLUMN icon_auth SET DEFAULT '';
ALTER TABLE phpbb_icons ALTER COLUMN icon_auth SET NOT NULL;
ALTER TABLE phpbb_icons DROP COLUMN icon_acl;

ALTER TABLE phpbb_topics ADD COLUMN topic_sub_type int4;
UPDATE phpbb_topics SET topic_sub_type = 0;
ALTER TABLE phpbb_topics ALTER COLUMN topic_sub_type SET DEFAULT 0;
ALTER TABLE phpbb_topics ALTER COLUMN topic_sub_type SET NOT NULL;

CREATE SEQUENCE phpbb_topics_attr_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;
CREATE TABLE phpbb_topics_attr (
  attr_id int4 NOT NULL DEFAULT nextval('phpbb_topics_attr_id_seq'::text),
  attr_name varchar(50) NOT NULL DEFAULT '',
  attr_fname varchar(50) DEFAULT NULL,
  attr_fimg varchar(50) DEFAULT NULL,
  attr_tname varchar(50) DEFAULT NULL,
  attr_timg varchar(50) DEFAULT NULL,
  attr_order int4 NOT NULL DEFAULT 0,
  attr_field varchar(50) DEFAULT NULL,
  attr_cond varchar(2) DEFAULT NULL,
  attr_value int2 NOT NULL DEFAULT 0,
  attr_auth varchar(50) DEFAULT NULL,
  CONSTRAINT phpbb_topics_attr_pkey PRIMARY KEY  ( attr_id )
);
