<?php
/**
 * Federacao Active Record
 * @author  <your-name-here>
 */
class Federacao extends TRecord
{
    const TABLENAME = 'federacao';
    const PRIMARYKEY= 'id_federacao';
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
