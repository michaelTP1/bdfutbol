			   			   

DROP DATABASE IF EXISTS bdFutbol;
CREATE DATABASE bdFutbol;
use bdFutbol;


create table ligas(
	codLiga char(5),CONSTRAINT pkLigas PRIMARY KEY(codLiga) ,
	nomLiga varchar(50)

);


create table equipos(
	codEquipo int AUTO_INCREMENT, CONSTRAINT pkequipos primary KEY (codEquipo),
	nomEquipo varchar(40),
	codLiga char(5)  DEFAULT 'PDN', CONSTRAINT fk_codigoLiga FOREIGN KEY (codLiga) REFERENCES LIGAS(codLiga),
    
    localidad varchar(60),
	internacional boolean DEFAULT false 
	

);




create table futbolistas(
	coddnionie char(9),
	nombre varchar(50),
	nacionalidad varchar(40),
	primary key (coddnionie)
);




create table contratos(
	codcontrato int AUTO_INCREMENT, constraint pkcontratos PRIMARY key (codcontrato),
	coddnionie char (9), constraint fkdniFutbolistas foreign key (coddnionie) references futbolistas(coddnionie),
	codEquipo int, CONSTRAINT fkcontratos_equipo foreign key (codEquipo) references equipos(codEquipo),
	fechaInicio date,
	fechaFin date,
	precioanual int,
	preciorecision int
	
	
);
/*
insert into ligas VALUES('LFP00', 'LIGA DE FÚTBOL PROFESIONAL');
insert into ligas values('PREM0', 'PREMIER LEAGUE');
insert into ligas values('SERIA', 'SERIE A');

insert into equipos(nomEquipo, codLiga,	localidad, internacional) VALUES('FUTBOL CLUB BARCELONA', 'LFP00', 'BARCELONA', true);
insert into equipos(nomEquipo, codLiga,	localidad, internacional) VALUES( 'REAL MADRID CLUB DE FUTBOL', 'LFP00', 'MADRID', true);

insert into futbolistas VALUES( '123456789', 'LIONEL MESSI', 'ARGENTINA');
insert into futbolistas VALUES( '123456389', 'TER STEGEN', 'ALEMANIA');
insert into futbolistas VALUES( '123412789', 'ANDRÉS INIESTA', 'ESPAÑA');
insert into futbolistas VALUES( '122412789', 'ASD', 'ESPAÑA');

insert into contratos(coddnionie,codEquipo,fechaInicio,fechaFin,precioanual,preciorecision) VALUES( '123456789', 1, '2018-01-01', '2021-01-01', 20000000, 5000000);
insert into contratos(coddnionie,codEquipo,fechaInicio,fechaFin,precioanual,preciorecision) VALUES( '123456789', 1, '2014-01-01', '2018-01-01', 2000000, 500000);
insert into contratos(coddnionie,codEquipo,fechaInicio,fechaFin,precioanual,preciorecision) VALUES( '123456389', 1, '2019-01-01', '2021-01-01', 320000, 50008);
insert into contratos(coddnionie,codEquipo,fechaInicio,fechaFin,precioanual,preciorecision) VALUES( '123412789', 1, '2004-01-01', '2016-01-01', 5130000, 721415);
insert into contratos(coddnionie,codEquipo,fechaInicio,fechaFin,precioanual,preciorecision) VALUES( '122412789', 2, '2004-01-01', '2016-01-01', 5130000, 721415);

*/

/*Crear un procedimiento almacenado que liste todos los contratos de cierto futbolista pasando por
parámetro de entrada el dni o nie del futbolista, ordenados por fecha de inicio.
Los datos a visualizar serán: Código de contrato, nombre de equipo, nombre de liga, fecha de inicio,
fecha de fin, precio anual y precio de recisión del contrato.
*/
delimiter //
create procedure contratos_futbolista(IN documento char(9))
	
	
			select codcontrato, equipos.nomEquipo, ligas.nomLiga, fechaInicio, fechaFin, precioanual, preciorecision from contratos
			join equipos on equipos.codEquipo=contratos.codEquipo
			join ligas on ligas.codLiga=equipos.codLiga
			where coddnionie=documento
			order by (fechaInicio)
		
//
/*
call contratos_futbolista('123456789')
*/
//


			   			   

