<!--

tehniline:

* urli kujud:
kõik, mis ei ole teise tähendusega (kindlaks määratud tagid: /foto, /photo jne), arvestatakse kui tagi
/nature, /triinu_lopetamine, /loodus/mets/triinu (kategooriaid nii palju kui vaja)

erifunktsionaalsusega tag'id:

/photo/unikaalnepilditag (uni-id stiilis, ei muutu kunagi assigneeritakse pildi lisamisel)
/foto/unikaalnepilditag või /foto/unikaalnepralkiri

unikaalsele pilditagile (süsteemi poolt määratud hash, 6kohaline) saab määrata unikaalse pealkirja (alphanum)

kuvatakse pilti tavavaates (täisekraanivaade lisaklikil alati)

märksõnad:
* igale märksõnale on võimalik sisestada tõlkena lisaks teine tag
* 
* saab lisada pildi (milline märksõna piltidest teda kirjeldab galeriivaates)

üldine:
* äärest ääreni alati

header:
* suur vahetuv (max*100px (2em?)), või hägustatud erinevate kõrguste/laiustega thumnailidest koosnev (mituiganes x 3 vms), slider-banner stiilis  randomiga vahetab mõne pildi 2sek järel välja (mobiilis mitte)
* enda nägu (viib kontaktile/õigustele/hindadele), otsingukast, piltide/albumite arv, keelevalik
* kategooriad ("#märksõnad", "uued", "loomad", "maastikud", "lilled", "inimesed", "matkad")
* kategooriad on ka märksõnad, aga märksõnadele saab määrata pealkirja, ja eemaldada nt tema kuvamine pildi all märksõnade hulgas jne)
* "märksõnad" peal klikkides avaneb/sulgub hashtag'ide loetelu (hoitakse küpsises meeles)
* "blogivaade" märksõna, kuvatakse pilte suurelt üksteise all
* (küpsiste warning leht kuva ka, kui küpsist pole aksepteeritud)

footer:
© 2015-2016 Andres Päsoke | Kõik õigused kaitstud
Selle veebilehekülje või tema suvalise alamosa, kaasa arvatud fotode, paljundamine, kopeerimine, müümine või mis tahes muul moel kasutamine on keelatud ilma autori kirjaliku loata.

sisuosa:

* dünaamiline, tumedal taustal (hägus, ruuduline)
* igal pildil peab olema vähemalt üks märksõna
* märksõnade järgi koondatakse pildid, saad valida millise märksõna järgi publitseerida "kategooria"
* "kategooria märksõnad" kuvatakse üksteise järel, by default uuemad üleval
* kuvatakse viimase pildi lisamise/muutmise aega päevades / kuupäevaliselt
* headerina kategooria/märksõna pealkiri/tag (lisaks vb käidud märksõnade nimekiri)
* thumbnailina kasutada by default esimest pilti, kui ei ole määratud märksõna pilti
* kui kategooria vaatest on mindud edasi, kuva thumbnailid by default uuemad all (hoia küpsises sortimise infot)
* pildid oleks järjestatud nii et ei cropitaks ka thumbnailidelt midagi
* kõrgus/laius võiks olla varieeruvad, kuvaks pilte ruumala põhjal (200x150 umbes vms)
* piltidele mingi väike raam ümber, alla pealkiri (kui on)
* pildil hoverdades, kuvatakse 1sek järel tema märksõnu/pealkirja vms
* pildil klikkides kuvatakse detailvaade, mingi efektiga
* kõikidele piltidele ankur külge, et kui detailvaatest tagasi tullakse siis oldakse õiges kohas (äkki saab pilte klikkides uuendada seda asukohta (kerides?) backgroundis)
* sorteerimine: populaarsus, suvaline, aeg, suurus, märksõnad
* leheküljed: lehekülje kaupa (kuva all lehekülgede numbrid, niipalju kui mahub, ja eelmine/järgmine), allakerides laadimine (kuva paremal all, mitu pilti on veel kuvada), http://www.kaupokalda.com/ stiilis laadimine (vaata ka sealt detailvaate, tägide stiili)

detailvaated:

tavavaade:
* pilt avaneb nii et lendab esile, taustale jääb galerii/kaustavaade
* pildi all kirjeldus, märksõnad, võimalus vaadata exif infot
* pildi kohal: eelmine, tagasi, järgmine, täisekraanivaade
* pildil klikkides sulgeb detailvaate