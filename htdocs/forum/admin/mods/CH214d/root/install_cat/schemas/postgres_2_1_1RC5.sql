
ALTER TABLE phpbb_forums ADD COLUMN forum_subs_hidden int2;

UDAPTE phpbb_forums SET forum_topics_ppage = 0 WHERE forum_topics_ppage IS NULL;
UDAPTE phpbb_forums SET forum_index_pack = 0 WHERE forum_index_pack IS NULL;
UDAPTE phpbb_forums SET forum_index_split = 0 WHERE forum_index_split IS NULL;
UDAPTE phpbb_forums SET forum_board_box = 0 WHERE forum_board_box IS NULL;
UPDATE phpbb_forums SET forum_subs_hidden = 0;

ALTER TABLE phpbb_forums ALTER COLUMN forum_topics_ppage SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_pack SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_split SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_board_box SET DEFAULT 0;
ALTER TABLE phpbb_forums ALTER COLUMN forum_subs_hidden SET DEFAULT 0;

ALTER TABLE phpbb_forums ALTER COLUMN forum_topics_ppage SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_pack SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_index_split SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_board_box SET NOT NULL;
ALTER TABLE phpbb_forums ALTER COLUMN forum_subs_hidden SET NOT NULL;


ALTER TABLE phpbb_users ADD COLUMN user_dst int2;

UPDATE phpbb_users SET user_dst = 0;

ALTER TABLE phpbb_users ALTER COLUMN user_dst SET DEFAULT 0;

ALTER TABLE phpbb_users ALTER COLUMN user_dst SET NOT NULL;
