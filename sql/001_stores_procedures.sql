
CREATE  PROCEDURE `sp_checkout_get_country_shippingavailable`(IN inshippingavailable char(1))
BEGIN
     SELECT idcountry, `code`, `name`, `phonecode`, `currency`, `transit_time` FROM country  WHERE shipping_available = inshippingavailable ORDER BY name ASC;     
END;


CREATE PROCEDURE `sp_checkout_get_country_code`(IN incountrycode char(2))
BEGIN
     SELECT idcountry, currency,currencyCheckout FROM country  WHERE `code` = incountrycode ORDER BY name ASC;     
END;

CREATE PROCEDURE `sp_checkout_get_partner_idpartner`(IN inidpartner int)
BEGIN
     SELECT *  FROM partners  WHERE `idpartner` = inidpartner;     
END;

CREATE PROCEDURE `sp_checkout_get_exchangerate_currencycode`(IN inbcurrencycode char(3),IN inccurrencycode char(3))
BEGIN
  SELECT c.code, b.code as currency_code, a.id, a.ask, a.bid, a.bongo_uplift, a.bid as bongo_rate,b.name as currency_name, b.simbolo as currency_simbolo 
  FROM exchange_rate a 
  INNER JOIN currency b ON (b.id = a.currency_exchange_id) 
  INNER JOIN currency c ON (c.id = a.currency_base_id) 
  WHERE c.code = inccurrencycode AND b.code=inbcurrencycode;
END;

CREATE PROCEDURE `sp_checkout_get_exchangerate_currencycode_avaliable`(IN inbcurrencycode char(3),IN inccurrencycode char(3),IN inavaliable char(1))
BEGIN
  SELECT c.code, b.code as currency_code, a.id, a.ask, a.bid, a.bongo_uplift, a.bid as bongo_rate,b.name as currency_name, b.simbolo as currency_simbolo 
  FROM exchange_rate a 
  INNER JOIN currency b ON (b.id = a.currency_exchange_id) 
  INNER JOIN currency c ON (c.id = a.currency_base_id) 
  WHERE c.code = inccurrencycode AND b.code=inbcurrencycode
  and b.available=inavaliable;
END;


drop PROCEDURE sp_checkout_get_exchangerate_cubase_cuexchange;
CREATE PROCEDURE `sp_checkout_get_exchangerate_cubase_cuexchange`(IN in_currencycbase char(3),IN in_ccurrency_exchange char(3))
BEGIN
  SELECT c.code, b.code as currency_code, a.id, a.ask, a.bid, a.bongo_uplift, a.bid as bongo_rate,b.name as currency_name, b.simbolo as currency_simbolo 
  FROM exchange_rate a 
  INNER JOIN currency b ON (b.id = a.currency_exchange_id) 
  INNER JOIN currency c ON (c.id = a.currency_base_id) 
  WHERE c.code = in_currencycbase AND b.code=in_ccurrency_exchange;
END;

drop PROCEDURE sp_checkout_get_exchangerate_currencycode_avaliable;
CREATE PROCEDURE `sp_checkout_get_exchangerate_currencycode_avaliable`(IN in_currencycbase char(3),IN in_ccurrency_exchange char(3),IN inavaliable char(1))
BEGIN
  SELECT c.code, b.code as currency_code, a.id, a.ask, a.bid, a.bongo_uplift, a.bid as bongo_rate,b.name as currency_name, b.simbolo as currency_simbolo 
  FROM exchange_rate a 
  INNER JOIN currency b ON (b.id = a.currency_exchange_id) 
  INNER JOIN currency c ON (c.id = a.currency_base_id) 
  WHERE c.code = in_currencycbase AND b.code=in_ccurrency_exchange
  and b.available=inavaliable;
END;

drop PROCEDURE sp_checkout_get_partnercurrency_cubase_cuexchange_idpartner;
CREATE PROCEDURE `sp_checkout_get_partnercurrency_cubase_cuexchange_idpartner`(IN in_currencycbase char(3),IN in_ccurrency_exchange char(3),IN in_idpartner int)
BEGIN
  SELECT a.bongo_uplift  FROM partner_currency a INNER JOIN currency b ON (b.id = a.currency_exchange_id)
  INNER JOIN currency c ON (c.id = a.currency_base_id)
  WHERE a.idpartner=in_idpartner and  c.code = in_currencycbase AND b.code = in_ccurrency_exchange;
END;


CREATE  PROCEDURE `sp_checkout_get_country_idcountry`(IN inidcountry int)
BEGIN
     SELECT * FROM country  WHERE `idcountry` = inidcountry;     
END;