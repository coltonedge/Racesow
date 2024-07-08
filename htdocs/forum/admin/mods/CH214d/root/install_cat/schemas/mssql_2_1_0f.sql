
BEGIN TRANSACTION
GO

ALTER TABLE [phpbb_icons] ADD [icon_auth] [varchar] (255)
GO

UPDATE phpbb_icons SET icon_auth = ''
GO

UPDATE phpbb_icons SET icon_auth = 'auth_post' WHERE icon_acl = 1
GO

UPDATE phpbb_icons SET icon_auth = 'auth_mod' WHERE icon_acl = 3
GO

UPDATE phpbb_icons SET icon_auth = 'auth_manage' WHERE icon_acl = 5
GO

ALTER TABLE [phpbb_icons] WITH NOCHECK ADD CONSTRAINT [DF_phpbb_icons_icon_auth] NOT NULL DEFAULT ('') FOR [icon_auth]
GO

ALTER TABLE [phpbb_icons] DROP [icon_acl]
GO

ALTER TABLE [phpbb_topics] ADD [topic_sub_type] int4
GO

UPDATE phpbb_topics SET topic_sub_type = 0
GO

ALTER TABLE [phpbb_topics] WITH NOCHECK ADD CONSTRAINT [DF_phpbb_topics_topic_sub_type] NOT NULL DEFAULT (0) FOR [topic_sub_type]
GO

// topic title attribute table
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

COMMIT
GO
