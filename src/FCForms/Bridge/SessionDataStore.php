<?php

namespace FCForms\Bridge;

use FCForms\DataStore;

use ASM\Session;

class SessionDataStore implements DataStore
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param $name
     * @param $default
     * @param $clearOnRead
     * @return mixed
     */
    public function getValue($name, $default, $clearOnRead)
    {
        return $this->session->getSessionVariable($name, $default, $clearOnRead);
    }

    /**
     * @param $name
     * @param $data
     * @return mixed
     */
    public function setValue($name, $data)
    {
        return $this->session->setSessionVariable($name, $data);
    }
}
