<?php

namespace FCFormsTest\Data;

use Room11\HTTP\Request\Request;

class Links implements \IteratorAggregate
{
    private $links = [];

    public function __construct(Request $request)
    {
        $data = [
            '/signup' => 'Signup',
            '/list' => 'All elements',
            '/file' => 'File upload',
            '/formValidation' => 'Form validation',
            '/login' => 'Login',
            
        ];

        foreach ($data as $path => $description) {
            $isActive = false;
            if (strcmp($request->getPath(), $path) === 0) {
                $isActive = true;
            }
            $this->links[] = new Link($path, $description, $isActive);
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->links);
    }
}
