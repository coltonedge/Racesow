ALTER TABLE phpbb_forums DROP COLUMN forum_type CASCADE;
ALTER TABLE phpbb_forums ADD COLUMN forum_type varchar(1);
ALTER TABLE phpbb_forums ADD COLUMN forum_main int4;
ALTER TABLE phpbb_forums ADD COLUMN auth_global_announce int2;
ALTER TABLE phpbb_forums ADD COLUMN forum_last_title varchar( 255 );
ALTER TABLE phpbb_forums ADD COLUMN forum_last_poster int4;
ALTER TABLE phpbb_forums ADD COLUMN forum_last_username varchar( 25 );
ALTER TABLE phpbb_forums ADD COLUMN forum_last_time int4;
ALTER TABLE phpbb_forums ADD COLUMN forum_link varchar( 255 );
ALTER TABLE phpbb_forums ADD COLUMN forum_link_hit_count int2;
ALTER TABLE phpbb_forums ADD COLUMN forum_link_hit bigint;
ALTER TABLE phpbb_forums ADD COLUMN forum_link_start int4;
ALTER TABLE phpbb_forums ADD COLUMN forum_style int4;
ALTER TABLE phpbb_forums ADD COLUMN forum_nav_icon varchar( 255 );
ALTER TABLE phpbb_forums ADD COLUMN forum_icon varchar( 255 );
ALTER TABLE phpbb_forums ADD COLUMN forum_topics_ppage int2;
ALTER TABLE phpbb_forums ADD COLUMN forum_topics_sort varchar( 25 );
ALTER TABLE phpbb_forums ADD COLUMN forum_topics_order varchar( 4 );
ALTER TABLE phpbb_forums ADD COLUMN forum_index_pack int2;
ALTER TABLE phpbb_forums ADD COLUMN forum_index_split int2;
ALTER TABLE phpbb_forums ADD COLUMN forum_board_box int2;
ALTER TABLE phpbb_forums ADD COLUMN forum_subs_hidden int2;

UPDATE phpbb_forums SET forum_type = 'f';
UPDATE phpbb_forums SET forum_main = 0;
UPDATE phpbb_forums SET auth_global_announce = 0;
UPDATE phpbb_forums SET forum_last_poster = 0;
UPDATE phpbb_forums SET forum_last_time = 0;
UPDATE phpbb_forums SET forum_link_hit_count = 0;
UPDATE phpbb_forums SET forum_link_hit = 0;
UPDATE phpbb_forums SET forum_link_start = 0;
UPDATE phpbb_forums SET forum_style = 0;
UPDATE phpbb_forums SET forum_topics_ppage = 0;
UPDATE phpbb_forums SET forum_index_pack = 0;
UPDATE phpbb_forums SET forum_index_split = 0;
UPDATE phpbb_forums SET forum_board_box = 0;
UPDATE phpbb_forums SET forum_subs_hidden = 0;

ALTER TABLE phpbb_forums ALTER COLUMN forum_type SET DEFAULT 'f';
ALTER TABLE phpbb_forums ALTER COLUMN forum_main SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN auth_global_announce SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_last_poster SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_last_time SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_link_hit_count SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_link_hit SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_link_start SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_style SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_topics_ppage SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_pack SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_split SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_board_box SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_subs_hidden SET DEFAULT 0;

ALTER TABLE phpbb_forums ALTER COLUMN forum_type SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_main SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN auth_global_announce SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_last_poster SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_last_time SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_link_hit_count SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_link_hit SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_link_start SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_style SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_topics_ppage SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_pack SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_split SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_board_box SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_subs_hidden SET NOT NULL;

ALTER TABLE phpbb_auth_access ADD COLUMN auth_global_announce int2;

UPDATE phpbb_auth_access SET auth_global_announce = 0;
ALTER TABLE phpbb_auth_access ALTER COLUMN auth_global_announce SET DEFAULT 0;
ALTER TABLE phpbb_auth_access ALTER COLUMN auth_global_announce SET NOT NULL;

ALTER TABLE phpbb_topics ADD COLUMN topic_sub_type int4;
ALTER TABLE phpbb_topics ADD COLUMN topic_sub_title varchar( 255 );
ALTER TABLE phpbb_topics ADD COLUMN topic_first_username varchar( 25 );
ALTER TABLE phpbb_topics ADD COLUMN topic_last_poster int4;
ALTER TABLE phpbb_topics ADD COLUMN topic_last_username varchar( 25 );
ALTER TABLE phpbb_topics ADD COLUMN topic_last_time int4;
ALTER TABLE phpbb_topics ADD COLUMN topic_icon int2;
ALTER TABLE phpbb_topics ADD COLUMN topic_duration int4;

