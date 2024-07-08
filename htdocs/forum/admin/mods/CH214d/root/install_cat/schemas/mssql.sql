
BEGIN TRANSACTION
GO

ALTER TABLE [phpbb_forums] DROP COLUMN [forum_type]
GO

ALTER TABLE [phpbb_forums] ADD [forum_type] [char] (1)
GO

ALTER TABLE [phpbb_forums] ADD [forum_main] [smallint]
GO

ALTER TABLE [phpbb_forums] ADD [auth_global_announce] [smallint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_last_title] [varchar] (255) NULL
GO

ALTER TABLE [phpbb_forums] ADD [forum_last_poster] [bigint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_last_username] [varchar] (25) NULL
GO

ALTER TABLE [phpbb_forums] ADD [forum_last_time] [int]
GO

ALTER TABLE [phpbb_forums] ADD [forum_link] [varchar] (255) NULL
GO

ALTER TABLE [phpbb_forums] ADD [forum_link_hit_count] [smallint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_link_hit] [bigint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_link_start] [int]
GO

ALTER TABLE [phpbb_forums] ADD [forum_style] [int]
GO

ALTER TABLE [phpbb_forums] ADD [forum_nav_icon] [varchar] (255) NULL
GO

ALTER TABLE [phpbb_forums] ADD [forum_icon] [varchar] (255) NULL
GO

ALTER TABLE [phpbb_forums] ADD [forum_topics_ppage] [smallint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_topics_sort] [varchar] (25) NULL
GO

ALTER TABLE [phpbb_forums] ADD [forum_topics_order] [varchar] (4) NULL
GO

ALTER TABLE [phpbb_forums] ADD [forum_index_pack] [smallint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_index_split] [smallint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_board_box] [smallint]
GO

ALTER TABLE [phpbb_forums] ADD [forum_subs_hidden] [smallint]
GO

UPDATE [phpbb_forums] SET
	forum_type = 'f',
	forum_main = 0,
	auth_global_announce = 0,
	forum_last_poster = 0,
	forum_last_time = 0,
	forum_link_hit_count = 0,
	forum_link_hit = 0,
	forum_link_start = 0,
	forum_style = 0,
	forum_topics_ppage = 0,
	forum_index_pack = 0,
	forum_index_split = 0,
	forum_board_box = 0,
	forum_subs_hidden = 0
GO

ALTER TABLE [phpbb_forums] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_forums_forum_type] NOT NULL DEFAULT ('f') FOR [forum_type],
	CONSTRAINT [DF_phpbb_forums_forum_main] NOT NULL DEFAULT (0) FOR [forum_main],
	CONSTRAINT [DF_phpbb_forums_auth_global_announce] NOT NULL DEFAULT (0) FOR [auth_global_announce],
	CONSTRAINT [DF_phpbb_forums_forum_last_poster] NOT NULL DEFAULT (0) FOR [forum_last_poster],
	CONSTRAINT [DF_phpbb_forums_forum_last_time] NOT NULL DEFAULT (0) FOR [forum_last_time],
	CONSTRAINT [DF_phpbb_forums_forum_link_hit_count] NOT NULL DEFAULT (0) FOR [forum_link_hit_count],
	CONSTRAINT [DF_phpbb_forums_forum_link_hit] NOT NULL DEFAULT (0) FOR [forum_link_hit],
	CONSTRAINT [DF_phpbb_forums_forum_link_start] NOT NULL DEFAULT (0) FOR [forum_link_start],
	CONSTRAINT [DF_phpbb_forums_forum_style] NOT NULL DEFAULT (0) FOR [forum_style],
	CONSTRAINT [DF_phpbb_forums_forum_topics_ppage] NOT NULL DEFAULT (0) FOR [forum_topics_ppage],
	CONSTRAINT [DF_phpbb_forums_forum_index_pack] NOT NULL DEFAULT (0) FOR [forum_index_pack],
	CONSTRAINT [DF_phpbb_forums_forum_index_split] NOT NULL DEFAULT (0) FOR [forum_index_split],
	CONSTRAINT [DF_phpbb_forums_forum_board_box] NOT NULL DEFAULT (0) FOR [forum_board_box],
	CONSTRAINT [DF_phpbb_forums_forum_subs_hidden] NOT NULL DEFAULT (0) FOR [forum_subs_hidden],
	CONSTRAINT [DF_phpbb_forums_forum_cat_id] DEFAULT (0) FOR [forum_cat_id],
	CONSTRAINT [DF_phpbb_forums_forum_status] DEFAULT (0) FOR [forum_status],
	CONSTRAINT [DF_phpbb_forums_forum_order] DEFAULT (0) FOR [forum_order]
