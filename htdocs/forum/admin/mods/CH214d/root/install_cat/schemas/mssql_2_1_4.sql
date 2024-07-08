
BEGIN TRANSACTION
GO

ALTER TABLE [phpbb_forums] ALTER COLUMN [forum_style] [int]
GO

CREATE INDEX [IX_phpbb_auths_CH_3] ON [phpbb_auths]([obj_type], [auth_name]) ON [PRIMARY]
GO

COMMIT
GO
