SELECT idveiculo, km_abast, data_abast, hora_abast, quantidade, valorcompra,
(SELECT max(km_abast) FROM abastecimentos as L1 WHERE L1.idveiculo=abastecimentos.idveiculo and L1.id_abast<abastecimentos.id_abast) as km_anterior,
(SELECT km_abast - km_anterior) as km_perc,
(SELECT km_perc / quantidade) as kmporlitro,
(SELECT valorcompra / km_perc) as reaisporkm,
(SELECT quantidade / km_perc) as litrosporkm
FROM abastecimentos WHERE idveiculo=1;