GO


ALTER TABLE [phpbb_auth_access] ADD [auth_global_announce] [smallint]
GO

UPDATE [phpbb_auth_access] SET
	auth_global_announce = 0
GO

ALTER TABLE [phpbb_auth_access] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_auth_access_auth_global_announce] NOT NULL DEFAULT (0) FOR [auth_global_announce]
GO


ALTER TABLE [phpbb_topics] ADD [topic_sub_type] [bigint]
GO

ALTER TABLE [phpbb_topics] ADD [topic_sub_title] [varchar] (255) NULL
GO

ALTER TABLE [phpbb_topics] ADD [topic_first_username] [varchar] (25) NULL
GO

ALTER TABLE [phpbb_topics] ADD [topic_last_poster] [bigint]
GO

ALTER TABLE [phpbb_topics] ADD [topic_last_username] [varchar] (25) NULL
GO

ALTER TABLE [phpbb_topics] ADD [topic_last_time] [int]
GO

ALTER TABLE [phpbb_topics] ADD [topic_icon] [smallint]
GO

ALTER TABLE [phpbb_topics] ADD [topic_duration] [int]
GO

ALTER TABLE [phpbb_topics] CHANGE [topic_title] [topic_title] [varchar] (255)
GO

UPDATE [phpbb_topics] SET
	topic_sub_type = 0,
	topic_last_poster = 0,
	topic_last_time = 0,
	topic_icon = 0,
	topic_duration = 0,
	topic_title = ''
GO

ALTER TABLE [phpbb_topics] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_topics_topic_sub_type] NOT NULL DEFAULT (0) FOR [topic_sub_type],
	CONSTRAINT [DF_phpbb_topics_topic_last_poster] NOT NULL DEFAULT (0) FOR [topic_last_poster],
	CONSTRAINT [DF_phpbb_topics_topic_last_time] NOT NULL DEFAULT (0) FOR [topic_last_time],
	CONSTRAINT [DF_phpbb_topics_topic_icon] NOT NULL DEFAULT (0) FOR [topic_icon],
	CONSTRAINT [DF_phpbb_topics_topic_duration] NOT NULL DEFAULT (0) FOR [topic_duration],
	CONSTRAINT [DF_phpbb_topics_topic_title] NOT NULL DEFAULT ('') FOR [topic_title]
GO


ALTER TABLE [phpbb_posts] ADD [post_icon] [smallint]
GO

UPDATE [phpbb_posts] SET
	post_icon = 0
GO

ALTER TABLE [phpbb_posts] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_posts_post_icon] NOT NULL DEFAULT (0) FOR [post_icon]
GO


ALTER TABLE [phpbb_posts_text] ADD [post_sub_title] [varchar] (255) NULL
GO

ALTER TABLE [phpbb_posts_text] CHANGE [post_subject] [post_subject] [varchar] (255) NULL
GO


ALTER TABLE [phpbb_users] ADD [user_unread_date] [int]
GO

ALTER TABLE [phpbb_users] ADD [user_unread_topics] [text] NULL
GO

ALTER TABLE [phpbb_users] ADD [user_keep_unreads] [smallint]
GO

ALTER TABLE [phpbb_users] ADD [user_topics_sort] [varchar] (25)
GO

ALTER TABLE [phpbb_users] ADD [user_topics_order] [varchar] (4)
GO

ALTER TABLE [phpbb_users] ADD [user_smart_date] [smallint]
GO

ALTER TABLE [phpbb_users] ADD [user_dst] [smallint]
GO

ALTER TABLE [phpbb_users] ADD [user_board_box] [smallint]
GO

ALTER TABLE [phpbb_users] ADD [user_index_pack] [smallint]
GO

ALTER TABLE [phpbb_users] ADD [user_index_split] [smallint]
GO

ALTER TABLE [phpbb_users] ADD [user_session_logged] [smallint]
GO

UPDATE [phpbb_users] SET
	user_unread_date = 0,
	user_keep_unreads = 0,
	user_topics_sort = '',
	user_topics_order = '',
	user_smart_date = 0,
	user_dst = 0,
	user_board_box = 0,
	user_index_pack = 0,
	user_index_split = 0,
	user_session_logged = 0
GO

