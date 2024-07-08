
BEGIN TRANSACTION
GO

ALTER TABLE [phpbb_forums] ADD [forum_subs_hidden] [smallint]
GO

UPDATE [phpbb_forums] SET forum_subs_hidden = 0
GO

ALTER TABLE [phpbb_forums] WITH NOCHECK ADD CONSTRAINT [DF_phpbb_forums_forum_subs_hidden] NOT NULL DEFAULT (0) FOR [forum_subs_hidden]
GO

ALTER TABLE [phpbb_users] ADD [user_dst] int2
GO

UPDATE phpbb_users SET user_dst = 0
GO

ALTER TABLE [phpbb_users] WITH NOCHECK ADD CONSTRAINT [DF_phpbb_users_user_dst] NOT NULL DEFAULT (0) FOR [user_dst]
GO

COMMIT
GO
