# Backend

Kaikki toiminnot (ja joitain virheellisten tapahtumien käsittelyjä) on exportattu postman collection tiedostoon.
Sen sijaan, että jokaiselle toiminnolle olisi oma PHP tiedosto, samaan dataan liittyviä toimintoja on laitettu samaan tiedostoon käyttäen switchiä (requestissa action -parametri)
Joissain tapauksissa laiminlyödään tietosuojakäytäntöjä kertomalla tarkempia tietoja virheestä requestiin vastattaessa. 
Codessa testaamiseen PHP Server extenionin portti 3001 -> serve project.

Komennot postmanissa tarkoitettu ajettavaksi järjestyksessä, koska esimerkiksi sessiotietoja tarvitaan joissain requesteissa myöhemmin.
Tottakai voi kokeilla esimerkiksi admin komentoja kun ollaan kirjautuneena esimerkiksi juuri luodulle tilille.