ALTER TABLE [phpbb_users] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_users_user_unread_date] NOT NULL DEFAULT (0) FOR [user_unread_date],
	CONSTRAINT [DF_phpbb_users_user_keep_unreads] NOT NULL DEFAULT (0) FOR [user_keep_unreads],
	CONSTRAINT [DF_phpbb_users_user_topics_sort] NOT NULL DEFAULT ('') FOR [user_topics_sort],
	CONSTRAINT [DF_phpbb_users_user_topics_order] NOT NULL DEFAULT ('') FOR [user_topics_order],
	CONSTRAINT [DF_phpbb_users_user_smart_date] NOT NULL DEFAULT (0) FOR [user_smart_date],
	CONSTRAINT [DF_phpbb_users_user_dst] NOT NULL DEFAULT (0) FOR [user_dst],
	CONSTRAINT [DF_phpbb_users_user_board_box] NOT NULL DEFAULT (0) FOR [user_board_box],
	CONSTRAINT [DF_phpbb_users_user_index_pack] NOT NULL DEFAULT (0) FOR [user_index_pack],
	CONSTRAINT [DF_phpbb_users_user_index_split] NOT NULL DEFAULT (0) FOR [user_index_split],
	CONSTRAINT [DF_phpbb_users_user_session_logged] NOT NULL DEFAULT (0) FOR [user_session_logged]
GO


ALTER TABLE [phpbb_groups] ADD [group_status] [smallint]
GO

ALTER TABLE [phpbb_groups] ADD [group_user_id] [bigint]
GO

ALTER TABLE [phpbb_groups] ADD [group_user_list] [text]
GO

UPDATE [phpbb_groups] SET
	group_status = 0,
	group_user_id = 0,
	group_user_list = 0
GO

ALTER TABLE [phpbb_groups] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_groups_group_status] NOT NULL DEFAULT (0) FOR [group_status],
	CONSTRAINT [DF_phpbb_groups_group_user_id] NOT NULL DEFAULT (0) FOR [group_user_id],
	CONSTRAINT [DF_phpbb_groups_group_user_list] NOT NULL DEFAULT ('') FOR [group_user_list]
GO


ALTER TABLE [phpbb_themes] ADD [images_pack] [varchar] (100)
GO

ALTER TABLE [phpbb_themes] ADD [custom_tpls] [varchar] (100)
GO

UPDATE [phpbb_themes] SET
	themes_images_pack = '',
	themes_custom_tpls = ''
GO

ALTER TABLE [phpbb_themes] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_themes_images_pack] NOT NULL DEFAULT ('') FOR [images_pack],
	CONSTRAINT [DF_phpbb_themes_custom_tpls] NOT NULL DEFAULT ('') FOR [custom_tpls]
GO