ALTER TABLE phpbb_topics ALTER COLUMN topic_title TYPE varchar( 255 );

UPDATE phpbb_topics SET topic_sub_type = 0;
UPDATE phpbb_topics SET topic_last_poster = 0;
UPDATE phpbb_topics SET topic_last_time = 0;
UPDATE phpbb_topics SET topic_icon = 0;
UPDATE phpbb_topics SET topic_duration = 0;

ALTER TABLE phpbb_topics ALTER COLUMN topic_sub_type SET DEFAULT 0;
ALTER TABLE phpbb_topics ALTER COLUMN topic_last_poster SET DEFAULT 0;
ALTER TABLE phpbb_topics ALTER COLUMN topic_last_time SET DEFAULT 0;
ALTER TABLE phpbb_topics ALTER COLUMN topic_icon SET DEFAULT 0;
ALTER TABLE phpbb_topics ALTER COLUMN topic_duration SET DEFAULT 0;

ALTER TABLE phpbb_topics ALTER COLUMN topic_sub_type SET NOT NULL;
ALTER TABLE phpbb_topics ALTER COLUMN topic_last_poster SET NOT NULL;
ALTER TABLE phpbb_topics ALTER COLUMN topic_last_time SET NOT NULL;
ALTER TABLE phpbb_topics ALTER COLUMN topic_icon SET NOT NULL;
ALTER TABLE phpbb_topics ALTER COLUMN topic_duration SET NOT NULL;

ALTER TABLE phpbb_posts ADD COLUMN post_icon int2;

UPDATE phpbb_posts SET post_icon = 0;
ALTER TABLE phpbb_posts ALTER COLUMN post_icon SET DEFAULT 0;
ALTER TABLE phpbb_posts ALTER COLUMN post_icon SET NOT NULL;

ALTER TABLE phpbb_posts_text ADD COLUMN post_sub_title varchar( 255 );
ALTER TABLE phpbb_posts_text ALTER COLUMN post_subject TYPE varchar( 255 );

ALTER TABLE phpbb_users ADD COLUMN user_unread_date int4;
ALTER TABLE phpbb_users ADD COLUMN user_unread_topics text;
ALTER TABLE phpbb_users ADD COLUMN user_keep_unreads int2;
ALTER TABLE phpbb_users ADD COLUMN user_topics_sort varchar( 25 );
ALTER TABLE phpbb_users ADD COLUMN user_topics_order varchar( 4 );
ALTER TABLE phpbb_users ADD COLUMN user_smart_date int2;
ALTER TABLE phpbb_users ADD COLUMN user_dst int2;
ALTER TABLE phpbb_users ADD COLUMN user_board_box int2;
ALTER TABLE phpbb_users ADD COLUMN user_index_pack int2;
ALTER TABLE phpbb_users ADD COLUMN user_index_split int2;
ALTER TABLE phpbb_users ADD COLUMN user_session_logged int2;

UPDATE phpbb_users SET user_unread_date = 0;
UPDATE phpbb_users SET user_keep_unreads = 0;
UPDATE phpbb_users SET user_topics_sort = '';
UPDATE phpbb_users SET user_topics_order = '';
UPDATE phpbb_users SET user_smart_date = 0;
UPDATE phpbb_users SET user_dst = 0;
UPDATE phpbb_users SET user_board_box = 0;
UPDATE phpbb_users SET user_index_pack = 0;
UPDATE phpbb_users SET user_index_split = 0;
UPDATE phpbb_users SET user_session_logged = 0;

ALTER TABLE phpbb_users ALTER COLUMN user_unread_date SET DEFAULT 0;
ALTER TABLE phpbb_users ALTER COLUMN user_keep_unreads SET DEFAULT 0;
ALTER TABLE phpbb_users ALTER COLUMN user_topics_sort SET DEFAULT '';
ALTER TABLE phpbb_users ALTER COLUMN user_topics_order SET DEFAULT '';
ALTER TABLE phpbb_users ALTER COLUMN user_smart_date SET DEFAULT 0;
ALTER TABLE phpbb_users ALTER COLUMN user_dst SET DEFAULT 0;
ALTER TABLE phpbb_users ALTER COLUMN user_board_box SET DEFAULT 0;
ALTER TABLE phpbb_users ALTER COLUMN user_index_pack SET DEFAULT 0;
ALTER TABLE phpbb_users ALTER COLUMN user_index_split SET DEFAULT 0;
ALTER TABLE phpbb_users ALTER COLUMN user_session_logged SET DEFAULT 0;

