drop procedure sp_checkout_insert_mail_process;
CREATE PROCEDURE `sp_checkout_insert_mail_process`(
in in_tipo text,
in in_parametros text,
in in_estatus text,
in in_fecha  text,
in in_lan text,
in in_idbongop int)
BEGIN
 insert into mail_process(
     tipo,
    parametros ,
    estatus ,
    fecha ,
    lan,
    idbongop)
 values(  
  in_tipo,
  in_parametros ,
  in_estatus ,
  in_fecha ,
  in_lan,
  in_idbongop); 
 select LAST_INSERT_ID() as ou_lastinsertid;
END;