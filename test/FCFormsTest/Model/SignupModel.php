<?php

namespace FCFormsTest\Model;

class SignupModel
{
    private $email;
    
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
}
