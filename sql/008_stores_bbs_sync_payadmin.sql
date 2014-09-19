
DROP PROCEDURE IF EXISTS `sp_service_delete_exchangerate`;
CREATE PROCEDURE `sp_service_delete_exchangerate`(in idpartner int)
BEGIN
 delete from partners where idpartner=in_idpartner;
 select in_idpartner as idpartner; 
END;

#
# Procedure "sp_service_get_id_excahngerate"
#

DROP PROCEDURE IF EXISTS `sp_service_get_id_excahngerate`;
CREATE PROCEDURE `sp_service_get_id_excahngerate`(in in_id int)
BEGIN
     select * from exchange_rate where id=in_id;
END;

#
# Procedure "sp_service_get_idpartner_currency"
#

DROP PROCEDURE IF EXISTS `sp_service_get_idpartner_currency`;
CREATE PROCEDURE `sp_service_get_idpartner_currency`(in in_idpartner int,in in_currency_base_id int,in in_currency_exchange_id int)
BEGIN
     select * from partner_currency where idpartner=in_idpartner and currency_base_id=in_currency_base_id and currency_exchange_id=in_currency_exchange_id;
END;

#
# Procedure "sp_service_insert_partnercurrency"
#

DROP PROCEDURE IF EXISTS `sp_service_insert_partnercurrency`;
CREATE PROCEDURE `sp_service_insert_partnercurrency`(
in in_bongo_uplift decimal(10,2),
in in_currency_base_id int,
in in_currency_exchange_id int,
in in_idpartner int
)
BEGIN
  insert into partner_currency(bongo_uplift,date_update,currency_base_id,currency_exchange_id,idpartner)
  values(in_bongo_uplift,now(),in_currency_base_id,in_currency_exchange_id,in_idpartner) ; 
  select LAST_INSERT_ID() as ou_lastinsertid;
END;

#
# Procedure "sp_service_update_country"
#

DROP PROCEDURE IF EXISTS `sp_service_update_country`;
CREATE PROCEDURE `sp_service_update_country`(
in in_idcountry int,
in in_dhl char(1),
in in_dhl_ddp char(1),
in in_fedex char(1),
in in_fedex_ddp char(1),
in in_ups char(1),
in in_ups_ddp char(1),
in in_rrd char(1),
in in_rrd_ddp char(1),
in in_usps char(1),
in in_usps_ddp char(1),
in in_shipping_available char(1),
in in_express int,
in in_deferred int,
in in_postal int
)
BEGIN
  update country set 
    idcountry=in_idcountry,
    dhl = in_dhl,
    dhl_ddp=in_dhl_ddp,
    fedex=in_fedex ,
    fedex_ddp=in_fedex_ddp ,
    ups=in_ups ,
    ups_ddp=in_ups_ddp ,
    rrd=in_rrd ,
    rrd_ddp=in_rrd_ddp,
    usps=in_usps ,
    usps_ddp=in_usps_ddp ,
    shipping_available=in_shipping_available ,
    express=in_express ,
    deferred=in_deferred ,
    postal=in_postal 
  where 
    idcountry=in_idcountry;
    select in_idcountry as idcountry;
END;

#
# Procedure "sp_service_update_currencycheckout_country_id"
#

DROP PROCEDURE IF EXISTS `sp_service_update_currencycheckout_country_id`;
CREATE PROCEDURE `sp_service_update_currencycheckout_country_id`(
in in_idcountry int,
in in_currencyCheckout text)
BEGIN
  update country set 
  currencyCheckout=in_currencyCheckout
  where 
  idcountry=in_idcountry;
  select in_idcountry as idcountry;
END;

#
# Procedure "sp_service_update_decimales_currency_code"
#

DROP PROCEDURE IF EXISTS `sp_service_update_decimales_currency_code`;
CREATE PROCEDURE `sp_service_update_decimales_currency_code`(
in in_code text,
in in_decimales int)
BEGIN
  update currency set 
  decimales=in_decimales
  where 
  `code`=in_code;
  select in_code as id;
END;

#
# Procedure "sp_service_update_partner"
#

DROP PROCEDURE IF EXISTS `sp_service_update_partner`;
CREATE PROCEDURE `sp_service_update_partner`(
in in_adjustment_amount float,
in in_adjustment_percentage float,
in in_adjustment_dhl_cost float,
in in_checkout_cc char(1),
in in_shopping_type char(3),
in in_domestic_transit int,
in in_location_zone_id int,
in in_idpartner int,
in in_currency_uplift decimal(10,2) 
)
BEGIN
  update partners set 
  adjustment_amount =in_adjustment_amount,
  adjustment_percentage=in_adjustment_percentage,
  adjustment_dhl_cost=in_adjustment_dhl_cost,
  checkout_cc=in_checkout_cc,
  shopping_type=in_shopping_type,
  domestic_transit=in_domestic_transit,
  location_zone_id=in_location_zone_id,
  currency_uplift=in_currency_uplift
  where idpartner=in_idpartner;
  select in_idpartner as idpartner;
END;

#
# Procedure "sp_service_update_partnercurrency"
#

DROP PROCEDURE IF EXISTS `sp_service_update_partnercurrency`;
CREATE PROCEDURE `sp_service_update_partnercurrency`(
in in_bongo_uplift decimal(10,2),
in in_currency_base_id int,
in in_currency_exchange_id int,
in in_idpartner int
)
BEGIN
  update partner_currency set 
  bongo_uplift =in_bongo_uplift,
  date_update=now() 
  where 
  currency_base_id=in_currency_base_id and
  currency_exchange_id=in_currency_exchange_id and 
  idpartner=in_idpartner;
  select in_idpartner as idpartner;
END;

#
# Procedure "sp_service_update_uplist_exchangerate"
#

DROP PROCEDURE IF EXISTS `sp_service_update_uplist_exchangerate`;
CREATE PROCEDURE `sp_service_update_uplist_exchangerate`(
in in_id int,
in in_bongo_uplift float)
BEGIN
  update exchange_rate set 
  bongo_uplift=in_bongo_uplift
  where 
  id=in_id;
  select in_id as id;
END;