/*
Crear un procedimiento almacenado que inserte un equipo, de modo que se le pase como parámetros
todos los datos.
Comprobar que el código de liga pasado exista en la tabla ligas. En caso de que no exista la liga que
no se inserte.
Devolver en un parámetro de salida: 0 si la liga no existe y 1 si la liga existe.
Devolver en otro parámetro de salida: 0 si el equipo no se insertó y 1 si la inserción fue
correcta.
*/


create procedure insertar_equipo(in nomEquipo varchar(40),in codLiga char(5), in localidad varchar(60), in internacional boolean, out liga_existe bit, out error_insercion bit) 
BEGIN
			set error_insercion=0;
			set liga_existe=0;
			if exists (select nomLiga from ligas where ligas.codLiga=codLiga) THEN
					set @liga_existe=1;
					insert into equipos values ( nomEquipo, codLiga, localidad, internacional);
					set error_insercion=1;
				end IF;
END		

//

/*
CALL insertar_equipo('BETIS', 'LFP40', 'SEVILLA', false, @liga_existe, @error_insercion)
//
select @liga_existe, @error_insercion
//
*/


/*
 Crear un procedimiento almacenado que indicándole un equipo, precio anual y un precio recisión,
devuelva dos parámetros. En un parámetro de salida la cantidad de futbolistas en activo (con contrato
vigente) que hay en dicho equipo. En otro parámetro de salida la cantidad de futbolistas en activo de
dicho equipo con precio anual y de recisión menor de los indicados.
*/
drop PROCEDURE if EXISTS consulta_contratos //
create procedure consulta_contratos(in nomEquipo varchar(40), in precio_anual int, in precio_recision int, out numero_activos int , out numero_consultado int )
	
	begin
		set numero_activos=( select COUNT(codcontrato) from contratos 
									join equipos on equipos.codEquipo=contratos.codEquipo
									where equipos.nomEquipo=nomEquipo and now()<contratos.fechaFin
								
							);
		set numero_consultado=( select COUNT(codcontrato) from contratos 
									join equipos on equipos.codEquipo=contratos.codEquipo
									where equipos.nomEquipo=nomEquipo and now()<contratos.fechaFin and contratos.precioanual<=precio_anual and contratos.preciorecision<=precio_recision
								
							);



	end
//
/*
CALL consulta_contratos('FUTBOL CLUB BARCELONA',5130000 ,500000, @numero_activos , @numero_consultado )
//
select @numero_activos, @numero_consultado
//


*/
/*
 Crear una función que dándole un dni o nie de un futbolista nos devuelva en número de meses total
que ha estado en equipos. 
*/
//
create function meses_activo (documento char(9))
	returns int
	begin
		declare ans int;
		set ans=(select sum(TIMESTAMPDIFF(month, fechaInicio, fechaFin ))from contratos
					where coddnionie=documento) ;
		return ans;
	end
//
/*
select meses_activo('123456789')//
select meses_activo('123412789')//
*/
/*
Hacer una función que devuelva los nombres de los equipos que pertenecen a una determinada liga que
le pasamos el nombre por parámetro de entrada, si la liga no existe deberá aparecer liga no existe.
*/
//
create PROCEDURE equipos_liga (in nomLiga varchar(50))
	
	begin
		if exists (select * from ligas where ligas.nomLiga=nomLiga) THEN
				
					select equipos.nomEquipo from ligas
					inner join equipos on equipos.codLiga=ligas.codLiga
						where ligas.nomLiga=nomLiga;
				
				else
					SELECT 'liga no existente';
	   end if;
	end;
//
/*
CALL equipos_liga('LIGA DE FÚTBOL PROFESIONAL')//
CALL equipos_liga('LIGA DE FÚTBOL PsfsfsfsfROFESIONAL')//

*/

/*
Hacer una función en la que visualicemos los datos de los jugadores extranjeros que pertenezcan a 
un equipo cuyo nombre pasamos por parámetro.
*/
//
DROP PROCEDURE if EXISTS equipo_extranjeros//
create PROCEDURE equipo_extranjeros (in nomEquipo VARCHAR(50))

	
		select futbolistas.nombre from contratos
				join equipos on equipos.codEquipo=contratos.codEquipo
				join futbolistas on futbolistas.coddnionie=contratos.coddnionie
			

				where equipos.nomEquipo=nomEquipo and futbolistas.nacionalidad not like'ESPAÑA';
