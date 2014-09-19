
DELIMITER $$

DROP PROCEDURE IF EXISTS `bongo`.`sp_checkout_update_bp_order_any_bitcoin`$$

CREATE  PROCEDURE `sp_checkout_update_bp_order_any_bitcoin1`(
      in in_estatus text,
      in in_cart_type text,
      in in_payment_gateway text,
      in in_date_process text,
      in in_paypal_id text,
      in in_coint_post text,

      in in_idorder_bongoP int
)
BEGIN
      update bp_order set      
      estatus = in_estatus,
      cart_type = in_cart_type,
      payment_gateway = in_payment_gateway,
      date_process = in_date_process,
      paypal_id = in_paypal_id  ,
      coint_post = in_coint_post  
      where idorder_bongoP = in_idorder_bongoP;
      select 1;
END$$

DELIMITER ;