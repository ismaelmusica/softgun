<?php
/**
 * Atirador Active Record
 * @author  <your-name-here>
 */
class Atirador extends TRecord
{
    const TABLENAME = 'atirador';
    const PRIMARYKEY= 'id_atirador';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('cpf');
        parent::addAttribute('fone');
    }


}