CREATE TABLE [phpbb_users_cache] (
  [user_id] [bigint],
  [cache_id] [varchar] (5),
  [cache_data] [text] NULL,
  [cache_time] [int] NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [phpbb_users_cache] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_users_cache_user_id] NOT NULL DEFAULT (0) FOR [user_id],
	CONSTRAINT [DF_phpbb_users_cache_cache_id] NOT NULL DEFAULT ('') FOR [cache_id],
	CONSTRAINT [PK_phpbb_users_cache] PRIMARY KEY CLUSTERED ([user_id], [cache_id]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_presets] (
  [preset_id] [bigint] IDENTITY (1,1) NOT NULL,
  [preset_type] [varchar] (5),
  [preset_name] [varchar] (50)
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_presets] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_presets_preset_type] NOT NULL DEFAULT ('') FOR [preset_type],
	CONSTRAINT [DF_phpbb_presets_preset_name] NOT NULL DEFAULT ('') FOR [preset_name],
	CONSTRAINT [PK_phpbb_presets] PRIMARY KEY CLUSTERED ([preset_id]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_presets_data] (
  [preset_id] [bigint],
  [preset_auth] [varchar] (50),
  [preset_value] [smallint]
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_presets_data] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_presets_data_preset_id] NOT NULL DEFAULT (0) FOR [preset_id],
	CONSTRAINT [DF_phpbb_presets_data_preset_auth] NOT NULL DEFAULT ('') FOR [preset_auth],
	CONSTRAINT [DF_phpbb_presets_data_preset_value] NOT NULL DEFAULT (0) FOR [preset_value],
	CONSTRAINT [PK_phpbb_presets_data] PRIMARY KEY CLUSTERED ([preset_id], [preset_auth]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_icons] (
  [icon_id] [bigint] IDENTITY (1,1) NOT NULL,
  [icon_name] [varchar] (50),
  [icon_url] [varchar] (255),
  [icon_auth] [varchar] (50),
  [icon_types] [varchar] (255) NULL,
  [icon_order] [bigint]
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_icons] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_icons_icon_name] NOT NULL DEFAULT ('') FOR [icon_name],
	CONSTRAINT [DF_phpbb_icons_icon_url] NOT NULL DEFAULT ('') FOR [icon_url],
	CONSTRAINT [DF_phpbb_icons_icon_auth] NOT NULL DEFAULT ('') FOR [icon_auth],
	CONSTRAINT [DF_phpbb_icons_icon_order] NOT NULL DEFAULT (0) FOR [icon_order],
	CONSTRAINT [PK_phpbb_icons] PRIMARY KEY CLUSTERED ([icon_id]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_cp_fields] (
  [field_id] [bigint] IDENTITY (1,1) NOT NULL,
  [field_name] [varchar] (50),
  [panel_id] [bigint],
  [field_order] [bigint],
  [field_attr] [text]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [phpbb_cp_fields] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_cp_fields_field_name] NOT NULL DEFAULT ('') FOR [field_name],
	CONSTRAINT [DF_phpbb_cp_fields_panel_id] NOT NULL DEFAULT (0) FOR [panel_id],
	CONSTRAINT [DF_phpbb_cp_fields_field_order] NOT NULL DEFAULT (0) FOR [field_order],
	CONSTRAINT [DF_phpbb_cp_fields_field_attr] NOT NULL DEFAULT ('') FOR [field_attr],
	CONSTRAINT [PK_phpbb_cp_fields] PRIMARY KEY CLUSTERED ([field_id]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_cp_panels] (
  [panel_id] [bigint] IDENTITY (1,1) NOT NULL,
  [panel_shortcut] [varchar] (30),
  [panel_name] [varchar] (50),
  [panel_main] [bigint],
  [panel_order] [bigint],
  [panel_file] [varchar] (50),
  [panel_auth_type] [char] (1),
  [panel_auth_name] [varchar] (50),
  [panel_hidden] [smallint]
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_cp_panels] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_cp_panels_panel_shortcut] NOT NULL DEFAULT ('') FOR [panel_shortcut],
	CONSTRAINT [DF_phpbb_cp_panels_panel_name] NOT NULL DEFAULT ('') FOR [panel_name],
	CONSTRAINT [DF_phpbb_cp_panels_panel_main] NOT NULL DEFAULT (0) FOR [panel_main],
	CONSTRAINT [DF_phpbb_cp_panels_panel_order] NOT NULL DEFAULT (0) FOR [panel_order],
	CONSTRAINT [DF_phpbb_cp_panels_panel_file] NOT NULL DEFAULT ('') FOR [panel_file],
	CONSTRAINT [DF_phpbb_cp_panels_panel_auth_type] NOT NULL DEFAULT ('') FOR [panel_auth_type],
	CONSTRAINT [DF_phpbb_cp_panels_panel_auth_name] NOT NULL DEFAULT ('') FOR [panel_auth_name],
	CONSTRAINT [DF_phpbb_cp_panels_panel_hidden] NOT NULL DEFAULT (0) FOR [panel_hidden],
	CONSTRAINT [PK_phpbb_cp_panels] PRIMARY KEY CLUSTERED ([panel_id]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_cp_patches] (
  [patch_id] [bigint] IDENTITY (1,1) NOT NULL,
  [patch_file] [varchar] (255),
  [patch_time] [int],
  [patch_version] [varchar] (25),
  [patch_date] [varchar] (8),
  [patch_ref] [varchar] (255),
  [patch_author] [varchar] (255)
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_cp_patches] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_cp_patches_patch_file] NOT NULL DEFAULT ('') FOR [patch_file],
	CONSTRAINT [DF_phpbb_cp_patches_patch_time] NOT NULL DEFAULT (0) FOR [patch_time],
	CONSTRAINT [DF_phpbb_cp_patches_patch_version] NOT NULL DEFAULT ('') FOR [patch_version],
	CONSTRAINT [DF_phpbb_cp_patches_patch_date] NOT NULL DEFAULT ('') FOR [patch_date],
	CONSTRAINT [DF_phpbb_cp_patches_patch_ref] NOT NULL DEFAULT ('') FOR [patch_ref],
	CONSTRAINT [DF_phpbb_cp_patches_patch_author] NOT NULL DEFAULT ('') FOR [patch_author],
	CONSTRAINT [PK_phpbb_cp_patches] PRIMARY KEY CLUSTERED ([patch_id]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_auths_def] (
  [auth_id] [smallint] IDENTITY (1,1) NOT NULL,
  [auth_type] [char] (1),
  [auth_name] [varchar] (50),
  [auth_desc] [varchar] (255),
  [auth_title] [smallint],
  [auth_order] [bigint]
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_auths_def] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_auths_def_auth_type] NOT NULL DEFAULT ('') FOR [auth_type],
	CONSTRAINT [DF_phpbb_auths_def_auth_name] NOT NULL DEFAULT ('') FOR [auth_name],
	CONSTRAINT [DF_phpbb_auths_def_auth_desc] NOT NULL DEFAULT ('') FOR [auth_desc],
	CONSTRAINT [DF_phpbb_auths_def_auth_title] NOT NULL DEFAULT (0) FOR [auth_title],
	CONSTRAINT [DF_phpbb_auths_def_auth_order] NOT NULL DEFAULT (0) FOR [auth_order],
	CONSTRAINT [PK_phpbb_auths_def] PRIMARY KEY CLUSTERED ([auth_id]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_auths] (
  [group_id] [bigint],
  [obj_type] [char] (1),
  [obj_id] [bigint],
  [auth_name] [varchar] (50),
  [auth_value] [smallint]
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_auths] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_auths_group_id] NOT NULL DEFAULT (0) FOR [group_id],
	CONSTRAINT [DF_phpbb_auths_obj_type] NOT NULL DEFAULT ('') FOR [obj_type],
	CONSTRAINT [DF_phpbb_auths_obj_id] NOT NULL DEFAULT (0) FOR [obj_id],
	CONSTRAINT [DF_phpbb_auths_auth_name] NOT NULL DEFAULT ('') FOR [auth_name],
	CONSTRAINT [DF_phpbb_auths_auth_value] NOT NULL DEFAULT (0) FOR [auth_value],
	CONSTRAINT [PK_phpbb_auths] PRIMARY KEY CLUSTERED ([group_id], [obj_type], [obj_id], [auth_name]) ON [PRIMARY]
