<?php

class Kassenzettel extends DBConn
{
    private int|null $bonId;
    private DateTime $dateNtime;
    private array $produkte;
    private int $anzahlArtikel;

    public function __construct(DateTime $dttime, ?int $bId = null)
    {
        $this->bonId = $bId;
        $this->dateNtime = $dttime;
    }

    public function create() : void
    {
        $conn = self::getConn();
        $sqlquery = "INSERT INTO kassenzettel (datum) VALUES (:datum)";
        $stmt = $conn->prepare($sqlquery);
        $date = $this->dateNtime->format('Y-m-d H:i:s');
        $stmt->execute([':datum' => $date]);
        $this->setBonId($conn->lastInsertId());
    }

    public static function read($id) : static
    {
        $conn = self::getConn();
        $sqlquery = "SELECT * FROM kassenzettel WHERE id = :id";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(2);
        return new self(date_create_from_format('Y-m-d H:i:s',$result['datum']), $result['id']);
    }

    public function update() : void
    {
        $conn = self::getConn();
        $sqlquery = "UPDATE kassenzettel SET datum = :datum WHERE id = :id";
        $stmt = $conn->prepare($sqlquery);
        $date = $this->getDatum()->format('Y-m-d H:i:s');
        $stmt->execute([':id' => $this->getBonId(), ':datum' => $date]);
    }

    public function delete() : void
    {
        $conn = self::getConn();
        $sqlquery = "DELETE FROM kassenzettel WHERE id = :id";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute([':id' => $this->getBonId()]);
    }

    public function deleteArtikelFromBon(Artikel ...$art) : void
    {
        $conn = self::getConn();
        $sqlquery = "DELETE FROM bon_artikel WHERE produktId = :id";
        $stmt = $conn->prepare($sqlquery);

        /**
         * @var $art Artikel // Typehinting wichtig hier
         */

        foreach ($art as $item) {

        $stmt->execute([':id' => $item->getProduktId()]);
        }
    }

    public function addArtikelToDB(Artikel ...$art) : void
    {
        $conn = self::getConn();
        $sqlquery = "INSERT INTO bon_artikel (bonId, produktId) VALUES (:bonId, :produktid)";
        $stmt = $conn->prepare($sqlquery);


        foreach ($art as $item) {
            $stmt->execute([':bonId' => $this->getBonId(),':produktid' => $item->getProduktId()]);
            $this->produkte[] = $item;
        }
    }

    public function getArtikel() : array
    {
        $tempArr = [];
        $conn = self::getConn();
        $sqlquery = "SELECT produktId FROM bon_artikel WHERE bonId = :bonId";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute([':bonId' => $this->getBonId()]);
        $result = $stmt->fetchAll(7);
        //$this->anzahlArtikel = count($result); kann man sich die eigentliche anzahl funktion sparen, aber das wollen wior in dem fall nicht

        foreach ($result as $item) {
             $tempArr[] = Artikel::read($item);
        }
        return $tempArr;
    }

    public function getAnzahlArtikel(): int
    {
        $this->anzahlArtikel = count(self::getArtikel());
        return $this->anzahlArtikel;
    }

    public function getBonId(): ?int
    {
        return $this->bonId;
    }

    public function setBonId(?int $bonId): void
    {
        $this->bonId = $bonId;
    }

    public function getDatum() : DateTime
    {
        return $this->dateNtime;
    }

    public function getDatumToString() : string
    {
        return $this->dateNtime->format('Y-m-d H:i:s');
    }

    public function setDatum(DateTime $dateNtime) : void
    {
        $this->dateNtime = $dateNtime;
    }
}