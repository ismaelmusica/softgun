<?php
/**
 * Ranking Active Record
 * @author  <your-name-here>
 */
class Ranking extends TRecord
{
    const TABLENAME = 'ranking';
    const PRIMARYKEY= 'id_ranking';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('col_ranking');
        parent::addAttribute('atirador_ranking');
        parent::addAttribute('clube_ranking');
        parent::addAttribute('mes_ranking');
        parent::addAttribute('total_ranking');
    }


}
