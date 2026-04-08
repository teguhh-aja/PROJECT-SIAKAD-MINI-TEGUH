<?php
abstract class User
{
    protected $nama;
    protected $email;
    protected $role;

    public function __construct($nama, $email, $role)
    {
        $this->nama = $nama;
        $this->email = $email;
        $this->role = $role;
    }

    abstract public function getInfo();

    public function getNama()
    {
        return $this->nama;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRole()
    {
        return $this->role;
    }
}
?>