<?php

namespace FCFormsTest\Model;

class SignupModel
{
    /** @var bool The users email address. */
    private $email;
    
    /** @var bool Whether the user opted into marketing messages */
    private $spamMe;
    
    public function __construct($email, $spamMe)
    {
        $this->email = $email;
        $this->spamMe = $spamMe;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function getSpamMe()
    {
        return $this->spamMe;
    }
}
