<?php

spl_autoload_register(function($className){
    include 'class/' . $className . '.php';
});

$salami = Artikel::read(1);
$saft = Artikel::read(3);
$butter = Artikel::read(5);
$kassenzettel = Kassenzettel::read(2);
$kassenzettel->addArtikelToDB($saft, $salami, $butter);
$kassenzettel->deleteArtikelFromBon($salami);
print_r($kassenzettel->getArtikel());

echo $kassenzettel->getAnzahlArtikel();

//$kassenzettel->addArtikelToDB($salami, $saft, $butter);
//$kassenzettel = new Kassenzettel(date_create('now'));
//$kassenzettel->create();
//$kassenzettel->setDatum(date_create_from_format('Y-m-d H:i:s', '2012-22-12 11:11:11'));
//print_r( $kassenzettel);
//$kassenzettel->update();
//foreach (Artikel::readAll() as $item) {
//    $kassenzettel->addArtikel($item);
//}
//
//print_r($kassenzettel->getArt());


//$neuerArtikel = new Artikel('Chio Chips',2.79);
//$neuerArtikel->create();
//$salami1 = Artikel::read(1);
//$salami2 = Artikel::read(2);
//print_r(Artikel::readAll());
//$salami2->delete();
//$salami1->setPreis(0.99);
//$salami1->update();