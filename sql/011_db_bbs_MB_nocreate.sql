DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_checkout_cron_mbproducts`$$
CREATE   PROCEDURE  `sp_checkout_cron_mbproducts`()
BEGIN
    select * from MBproducts where status_bbs='E';
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS  `sp_checkout_cron_mbproducts_frezze`$$
CREATE   PROCEDURE  `sp_checkout_cron_mbproducts_frezze`(in idbpa TEXT,IN proceso char(1))
BEGIN
    update MBproducts set status_bbs=proceso WHERE `id` in (idbpa); 
END$$
DELIMITER ;