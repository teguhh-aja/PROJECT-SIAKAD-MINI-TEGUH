<?php
require_once 'User.php';

class Dosen extends User
{
    private $nidn;
    private $bidang;

    public function __construct($nama, $email, $nidn, $bidang)
    {
        parent::__construct($nama, $email, 'Dosen');
        $this->nidn = $nidn;
        $this->bidang = $bidang;
    }

    public function getInfo()
    {
        return "Dosen: {$this->nama} (NIDN: {$this->nidn}) - Bidang: {$this->bidang}";
    }

    public function getNidn()
    {
        return $this->nidn;
    }

    public function getBidang()
    {
        return $this->bidang;
    }
}
?>