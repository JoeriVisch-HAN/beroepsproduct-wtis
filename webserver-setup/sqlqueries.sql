use GelreAirport
go

select * from Passagier

insert into Passagier (naam, geslacht, vluchtnummer)
VALUES('kees van Dam', 'M', 28761)

select vluchtnummer, m.naam, vertrektijd, l.naam, l.land
from Vlucht v
JOIN Maatschappij m
on m.maatschappijcode = v.maatschappijcode
JOIN Luchthaven l
on l.luchthavencode = v.bestemming
WHERE vertrektijd > GETDATE() AND vluchtnummer in (
    SELECT v.vluchtnummer
FROM Maatschappij m
left JOIN Vlucht v
on m.maatschappijcode = v.maatschappijcode
JOIN Passagier p
on p.vluchtnummer = v.vluchtnummer
GROUP BY max_aantal, v.vluchtnummer
HAVING COUNT(p.vluchtnummer) < max_aantal
)
ORDER BY vluchtnummer



SELECT * from Vlucht



select MAX(passagiernummer)+1
from Passagier

select * from Passagier