A w co mam się ubrać? Jaki strój sportowy jest dopuszczony?
Czy muszę mieć swoje rękawice? Kaski? Ochraniacze?
Czy walki będą nagrywane?


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
SELECT name, surname, pesel, mother_name, father_name FROM user
join user_fight ON user_fight.user_id = user.id
join fight ON user_fight.fight_id = fight.id
WHERE fight.tournament_id = 4

