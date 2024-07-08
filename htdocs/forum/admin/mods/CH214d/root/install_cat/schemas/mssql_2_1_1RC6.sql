
BEGIN TRANSACTION
GO

ALTER TABLE [phpbb_forums] WITH NOCHECK ADD
	CONSTRAINT [DF_phpbb_forums_forum_cat_id] DEFAULT (0) FOR [forum_cat_id],
	CONSTRAINT [DF_phpbb_forums_forum_status] DEFAULT (0) FOR [forum_status],
	CONSTRAINT [DF_phpbb_forums_forum_order] DEFAULT (0) FOR [forum_order]
GO

COMMIT
GO
