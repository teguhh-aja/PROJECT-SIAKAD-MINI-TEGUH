<?php
require_once 'User.php';

class Mahasiswa extends User
{
    private $nim;
    private $jurusan;
    private $angkatan;

    public function __construct($nama, $email, $nim, $jurusan, $angkatan)
    {
        parent::__construct($nama, $email, 'Mahasiswa');
        $this->nim = $nim;
        $this->jurusan = $jurusan;
        $this->angkatan = $angkatan;
    }

    public function getInfo()
    {
        return "Mahasiswa: {$this->nama} (NIM: {$this->nim}) - {$this->jurusan} Angkatan {$this->angkatan}";
    }

    public function getNim()
    {
        return $this->nim;
    }

    public function getJurusan()
    {
        return $this->jurusan;
    }

    public function getAngkatan()
    {
        return $this->angkatan;
    }
}
?>