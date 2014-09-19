
DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_checkout_cron_bp_abandons`$$
CREATE   PROCEDURE  `sp_checkout_cron_bp_abandons`()
BEGIN
    select * from bp_abandon where estatus='E' and date <  DATE_ADD(now(),  INTERVAL '-1' HOUR) limit 60;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS  `sp_checkout_cron_abandon_frezze`$$
CREATE   PROCEDURE  `sp_checkout_cron_abandon_frezze`(in idbpa TEXT,IN proceso char(1))
BEGIN
    update bp_abandon set estatus=proceso     WHERE `id` in (idbpa); 
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS  `sp_checkout_cron_abandon_details`$$
CREATE   PROCEDURE  `sp_checkout_cron_abandon_details`(IN bpaid int)
BEGIN
    SELECT * FROM bp_abandon_details  WHERE id_abandon = bpaid;
END$$
