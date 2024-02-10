<?php

namespace App\Examples;

class Examples
{

  // METHOD CHAINING

  private $id = 0;
  private $name = '';
  private $address ='';

  public function setStudentId(int $id) : Examples
  {
    $this->id = $id;
    return $this;
  }

  public function setStudentName(string $name) : Examples
  {
    $this->name = $name;
    return $this;
  }

  public function setStudentAddress(string $address) : Examples
  {
    $this->address = $address;
    return $this;
  }

  public function getStudentInfo() : string
  {
    return 'student '. $this->name. ' of id '.$this->id. ' stays at '. $this->address;
  }
}
