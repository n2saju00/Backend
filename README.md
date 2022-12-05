Kaikki toiminnot (ja joitain virheellisten tapahtumien käsittelyjä) on exportattu postman collection tiedostoon.
Sen sijaan, että jokaiselle toiminnolle olisi oma PHP tiedosto, samaan dataan liittyviä toimintoja on laitettu samaan tiedostoon käyttäen switchiä (requestissa action -parametri)
Codessa testaamiseen PHP Server extenionin portti 3001.

Komennot postmanissa tarkoitettu ajettavaksi järjestyksessä, koska esimerkiksi sessiotietoja tarvitaan joissain requesteissa myöhemmin.