ALTER TABLE phpbb_users ALTER COLUMN user_unread_date SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_keep_unreads SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_topics_sort SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_topics_order SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_smart_date SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_dst SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_board_box SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_index_pack SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_index_split SET NOT NULL;
ALTER TABLE phpbb_users ALTER COLUMN user_session_logged SET NOT NULL;

ALTER TABLE phpbb_groups ADD COLUMN group_status int2;
ALTER TABLE phpbb_groups ADD COLUMN group_user_id int4;
ALTER TABLE phpbb_groups ADD COLUMN group_user_list text;

UPDATE phpbb_groups SET group_status = 0;
UPDATE phpbb_groups SET group_user_id = 0;
UPDATE phpbb_groups SET group_user_list = '';

ALTER TABLE phpbb_groups ALTER COLUMN group_status SET DEFAULT 0;
ALTER TABLE phpbb_groups ALTER COLUMN group_user_id SET DEFAULT 0;
ALTER TABLE phpbb_groups ALTER COLUMN group_user_list SET DEFAULT '';

ALTER TABLE phpbb_groups ALTER COLUMN group_status SET NOT NULL;
ALTER TABLE phpbb_groups ALTER COLUMN group_user_id SET NOT NULL;
ALTER TABLE phpbb_groups ALTER COLUMN group_user_list SET NOT NULL;

ALTER TABLE phpbb_themes ADD COLUMN images_pack varchar( 100 );
ALTER TABLE phpbb_themes ADD COLUMN custom_tpls varchar( 100 );

UPDATE phpbb_themes SET images_pack = '';
UPDATE phpbb_themes SET custom_tpls = '';

ALTER TABLE phpbb_themes ALTER COLUMN images_pack SET DEFAULT '';
ALTER TABLE phpbb_themes ALTER COLUMN custom_tpls SET DEFAULT '';

ALTER TABLE phpbb_themes ALTER COLUMN images_pack SET NOT NULL;
ALTER TABLE phpbb_themes ALTER COLUMN custom_tpls SET NOT NULL;

CREATE SEQUENCE phpbb_presets_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;
CREATE SEQUENCE phpbb_icons_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;
CREATE SEQUENCE phpbb_cp_fields_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;
CREATE SEQUENCE phpbb_cp_panels_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;
CREATE SEQUENCE phpbb_cp_patches_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;
CREATE SEQUENCE phpbb_auths_def_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;
CREATE SEQUENCE phpbb_topics_attr_id_seq start 1 increment 1 maxvalue 2147483647 minvalue 1 cache 1;

CREATE TABLE phpbb_users_cache (
  user_id int4 NOT NULL DEFAULT 0,
  cache_id varchar(5) NOT NULL DEFAULT '',
  cache_data text,
  cache_time int4 DEFAULT 0,
  CONSTRAINT phpbb_users_cache_pkey PRIMARY KEY ( user_id, cache_id )
);

CREATE TABLE phpbb_presets (
  preset_id int4 NOT NULL DEFAULT nextval('phpbb_presets_id_seq'::text),
  preset_type varchar(5) NOT NULL DEFAULT '',
  preset_name varchar(50) NOT NULL DEFAULT '',
  CONSTRAINT phpbb_presets_pkey PRIMARY KEY ( preset_id )
);

CREATE TABLE phpbb_presets_data (
  preset_id int4 NOT NULL DEFAULT 0,
  preset_auth varchar(50) NOT NULL DEFAULT '',
  preset_value int2 DEFAULT 0,
  CONSTRAINT phpbb_presets_data_pkey PRIMARY KEY ( preset_id, preset_auth )
);

CREATE TABLE phpbb_icons (
  icon_id int4 NOT NULL DEFAULT nextval('phpbb_icons_id_seq'::text),
  icon_name varchar(50) NOT NULL DEFAULT '',
  icon_url varchar(255) NOT NULL DEFAULT '',
  icon_auth varchar(50) NOT NULL DEFAULT '',
  icon_types varchar(255) DEFAULT NULL,
  icon_order int4 NOT NULL DEFAULT 0,
  CONSTRAINT phpbb_icons_pkey PRIMARY KEY  ( icon_id )
);

