CREATE TABLE `config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `app_id` VARCHAR(255) NULL,
  `app_secret` VARCHAR(255) NULL,
  `redirect_uri` VARCHAR(255) NULL,
  `standalone_id` VARCHAR(255) NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `social_id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `token` VARCHAR(255) NULL,
  `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_bot` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `social_id_UNIQUE` (`social_id` ASC));

CREATE TABLE `group` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `slug` VARCHAR(255) NULL,
  `picture` VARCHAR(255) NULL,
  `secret` VARCHAR(255) NULL,
  `confirm` VARCHAR(255) NULL,
  `token` VARCHAR(255) NULL,
  `standalone_token` VARCHAR(255) NULL,
  `topic_id` INT NULL,
  `post_id` INT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `admin_group_link` (
  `admin_id` INT NOT NULL,
  `group_id` INT NOT NULL);

CREATE TABLE `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `social_id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `scores` INT NOT NULL DEFAULT 0,
  `is_member` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `is_repost` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `social_id` (`social_id` ASC));

CREATE TABLE `post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `social_id` INT NOT NULL,
  `likes` INT NOT NULL DEFAULT 0,
  `comments` INT NOT NULL DEFAULT 0,
  `reposts` INT NOT NULL DEFAULT 0,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `social_id` (`social_id` ASC));

CREATE TABLE `comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `group_id` INT NOT NULL,
  `social_id` INT NOT NULL,
  `likes` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `social_id` (`social_id` ASC));

CREATE TABLE `top` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `social_id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `scores` INT NOT NULL DEFAULT 0,
  `date` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `top_social_id` (`social_id` ASC),
  INDEX `top_date` (`date` ASC));

CREATE TABLE `activity` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NOT NULL,
  `price` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`));

CREATE TABLE `action` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `social_id` INT NOT NULL,
  `parent_social_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `user_social_id` INT NOT NULL,
  `activity_id` INT NOT NULL,
  `content` VARCHAR(255) NULL,
  `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `scores` INT NOT NULL DEFAULT 0,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `error` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(50) NULL,
  `content` TEXT NULL,
  `response` VARCHAR(50) NULL,
  `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `widget` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `user_social_id` INT DEFAULT NULL,
  `title` VARCHAR(100) NULL,
  `text` VARCHAR(100) NULL,
  `main_text` VARCHAR(300) NULL,
  `button_text` VARCHAR(50) NULL,
  `button_url` VARCHAR(100) NULL,
  PRIMARY KEY (`id`));

INSERT INTO `config` VALUES ('6253298', 'eH3T0i8mYSmcIoHqGppB', 'http://mediastog.ru/site/auth', '6265782');