-- Migration: Add dedicated reactions table
-- Date: 2025-07-01
-- Description: Create reactions table to replace usage of likes table for reactions feature

CREATE TABLE IF NOT EXISTS reactions (
    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    toUserId int(11) UNSIGNED DEFAULT 0,
    fromUserId int(11) UNSIGNED DEFAULT 0,
    itemId int(11) UNSIGNED DEFAULT 0,
    reactionType int(11) UNSIGNED NOT NULL DEFAULT 0,
    notifyId int(11) UNSIGNED DEFAULT 0,
    createAt int(11) UNSIGNED DEFAULT 0,
    removeAt int(11) UNSIGNED DEFAULT 0,
    ip_addr CHAR(32) NOT NULL DEFAULT '',
    PRIMARY KEY (id),
    KEY idx_item_reaction (itemId, fromUserId, removeAt),
    KEY idx_user_reactions (fromUserId, removeAt),
    KEY idx_target_user (toUserId, removeAt)
) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- Create indexes for better performance
CREATE INDEX idx_reactions_item_type ON reactions (itemId, reactionType, removeAt);
CREATE INDEX idx_reactions_user_item ON reactions (fromUserId, itemId, removeAt);