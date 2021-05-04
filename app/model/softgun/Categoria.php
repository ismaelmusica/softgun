<?php
/**
 * Categoria Active Record
 * @author  <your-name-here>
 */
class Categoria extends TRecord
{
    const TABLENAME = 'categoria';
    const PRIMARYKEY= 'id_categoria';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }


}
