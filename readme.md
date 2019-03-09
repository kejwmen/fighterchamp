kategorie wiekowe liczone sa rocznikowo!!!

A w co mam się ubrać? Jaki strój sportowy jest dopuszczony?
Czy walki będą nagrywane?
Sędziują licencjonowani sędziowie PZB.

Aby zrezygnować z wiadomości wysyłanych przez sportbonus.pl, wystarczy kliknąć w kliknąć w link.

https://allegro.pl/regulamin/pl - zgoda na otrzymwanie maili reklamowych

https://github.com/Behatch/contexts
java -jar selenium-server-standalone-3.8.1.jar

select u.name, u.surname,
  sum(f.winner_id = u.id) as w,
  sum(f.draw) as d,
  count(*) - sum(f.draw) - sum(f.winner_id = u.id) as l
from user u join user_fight uf on uf.user_id = u.id join fight f on uf.fight_id = f.id group by u.id

SELECT us.surname, us.name
  ,sum(case when f.draw then 1 else 0 end) AS draw
  ,sum(case when f.winner_id = us.id then 1 end) as win
  ,sum(case when f.winner_id != user_id and !f.draw then 1 end) as lose
FROM user as us
  INNER JOIN user_fight AS uf
    ON uf.user_id = us.id
  INNER JOIN fight as f
    ON f.id = uf.fight_id
group by us.surname, us.name

SELECT user.email
FROM user
JOIN signuptournament ON signuptournament.user_id = user.id
WHERE signuptournament.tournament_id =4 and is_paid=false and signuptournament.deleted_at IS NULL

UBEZPIECZENIE
SET @row_number = 0;
 
SELECT 
     @curRow := @curRow + 1 AS row_numbe,

name, surname, pesel, mother_name, father_name FROM user
join signuptournament ON signuptournament.user_id = user.id
JOIN    (SELECT @curRow := 0) r
WHERE signuptournament.tournament_id = 6
AND signuptournament.deleted_at is null 
AND signuptournament.deleted_at_by_admin is null

*****MUZIK

SELECT 
     @curRow := @curRow + 1 AS row_numbe,

concat(surname," " ,name) as nameConcat, youtube_id FROM user
join signuptournament ON signuptournament.user_id = user.id
JOIN    (SELECT @curRow := 0) r
WHERE signuptournament.tournament_id = 6
AND signuptournament.deleted_at is null 
AND signuptournament.deleted_at_by_admin is null
ORDER BY nameConcat

SELECT
  GROUP_CONCAT('youtube-dl ', youtube_id, ' -x -o "',surname,' ' ,name,'.%(ext)s"' SEPARATOR ' && ') as nameConcat FROM user
  join signuptournament ON signuptournament.user_id = user.id
WHERE signuptournament.tournament_id = 6
      AND signuptournament.deleted_at is null
      AND signuptournament.deleted_at_by_admin is null
      AND signuptournament.youtube_id is not null
ORDER BY nameConcat

//dane walczących zawodników

select u.name, u.surname, u.pesel
from fight f
left join user_fight uf on f.id = uf.fight_id
left join user u on uf.user_id = u.id
where tournament_id = 9

