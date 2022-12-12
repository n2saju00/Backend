# Backend

Tietokantaohjelmointi -kurssin harjoitustyö. Verkkokaupalle tarkoitetun backendin esimerkkitoimintoja. 

Sen sijaan, että jokaiselle toiminnolle olisi oma PHP tiedosto, samaan dataan liittyviä toimintoja on laitettu samaan tiedostoon käyttäen switchiä (requestissa action -parametri).
Joissain tapauksissa laiminlyödään tietosuojakäytäntöjä kertomalla tarkempia tietoja virheestä requestiin vastattaessa.

# Esivaatimukset
Postman (import Backend - tietokantaohjelmointi.postman_collection.json)

Visual Studio Codella testaamiseen PHP Server -extension.
Laajennuksen asetuksista portiksi 3001 -> Serve Project

# Käyttö
Kaikki toiminnot (ja joitain virheellisten tapahtumien käsittelyjä) on exportattu postman collection tiedostoon jonka saa importattua suoraan testaamisen helpottamiseksi.
Komennot postmanissa tarkoitettu ajettavaksi järjestyksessä, koska esimerkiksi sessiotietoja tarvitaan joissain requesteissa myöhemmin.
Tottakai voi kokeilla esimerkiksi admin komentoja kun ollaan kirjautuneena esimerkiksi juuri luodulle tilille.

Mukana olevassa tietokannassa esimerkkidata. Tietokannan voi nollata tarvittaessa mukana tulevilla SQL komennoilla
