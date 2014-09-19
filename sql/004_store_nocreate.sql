DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_transactions`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_transactions`()
BEGIN
    SELECT id, idorder_bongoP,idorder_bongoP_origin FROM bp_order_transaction  WHERE estatus = 0;
END$$
DELIMITER ;



DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_transactions_frezze`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_transactions_frezze`(in idbp TEXT,IN proceso char(1))
BEGIN
    update bp_order_transaction set estatus=proceso     WHERE `id` in (idbp); 
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_get_bp_order`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_get_bp_order`(in idbp int)
BEGIN
    select * from bp_order where idorder_bongoP=idbp;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_bporder_details`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_bporder_details`(IN bpid int)
BEGIN
    SELECT * FROM bp_order_detail  WHERE idorder_bongoP = bpid;
END$$
 
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_bp_ship`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_bp_ship`(in idbp int)
BEGIN
    select * from bp_order_ship where idorder_bongoP = idbp;
END$$
DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_bptrack`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_bptrack`(in idbp int)
BEGIN
    select * from bp_track where idorder_bongoP = idbp;
END$$
 
DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_bpmail`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_bpmail`(in idbp int)
BEGIN
    select * from  mail_process  where idbongop=idbp;
END$$
 
DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_bphttpbeta`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_bphttpbeta`(in idbp int)
BEGIN
    select * from http_beta_notification_cron where idorder_bongoP=idbp;
END$$
 
DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_bphttp`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_bphttp`(in idbp int)
BEGIN
    select * from http_notification_cron where idorder_bongoP=idbp;
END$$
 
DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_cron_bphttppre`$$
CREATE   PROCEDURE `bongo`.`sp_checkout_cron_bphttppre`(in idbp int)
BEGIN
    select * from http_beta_notification_pre where idorder_bongoP=idbp;
END$$
 
DELIMITER ;