GO


CREATE TABLE [phpbb_topics_attr] (
  [attr_id] [bigint] IDENTITY (1,1) NOT NULL,
  [attr_name] [varchar] (50),
  [attr_fname] [varchar] (50) NULL,
  [attr_fimg] [varchar] (50) NULL,
  [attr_tname] [varchar] (50) NULL,
  [attr_timg] [varchar] (50) NULL,
  [attr_order] [bigint],
  [attr_field] [varchar] (50) NULL,
  [attr_cond] [varchar] (2) NULL,
  [attr_value] [smallint] NULL,
  [attr_auth] [varchar] (50) NULL
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_topics_attr] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_topics_attr_attr_name] NOT NULL DEFAULT ('') FOR [attr_name],
	CONSTRAINT [DF_phpbb_topics_attr_attr_order] NOT NULL DEFAULT (0) FOR [attr_order],
	CONSTRAINT [PK_phpbb_topics_attr] PRIMARY KEY CLUSTERED ([attr_id]) ON [PRIMARY]
GO

ALTER TABLE [phpbb_config] ADD [config_static] [smallint]
GO

UPDATE [phpbb_config] SET
	config_static = 0
GO

ALTER TABLE [phpbb_config] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_config_config_static] NOT NULL DEFAULT (0) FOR [config_static]
GO

CREATE INDEX [IX_phpbb_cp_fields_CH_1] ON [phpbb_cp_fields]([panel_id], [field_name]) ON [PRIMARY]
GO

CREATE INDEX [IX_phpbb_auths_CH_1] ON [phpbb_auths]([group_id], [obj_type]) ON [PRIMARY]
GO

CREATE INDEX [IX_phpbb_auths_CH_2] ON [phpbb_auths]([obj_type], [obj_id]) ON [PRIMARY]
GO

CREATE INDEX [IX_phpbb_auths_CH_3] ON [phpbb_auths]([obj_type], [auth_name]) ON [PRIMARY]
GO

CREATE INDEX [IX_phpbb_topics_CH_1] ON [phpbb_topics]([topic_last_time]) ON [PRIMARY]
GO

CREATE INDEX [IX_phpbb_posts_CH_1] ON [phpbb_posts]([post_icon]) ON [PRIMARY]
GO

CREATE INDEX [IX_phpbb_groups_CH_1] ON [phpbb_groups]([group_user_id]) ON [PRIMARY]
GO

CREATE INDEX [IX_phpbb_config_CH_1] ON [phpbb_config]([config_static]) ON [PRIMARY]
GO

COMMIT
GO