//

call equipo_extranjeros ('FUTBOL CLUB BARCELONA')//

/*
Hacer una función que nos devuelva por cada futbolista su nombre y en cuantos equipos a tenido
contrato entre dos fechas determinadas.
*/
//
create PROCEDURE jugadores_contratos ()
	
		 
		 select futbolistas.nombre, count(contratos.codContrato) as 'nContratos' from futbolistas
											inner join contratos on contratos.coddnionie=futbolistas.coddnionie
											group by futbolistas.nombre;
	
				
		
		
//
/*
call jugadores_contratos()//
*/
/*
Hacer una función escalar que nos devuelva el precioanual mas alto que se le ha pagado a un 
futbolista ,  con el nombre de equipo y año pasado por parámetro.
*/
//
create function top_precio_año (nomEquipo varchar(50),  inyear int)	
	returns int 
	begin
		declare ans int;

		set ans = (select contratos.precioanual from equipos
						inner join contratos on contratos.codEquipo=equipos.codEquipo
						where year(contratos.fechaInicio)<=inyear
							and year( contratos.fechaFin)>=inyear
							AND equipos.nomEquipo=nomEquipo
							order by contratos.precioanual desc
                  			limit 1);
		IF ans is null THEN
				set ans=-1;
               end if; 
		return ans;
	
	end
//
/*
select top_precio_año('BETIS', 2019)//
select top_precio_año('FUTBOL CLUB BARCELONA', 2019)//
select top_precio_año('FUTBOL CLUB BARCELONA', 2009)//
*/

/*
Hacer un Trigger que en la tabla contratos al insertar o modificar el precio de recisión no permita
que sea menor que el precio anual.
*/
drop TRIGGER if EXISTS tr_I_contratos_precio_recision
//
create trigger tr_IoU_contratos_precio_recision before insert on contratos for each ROW
BEGIN
	if (NEW.preciorecision<NEW.precioanual) then
			signal sqlstate '45000' set message_text='El precio de recisión no puede ser menor que el precio anual'; 
     end if;       
END
//
drop TRIGGER if EXISTS tr_U_contratos_precio_recision//
create trigger tr_U_contratos_precio_recision before UPDATE on contratos for each ROW
BEGIN
	if (NEW.preciorecision<NEW.precioanual) then
			signal sqlstate '45000' set message_text='El precio de recisión no puede ser menor que el precio anual'; 
     end if;       
END
//

/*
 Hacer un Trigger que si en la tabla contratos que al insertar o modificar ponemos la fecha inicio
posterior a la fecha fin que las intercambie.
*/
drop TRIGGER if EXISTS tr_I_contratos_fechas
//
create trigger tr_I_contratos_fechas before insert on contratos for EACH ROW
BEGIN
	    declare aux date;
   		if (NEW.fechaFin<NEW.fechaInicio) THEN
           	set aux=NEW.fechaInicio,
 	   		NEW.fechaInicio=NEW.fechaFin,
 	   		NEW.fechaFin=aux;   
        end if;    
		
END        
	
//
drop TRIGGER if EXISTS tr_U_contratos_fechas
//
create trigger tr_U_contratos_fechas before update on contratos for EACH ROW
BEGIN
	    declare aux date;
   		if (NEW.fechaFin<NEW.fechaInicio) THEN
           	set aux=NEW.fechaInicio,
 	   		NEW.fechaInicio=NEW.fechaFin,
 	   		NEW.fechaFin=aux;   
        end if;    
		
END        
	
//
/*
insert into contratos(coddnionie,codEquipo,fechaInicio,fechaFin,precioanual,preciorecision) VALUES( '122412789', 2, '01/01/2018', '01/01/2017', 10000, 100000)
//

*/

/*
 Hacer un Trigger que no permita eliminar ninguna liga.
*/

create trigger tr_D_ligas BEFORE DELETE ON ligas for EACH row
BEGIN	
	signal sqlstate '45000' set message_text = 'no se pueden borrar ligas';
END	
//

/*
delete from ligas where nomLiga like 'LIGA DE FÚTBOL PROFESIONAL'
//
*/