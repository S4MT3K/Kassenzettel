<?php

class Artikel extends DBConn
{
    private int|null $produktId;
    private string $name;
    private float $preis;
    private const MWST = 1.07;

    public function __construct(string $nm, float $prs, ?int $prodId = null)
    {
        $this->produktId = $prodId;
        $this->name = $nm;
        $this->preis = $prs;
    }

    public function create(string $name, float $preis): static
    {
        $conn = self::getConn();
        $sqlquery = "INSERT INTO produkte (name, preis) VALUES (:name, :preis)";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute([':name' => $name, ':preis' => $preis]);
        return self::read($conn->lastInsertId());
    }

    public static function read(int $id) : static
    {
        $conn = self::getConn();
        $sqlquery = "SELECT * FROM produkte WHERE id = :id";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(2);
        return new Artikel($result['name'],$result['preis'], $result['id']);
    }

    public static function readAll() : array
    {
        $tempArr = [];
        $conn = self::getConn();
        $sqlquery = "SELECT * FROM produkte";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute();
        $result = $stmt->fetchAll(2);
        /**
         * @var $item Artikel
         */

        foreach ($result as $item) {
            $tempArr[] = new Artikel($item['name'], $item['preis'], $item['id']);
        }
        return $tempArr;
    }

    public function update() : void
    {
        $conn = self::getConn();
        $sqlquery = "UPDATE produkte SET name = :name, preis = :preis WHERE id = :id";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute([':id' => $this->getProduktId(), ':name' => $this->getName(), ':preis' => $this->getPreis()]);
    }

    public function delete() : void
    {
        $conn = self::getConn();
        $sqlquery = "DELETE FROM produkte WHERE id = :id";
        $stmt = $conn->prepare($sqlquery);
        $stmt->execute([':id' => $this->getProduktId()]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProduktId(): int
    {
        return $this->produktId;
    }

    public function setProduktId(int $produktId): void
    {
        $this->produktId = $produktId;
    }

    public function getPreis(): float
    {
        return $this->preis * self::MWST;
    }

    public function setPreis(float $preis): void
    {
        $this->preis = $preis;
    }


}
