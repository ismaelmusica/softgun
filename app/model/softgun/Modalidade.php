<?php
/**
 * Modalidade Active Record
 * @author  <your-name-here>
 */
class Modalidade extends TRecord
{
    const TABLENAME = 'modalidade';
    const PRIMARYKEY= 'id_modalidade';
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
