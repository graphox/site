SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+01:00";

CREATE TABLE IF NOT EXISTS `acl_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `acl_group` (`id`, `parent_id`, `name`) VALUES
(1, NULL, 'world'),
(2, 1, 'user'),
(3, 2, 'admin'),
(4, 1, 'oauthUser');

CREATE TABLE IF NOT EXISTS `acl_group_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `acl_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `acl_object` (`id`, `parent_id`, `name`) VALUES
(1, NULL, 'site'),
(2, 1, 'AdminObject'),
(NULL, 2, 'Pages::Overview'),
(NULL, 2, 'AclGroup::Overview'),
(NULL, 2, 'AclGroupUser::Overview'),
(NULL, 2, 'AclObject::Overview'),
(NULL, 2, 'AclPrivilege::Overview'),
(NULL, 2, 'acl'),
(NULL, 2, 'Clans::Overview'),
(NULL, 2, 'ClanMembers::Overview'),
(NULL, 2, 'ClanRanks::Overview'),
(NULL, 2, 'ClanTag::Overview'),
(NULL, 2, 'User::Overview'),
(NULL, 2, 'Profile::Overview'),
(NULL, 2, 'Images::Overview'),
(NULL, 2, 'PageComments::Overview'),
(NULL, 2, 'CommentVotes::Overview'),
(NULL, 2, 'PmDirectory::Overview'),
(NULL, 2, 'PmMessage::Overview'),
(NULL, 2, 'MenuItem::Overview'),
(NULL, 2, 'TagsPages::Overview'),
(NULL, 2, 'Tags::Overview'),
(NULL, 2, 'Forum::Overview'),
(NULL, 1, 'Forum')

;

CREATE TABLE IF NOT EXISTS `acl_privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `read` tinyint(1) NOT NULL,
  `write` tinyint(1) NOT NULL,
  `update` tinyint(1) NOT NULL,
  `delete` tinyint(1) NOT NULL,
  `order_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `acl_privilege` (`id`, `object_id`, `group_id`, `read`, `write`, `update`, `delete`, `order_by`) VALUES
(NULL, 1, 1, 1, 0, 0, 0, 5000000),
(NULL, 17, 1, 0, 0, 0, 0, 100000),
(NULL, 17, 3, 1, 1, 1, 1, 2000000),
(NULL, 1, 2, 1, 1, 0, 0, 2);

CREATE TABLE IF NOT EXISTS `activationkeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(500) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `clans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `acl_group_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `forum_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`),
  KEY `page_id` (`page_id`),
  KEY `acl_group_id` (`acl_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `clan_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `clan_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rank_id` (`rank_id`),
  KEY `user_id` (`user_id`),
  KEY `clan_id` (`clan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `clan_ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `acl_group_id` int(11) NOT NULL,
  `clan_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_group_id` (`acl_group_id`),
  KEY `clan_id` (`clan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `clan_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  `clan_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clan_id` (`clan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `comment_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dashboard_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `path` varchar(1000) DEFAULT NULL,
  `weight` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dashboard_portlet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dashboard` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `settings` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `external_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `oauth_provider_id` int(11) NOT NULL,
  `key` int(11) NOT NULL,
  `external_user_id` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_user_id` (`external_user_id`),
  KEY `user_id` (`user_id`),
  KEY `oauth_provider_id` (`oauth_provider_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `acl_object_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `main_forum` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `acl_object_id` (`acl_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `forum_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `date_added` datetime NOT NULL,
  `date_changed` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `forum_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `acl_object_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`),
  KEY `acl_object_id` (`acl_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `added_date` datetime NOT NULL,
  `owned_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owned_by` (`owned_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `url` text NOT NULL,
  `acl_object_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `acl_object_id` (`acl_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `oauth_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `logo_url` varchar(50) NOT NULL,
  `privatekey` varchar(50) DEFAULT NULL,
  `dialect` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `oauth_provider` (`id`, `name`, `logo_url`, `privatekey`, `dialect`) VALUES
(1, 'google', 'Google', '', 'OpenID');

CREATE TABLE IF NOT EXISTS `online_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `begin_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `server_id` (`server_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `uri` varchar(50) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `editor_id` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `allow_comments` tinyint(1) NOT NULL DEFAULT '1',
  `layout` varchar(50) DEFAULT NULL,
  `content` text NOT NULL,
  `change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acl_object_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `editor_id` (`editor_id`),
  KEY `acl_object_id` (`acl_object_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

CREATE TABLE IF NOT EXISTS `page_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `posted_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

CREATE TABLE IF NOT EXISTS `pm_directory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `pm_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `read` tinyint(1) NOT NULL,
  `receiver_deleted` tinyint(1) NOT NULL,
  `receiver_dir_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `sended_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `receiver_dir_id` (`receiver_dir_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `homepage` text NOT NULL,
  `avatar_img_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `avatar_img_id` (`avatar_img_id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `port` int(11) NOT NULL,
  `external` tinyint(1) NOT NULL,
  `mode` varchar(50) NOT NULL,
  `map` varchar(50) NOT NULL,
  `updated_time` datetime NOT NULL,
  `online` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `stats_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) NOT NULL,
  `mode` varchar(5) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `map_id` (`map_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `stats_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `win` tinyint(1) NOT NULL,
  `draw` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `stats_totals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `frags` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `suicides` int(11) NOT NULL,
  `misses` int(11) NOT NULL,
  `shots` int(11) NOT NULL,
  `hits_made` int(11) NOT NULL,
  `hits_get` int(11) NOT NULL,
  `tk_made` int(11) NOT NULL,
  `tk_get` int(11) NOT NULL,
  `flags_returned` int(11) NOT NULL,
  `flags_stolen` int(11) NOT NULL,
  `flags_gone` int(11) NOT NULL,
  `flags_scored` int(11) NOT NULL,
  `total_scored` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `stats_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `country` varchar(50) NOT NULL,
  `playing` tinyint(1) NOT NULL,
  `frags` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `suicides` int(11) NOT NULL,
  `misses` int(11) NOT NULL,
  `shots` int(11) NOT NULL,
  `hits_made` int(11) NOT NULL,
  `hits_get` int(11) NOT NULL,
  `tk_made` int(11) NOT NULL,
  `tk_get` int(11) NOT NULL,
  `flags_returned` int(11) NOT NULL,
  `flags_stolen` int(11) NOT NULL,
  `flags_gone` int(11) NOT NULL,
  `flags_scored` int(11) NOT NULL,
  `total_scored` int(11) NOT NULL,
  `win` tinyint(1) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `game_id` (`game_id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `tags_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_id` (`tag_id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `ingame_password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `hashing_method` varchar(50) NOT NULL,
  `web_password` varchar(50) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

ALTER TABLE `acl_group`
  ADD CONSTRAINT `acl_group_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `acl_group` (`id`);

ALTER TABLE `acl_group_user`
  ADD CONSTRAINT `acl_group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `acl_group` (`id`),
  ADD CONSTRAINT `acl_group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `acl_object`
  ADD CONSTRAINT `acl_object_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `acl_object` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE `acl_privilege`
  ADD CONSTRAINT `acl_privilege_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `acl_object` (`id`),
  ADD CONSTRAINT `acl_privilege_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `acl_group` (`id`);

ALTER TABLE `activationkeys`
  ADD CONSTRAINT `activationkeys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `clans`
  ADD CONSTRAINT `clans_ibfk_1` FOREIGN KEY (`acl_group_id`) REFERENCES `acl_group` (`id`),
  ADD CONSTRAINT `clans_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  ADD CONSTRAINT `clans_ibfk_3` FOREIGN KEY (`forum_id`) REFERENCES `forum` (`id`);

ALTER TABLE `clan_members`
  ADD CONSTRAINT `clan_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `clan_members_ibfk_2` FOREIGN KEY (`clan_id`) REFERENCES `clans` (`id`),
  ADD CONSTRAINT `clan_members_ibfk_3` FOREIGN KEY (`rank_id`) REFERENCES `clan_ranks` (`id`);

ALTER TABLE `clan_ranks`
  ADD CONSTRAINT `clan_ranks_ibfk_1` FOREIGN KEY (`acl_group_id`) REFERENCES `acl_group` (`id`),
  ADD CONSTRAINT `clan_ranks_ibfk_2` FOREIGN KEY (`clan_id`) REFERENCES `clans` (`id`);

ALTER TABLE `clan_tag`
  ADD CONSTRAINT `clan_tag_ibfk_1` FOREIGN KEY (`clan_id`) REFERENCES `clans` (`id`);

ALTER TABLE `comment_votes`
  ADD CONSTRAINT `comment_votes_ibfk_5` FOREIGN KEY (`comment_id`) REFERENCES `page_comments` (`id`),
  ADD CONSTRAINT `comment_votes_ibfk_8` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

ALTER TABLE `external_user`
  ADD CONSTRAINT `external_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `external_user_ibfk_2` FOREIGN KEY (`oauth_provider_id`) REFERENCES `oauth_provider` (`id`);

ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `forum` (`id`),
  ADD CONSTRAINT `forum_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`);

ALTER TABLE `forum_message`
  ADD CONSTRAINT `forum_message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `forum_message_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `forum_topic` (`id`);

ALTER TABLE `forum_topic`
  ADD CONSTRAINT `forum_topic_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forum` (`id`),
  ADD CONSTRAINT `forum_topic_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`);

ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `user` (`id`);

ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`owned_by`) REFERENCES `user` (`id`);

ALTER TABLE `menu_item`
  ADD CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menu_item` (`id`),
  ADD CONSTRAINT `menu_item_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`);

ALTER TABLE `names`
  ADD CONSTRAINT `names_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `online_player`
  ADD CONSTRAINT `online_player_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `online_player_ibfk_2` FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`);

ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`editor_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`),
  ADD CONSTRAINT `pages_ibfk_4` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

ALTER TABLE `page_comments`
  ADD CONSTRAINT `page_comments_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  ADD CONSTRAINT `page_comments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

ALTER TABLE `pm_directory`
  ADD CONSTRAINT `pm_directory_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `pm_directory` (`id`),
  ADD CONSTRAINT `pm_directory_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `pm_message`
  ADD CONSTRAINT `pm_message_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `pm_message_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `pm_message_ibfk_3` FOREIGN KEY (`receiver_dir_id`) REFERENCES `pm_directory` (`id`);

ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `profile_ibfk_2` FOREIGN KEY (`avatar_img_id`) REFERENCES `images` (`id`),
  ADD CONSTRAINT `profile_ibfk_3` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

ALTER TABLE `stats_games`
  ADD CONSTRAINT `stats_games_ibfk_1` FOREIGN KEY (`map_id`) REFERENCES `map` (`id`);

ALTER TABLE `stats_teams`
  ADD CONSTRAINT `stats_teams_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `stats_games` (`id`);

ALTER TABLE `stats_totals`
  ADD CONSTRAINT `stats_totals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `stats_users`
  ADD CONSTRAINT `stats_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `stats_users_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `stats_games` (`id`),
  ADD CONSTRAINT `stats_users_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `stats_teams` (`id`);

ALTER TABLE `tags_pages`
  ADD CONSTRAINT `tags_pages_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `tags_pages_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;