CREATE TABLE phpbb_cp_fields (
  field_id int4 NOT NULL DEFAULT nextval('phpbb_cp_fields_id_seq'::text),
  field_name varchar(50) NOT NULL DEFAULT '',
  panel_id int4 NOT NULL DEFAULT 0,
  field_order int4 NOT NULL DEFAULT 0,
  field_attr text NOT NULL DEFAULT '',
  CONSTRAINT phpbb_cp_fields_pkey PRIMARY KEY  ( field_id )
);

CREATE TABLE phpbb_cp_panels (
  panel_id int4 NOT NULL DEFAULT nextval('phpbb_cp_panels_id_seq'::text),
  panel_shortcut varchar(30) NOT NULL DEFAULT '',
  panel_name varchar(50) NOT NULL DEFAULT '',
  panel_main int4 NOT NULL DEFAULT 0,
  panel_order int4 NOT NULL DEFAULT 0,
  panel_file varchar(50) NOT NULL DEFAULT '',
  panel_auth_type varchar(1) NOT NULL DEFAULT '',
  panel_auth_name varchar(50) NOT NULL DEFAULT '',
  panel_hidden int2 NOT NULL DEFAULT 0,
  CONSTRAINT phpbb_cp_panels_pkey PRIMARY KEY  ( panel_id )
);

CREATE TABLE phpbb_cp_patches (
  patch_id int4 NOT NULL DEFAULT nextval('phpbb_cp_patches_id_seq'::text),
  patch_file varchar(255) NOT NULL DEFAULT '',
  patch_time int4 NOT NULL DEFAULT 0,
  patch_version varchar(25) NOT NULL DEFAULT '',
  patch_date varchar(8) NOT NULL DEFAULT '',
  patch_ref varchar(255) NOT NULL DEFAULT '',
  patch_author varchar(255) NOT NULL DEFAULT '',
  CONSTRAINT phpbb_cp_patches_pkey PRIMARY KEY  ( patch_id )
);

CREATE TABLE phpbb_auths_def (
  auth_id int4 NOT NULL DEFAULT nextval('phpbb_auths_def_id_seq'::text),
  auth_type varchar(1) NOT NULL DEFAULT '',
  auth_name varchar(50) NOT NULL DEFAULT '',
  auth_desc varchar(255) NOT NULL DEFAULT '',
  auth_title int2 NOT NULL DEFAULT 0,
  auth_order int4 NOT NULL DEFAULT 0,
  CONSTRAINT phpbb_auths_def_pkey PRIMARY KEY  ( auth_id )
);

CREATE TABLE phpbb_auths (
  group_id int4 NOT NULL DEFAULT 0,
  obj_type varchar(1) NOT NULL DEFAULT '',
  obj_id int4 NOT NULL DEFAULT 0,
  auth_name varchar(50) NOT NULL DEFAULT '',
  auth_value int2 NOT NULL DEFAULT 0,
  CONSTRAINT phpbb_auths_pkey PRIMARY KEY ( group_id, obj_type, obj_id, auth_name )
);

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

ALTER TABLE phpbb_config ADD COLUMN config_static int2;

UPDATE phpbb_config SET config_static = 0;
ALTER TABLE phpbb_config ALTER COLUMN config_static SET DEFAULT 0;
ALTER TABLE phpbb_config ALTER COLUMN config_static SET NOT NULL;

CREATE INDEX panel_id_phpbb_cp_fields_index ON phpbb_cp_fields (panel_id, field_name);
CREATE INDEX group_id_phpbb_auths_index ON phpbb_auths (group_id, obj_type);
CREATE INDEX obj_type_phpbb_auths_index ON phpbb_auths (obj_type, obj_id);
CREATE INDEX auth_name_phpbb_auths_index ON phpbb_auths (obj_type, auth_name);
CREATE INDEX topic_last_time_phpbb_topics_index ON phpbb_topics (topic_last_time);
CREATE INDEX post_icon_phpbb_posts_index ON phpbb_posts (post_icon);
CREATE INDEX group_user_id_phpbb_groups_index ON phpbb_groups (group_user_id);
CREATE INDEX config_static_phpbb_config_index ON phpbb_config (config_static);
