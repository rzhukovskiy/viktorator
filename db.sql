CREATE TABLE `viktorator`.`admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `social_id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `token` VARCHAR(255) NULL,
  `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_bot` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `social_id_UNIQUE` (`social_id` ASC));

CREATE TABLE `viktorator`.`user` (
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

CREATE TABLE `viktorator`.`top` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `social_id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `scores` INT NOT NULL DEFAULT 0,
  `date` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `top_social_id` (`social_id` ASC),
  INDEX `top_date` (`date` ASC));

CREATE TABLE `viktorator`.`activity` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NOT NULL,
  `price` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`));

CREATE TABLE `viktorator`.`action` (
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

CREATE TABLE `viktorator`.`error` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(50) NULL,
  `content` TEXT NULL,
  `response` VARCHAR(50) NULL,
  `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `viktorator`.`config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `value` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC));

INSERT INTO `viktorator`.`config` (`name`, `value`) VALUES ('app_id', '6253298');
INSERT INTO `viktorator`.`config` (`name`, `value`) VALUES ('app_secret', 'eH3T0i8mYSmcIoHqGppB');
INSERT INTO `viktorator`.`config` (`name`, `value`) VALUES ('redirect_uri', 'http://mediastog.ru/site/auth');