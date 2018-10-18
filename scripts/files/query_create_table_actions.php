<?php
return "
  CREATE TABLE `actions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `action_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NULL,
    `date_start` INT UNSIGNED NOT NULL DEFAULT 0,
    `date_end` VARCHAR(50) NULL,
    `status` VARCHAR(10) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `action_id_UNIQUE` (`action_id` ASC))
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8
  COMMENT = 'Actions by csv file'
";