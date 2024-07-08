
ALTER TABLE phpbb_forums CHANGE forum_style forum_style MEDIUMINT( 8 ) NOT NULL DEFAULT '0'

ALTER TABLE phpbb_auths ADD INDEX auth_name ( obj_type , auth_name )
