<?php

class Block
{
    public int $index;
    public string $timestamp;
    public array $data;
    public string $previousHash;
    public string $hash;
    public int $nonce = 0;


    public function __construct($index, $timestamp, $data, $previousHash = '')
    {
        $this->index = $index;
        $this->timestamp = $timestamp;
        $this->data = $data;
        $this->previousHash = $previousHash;

        // Calcola l'hash del blocco quando viene creato
        $this->hash = $this->calculateHashBlock();
    }

    // Calcola l'hash del blocco
    public function calculateHashBlock(): string
    {
        return hash(
            'sha256',
            $this->index . $this->timestamp . $this->previousHash . json_encode($this->data) . $this->nonce
        );
    }

    // Processo di "mining" per trovare un hash con il numero di zeri che voglio
    public function miningBlock($difficulty): void
    {
        while (substr($this->hash, 0, $difficulty) !== str_repeat('0', $difficulty)) {
            $this->nonce++;
            $this->hash = $this->calculateHashBlock();
        }
    }
};

class Blockchain
{
    private array $chain;
    private int $difficulty = 6;

    public function __construct()
    {
        $this->chain = [$this->createGenesisBlock()];
    }

    public function createGenesisBlock(): Block
    {
        return new Block(0, "01/01/2020", ['amount' => 0], "0");
    }

    public function getLatestBlock(): Block
    {
        return $this->chain[count($this->chain) - 1];
    }

    //aggiungo un nuovo blocco
    public function addBlock($newBlock): void
    {
        $newBlock->previousHash = $this->getLatestBlock()->hash;

        $newBlock->miningBlock($this->difficulty);
        $newBlock->hash = $newBlock->calculateHashBlock();
        $this->chain[] = $newBlock;
    }

    public function blockIsValid(): bool
    {
        for ($c = 1; $c < count($this->chain); $c++) {
            $currentBlock = $this->chain[$c];
            $previousBlock = $this->chain[$c - 1];

            // Verifica se l'hash del blocco corrente è coerente con il suo contenuto
            if ($currentBlock->hash !== $currentBlock->calculateHashBlock()) {
                return false;
            }

            // Verifica se il valore di previousHash del blocco corrente corrisponde all'hash del blocco precedente
            if ($currentBlock->previousHash !== $previousBlock->hash) {
                return false;
            }
        }
        return true;
    }
}

$myBlockChain = new blockChain();
$myBlockChain->addBlock(new Block(1, "02/07/2023", ['amount' => 4]));
$myBlockChain->addBlock(new Block(2, "03/07/2023", ['amount' => 70]));
$myBlockChain->addBlock(new Block(3, "04/07/2023", ['amount' => 55]));
$myBlockChain->addBlock(new Block(4, "05/07/2023", ['amount' => 3]));



// $myBlockChain->chain[1]->data = ['amount' => 100];
// echo "myBlockChain is correct?" . ($myBlockChain->blockIsValid() ? "yes" : "no");



var_dump($myBlockChain);
echo $myBlockChain->blockIsValid() ? "Valid" : "Invalid";
