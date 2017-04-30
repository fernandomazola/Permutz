-- GRAU 2
-- apaga tabela se exisitr
DROP TABLE IF EXISTS t_pmt_comb_2;
-- cria tabela a partir da consulta
CREATE TABLE t_pmt_comb_2
SELECT SQL_CACHE
	o1.id_opcao AS id_opcao_1, o2.id_opcao AS id_opcao_2
FROM t_pmt_usuarios AS u1 
INNER JOIN t_pmt_opcoes_permuta AS o1 ON (u1.id_usuario = o1.id_usuario)
LEFT JOIN t_pmt_usuarios AS u2 ON (o1.id_local_desejado = u2.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o2 ON (u2.id_usuario = o2.id_usuario)
WHERE 
	u1.id_local_atual=o2.id_local_desejado AND u1.id_usuario NOT IN (u2.id_usuario) AND u1.id_usuario<u2.id_usuario
ORDER BY
	o1.id_opcao ASC, o2.id_opcao ASC;
-- adiciona chave primária
ALTER TABLE t_pmt_comb_2 ADD id_comb_2 INT PRIMARY KEY NOT NULL AUTO_INCREMENT FIRST;





-- GRAU 3
-- apaga tabela se exisitr
DROP TABLE IF EXISTS t_pmt_comb_3;
-- cria tabela a partir da consulta
CREATE TABLE t_pmt_comb_3
SELECT SQL_CACHE
	o1.id_opcao AS id_opcao_1, o2.id_opcao AS id_opcao_2, o3.id_opcao AS id_opcao_3,
	((o1.id_opcao * o2.id_opcao * o3.id_opcao) + 1) AS vl_check
FROM t_pmt_usuarios AS u1 
INNER JOIN t_pmt_opcoes_permuta AS o1 ON (u1.id_usuario = o1.id_usuario)
LEFT JOIN t_pmt_usuarios AS u2 ON (o1.id_local_desejado = u2.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o2 ON (u2.id_usuario = o2.id_usuario)
LEFT JOIN t_pmt_usuarios AS u3 ON (o2.id_local_desejado = u3.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o3 ON (u3.id_usuario = o3.id_usuario)
WHERE 
	u1.id_local_atual=o3.id_local_desejado AND u1.id_usuario NOT IN (u2.id_usuario, u3.id_usuario)
ORDER BY
	vl_check ASC, o1.id_opcao ASC;
-- adiciona chave primária
ALTER TABLE t_pmt_comb_3 ADD id_comb_3 INT PRIMARY KEY NOT NULL AUTO_INCREMENT FIRST;
-- deleta combinacoes grau 2
DELETE FROM t_pmt_comb_3 
WHERE 
	((id_opcao_1, id_opcao_2) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2)) 
	OR 
	((id_opcao_2, id_opcao_3) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR 
	((id_opcao_3, id_opcao_1) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2));






-- GRAU 4
-- apaga tabela se exisitr
DROP TABLE IF EXISTS t_pmt_comb_4;
-- cria tabela a partir da consulta
CREATE TABLE t_pmt_comb_4
SELECT SQL_CACHE
	o1.id_opcao AS id_opcao_1, o2.id_opcao AS id_opcao_2, o3.id_opcao AS id_opcao_3, o4.id_opcao AS id_opcao_4, 
	((o1.id_opcao * o2.id_opcao * o3.id_opcao * o4.id_opcao) + 1) AS vl_check
FROM t_pmt_usuarios AS u1 
INNER JOIN t_pmt_opcoes_permuta AS o1 ON (u1.id_usuario = o1.id_usuario)
LEFT JOIN t_pmt_usuarios AS u2 ON (o1.id_local_desejado = u2.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o2 ON (u2.id_usuario = o2.id_usuario)
LEFT JOIN t_pmt_usuarios AS u3 ON (o2.id_local_desejado = u3.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o3 ON (u3.id_usuario = o3.id_usuario)
LEFT JOIN t_pmt_usuarios AS u4 ON (o3.id_local_desejado = u4.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o4 ON (u4.id_usuario = o4.id_usuario)
WHERE 
	u1.id_local_atual=o4.id_local_desejado AND u1.id_usuario NOT IN (u2.id_usuario, u3.id_usuario, u4.id_usuario)
ORDER BY
	vl_check ASC, o1.id_opcao ASC;
-- adiciona chave primária
ALTER TABLE t_pmt_comb_4 ADD id_comb_4 INT PRIMARY KEY NOT NULL AUTO_INCREMENT FIRST;
-- deleta combinacoes grau 3
DELETE FROM t_pmt_comb_4 
WHERE 
	((id_opcao_1, id_opcao_2) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2)) 
	OR 
	((id_opcao_2, id_opcao_3) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR 
	((id_opcao_3, id_opcao_4) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR 
	((id_opcao_4, id_opcao_1) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR
	((id_opcao_1, id_opcao_2, id_opcao_3) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3)) 
	OR 
	((id_opcao_2, id_opcao_3, id_opcao_4) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3))
	OR 
	((id_opcao_3, id_opcao_4, id_opcao_1) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3))
	OR 
	((id_opcao_4, id_opcao_1, id_opcao_2) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3));






-- GRAU 5
-- apaga tabela se exisitr
DROP TABLE IF EXISTS t_pmt_comb_5;
-- cria tabela a partir da consulta
CREATE TABLE t_pmt_comb_5
SELECT SQL_CACHE
	o1.id_opcao AS id_opcao_1, o2.id_opcao AS id_opcao_2, 
	o3.id_opcao AS id_opcao_3, o4.id_opcao AS id_opcao_4, o5.id_opcao AS id_opcao_5, 
	((o1.id_opcao * o2.id_opcao * o3.id_opcao * o4.id_opcao * o5.id_opcao) + 1) AS vl_check
FROM t_pmt_usuarios AS u1 
INNER JOIN t_pmt_opcoes_permuta AS o1 ON (u1.id_usuario = o1.id_usuario)
LEFT JOIN t_pmt_usuarios AS u2 ON (o1.id_local_desejado = u2.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o2 ON (u2.id_usuario = o2.id_usuario)
LEFT JOIN t_pmt_usuarios AS u3 ON (o2.id_local_desejado = u3.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o3 ON (u3.id_usuario = o3.id_usuario)
LEFT JOIN t_pmt_usuarios AS u4 ON (o3.id_local_desejado = u4.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o4 ON (u4.id_usuario = o4.id_usuario)
LEFT JOIN t_pmt_usuarios AS u5 ON (o4.id_local_desejado = u5.id_local_atual)
LEFT JOIN t_pmt_opcoes_permuta AS o5 ON (u5.id_usuario = o5.id_usuario)
WHERE 
	u1.id_local_atual=o5.id_local_desejado AND u1.id_usuario NOT IN (u2.id_usuario, u3.id_usuario, u4.id_usuario, u5.id_usuario)
ORDER BY
	vl_check ASC, o1.id_opcao ASC;
-- adiciona chave primária
ALTER TABLE t_pmt_comb_5 ADD id_comb_5 INT PRIMARY KEY NOT NULL AUTO_INCREMENT FIRST;
-- deleta combinacoes grau 3
DELETE FROM t_pmt_comb_5 
WHERE 
	((id_opcao_1, id_opcao_2) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2)) 
	OR 
	((id_opcao_2, id_opcao_3) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR 
	((id_opcao_3, id_opcao_4) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR 
	((id_opcao_4, id_opcao_5) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR 
	((id_opcao_5, id_opcao_1) IN (SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2))
	OR
	((id_opcao_1, id_opcao_2, id_opcao_3) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3)) 
	OR 
	((id_opcao_2, id_opcao_3, id_opcao_4) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3))
	OR 
	((id_opcao_3, id_opcao_4, id_opcao_5) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3))
	OR 
	((id_opcao_4, id_opcao_5, id_opcao_1) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3))
	OR 
	((id_opcao_5, id_opcao_1, id_opcao_2) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3))
	OR 
	((id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4 FROM t_pmt_comb_4))
	OR 
	((id_opcao_2, id_opcao_3, id_opcao_4, id_opcao_5) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4 FROM t_pmt_comb_4))
	OR 
	((id_opcao_3, id_opcao_4, id_opcao_5, id_opcao_1) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4 FROM t_pmt_comb_4))
	OR 
	((id_opcao_4, id_opcao_5, id_opcao_1, id_opcao_2) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4 FROM t_pmt_comb_4))
	OR 
	((id_opcao_5, id_opcao_1, id_opcao_2, id_opcao_3) IN (SELECT id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4 FROM t_pmt_comb_4));

