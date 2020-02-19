<?php
namespace Model;
class Meta
{
    protected $array_pages;
    public function __construct()
    {
       
        global $container;
        $this->db = $container->get('db');
 
    }
    
    
}
