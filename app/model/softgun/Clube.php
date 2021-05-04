<?php
/**
 * Clube Active Record
 * @author  <your-name-here>
 */
class Clube extends TRecord
{
    const TABLENAME = 'clube';
    const PRIMARYKEY= 'id_clube';
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
