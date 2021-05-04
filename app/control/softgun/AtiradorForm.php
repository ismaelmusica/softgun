<?php
/**
 * AtiradorForm Form
 * @author  <your name here>
 */
class AtiradorForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Atirador');
        $this->form->setFormTitle('Atirador');
        

        // create the form fields
        // $id_atirador = new TDBUniqueSearch('id_atirador', 'softgun', 'Atirador', 'id_atirador', 'nome');
        $id_atirador = new THidden('id_atirador');
        $nome = new TEntry('nome');
        $nome->forceUpperCase();
        $cpf = new TEntry('cpf');
        $cpf->setMask('999.999.999-99');
        $fone = new TEntry('fone');
        $fone->setMask('(99)99999-9999');


        // add the fields
        $this->form->addFields( [ new TLabel('') ], [ $id_atirador ] );
        $this->form->addFields( [ new TLabel('NOME') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('CPF') ], [ $cpf ] );
        $this->form->addFields( [ new TLabel('FONE') ], [ $fone ] );



        // set sizes
        $id_atirador->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');
        $fone->setSize('100%');



        if (!empty($id_atirador))
        {
            $id_atirador->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addActionLink(_t('Back'), new TAction(array('AtiradorList','onReload')),'fa:backward blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('softgun'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Atirador;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id_atirador
            $data->id_atirador = $object->id_atirador;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('softgun'); // open a transaction
                $object = new Atirador($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
