<?php
/**
 * RankingList Listing
 * @author  <your name here>
 */
class RankingList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Ranking');
        $this->form->setFormTitle('Ranking Copa Scolt');
        

        // create the form fields
        // $id_ranking = new TDBUniqueSearch('id_ranking', 'softgun', 'Ranking', 'id_ranking', 'col_ranking');
        $id_ranking = new THidden('id_ranking');
        $col_ranking = new TEntry('col_ranking');
        $col_ranking->forceUpperCase();
        $atirador_ranking = new TEntry('atirador_ranking');
        $atirador_ranking->forceUpperCase();
        $clube_ranking = new TEntry('clube_ranking');
        $clube_ranking->forceUpperCase();
        $total_ranking = new TEntry('total_ranking');
        $mes_ranking = new TEntry('mes_ranking');
        $mes_ranking->forceUpperCase();



        // add the fields
        $this->form->addFields( [ new TLabel('') ], [ $id_ranking ] );
        $this->form->addFields( [ new TLabel('Colocação') ], [ $col_ranking ] );
        $this->form->addFields( [ new TLabel('Noma do Atirador') ], [ $atirador_ranking ] );
        $this->form->addFields( [ new TLabel('Clube') ], [ $clube_ranking ] );
        $this->form->addFields( [ new TLabel('Total') ], [ $total_ranking ] );
        $this->form->addFields( [ new TLabel('Mês') ], [ $mes_ranking ] );



        // set sizes
        $id_ranking->setSize('100%');
        $col_ranking->setSize('100%');
        $atirador_ranking->setSize('100%');
        $clube_ranking->setSize('100%');
        $mes_ranking->setSize('100%');


        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['RankingForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        // $column_id_ranking = new TDataGridColumn('id_ranking', 'Id Ranking', 'right');
        $column_col_ranking = new TDataGridColumn('col_ranking', 'Col Ranking', 'left');
        $column_atirador_ranking = new TDataGridColumn('atirador_ranking', 'Atirador Ranking', 'left');
        $column_clube_ranking = new TDataGridColumn('clube_ranking', 'Clube Ranking', 'left');
        $column_total_ranking = new TDataGridColumn('total_ranking', 'Total Ranking', 'left'); 
        $column_mes_ranking = new TDataGridColumn('mes_ranking', 'Mes Ranking', 'left');



        // add the columns to the DataGrid
        // $this->datagrid->addColumn($column_id_ranking);
        $this->datagrid->addColumn($column_col_ranking);
        $this->datagrid->addColumn($column_atirador_ranking);
        $this->datagrid->addColumn($column_clube_ranking);
        $this->datagrid->addColumn($column_total_ranking);
        $this->datagrid->addColumn($column_mes_ranking);



        $action1 = new TDataGridAction(['RankingForm', 'onEdit'], ['id_ranking'=>'{id_ranking}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id_ranking'=>'{id_ranking}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('softgun'); // open a transaction with database
            $object = new Ranking($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue(__CLASS__.'_filter_id_ranking',   NULL);
        TSession::setValue(__CLASS__.'_filter_col_ranking',   NULL);
        TSession::setValue(__CLASS__.'_filter_atirador_ranking',   NULL);
        TSession::setValue(__CLASS__.'_filter_clube_ranking',   NULL);
        TSession::setValue(__CLASS__.'_filter_mes_ranking',   NULL);
        TSession::setValue(__CLASS__.'_filter_total_ranking',   NULL);

        if (isset($data->id_ranking) AND ($data->id_ranking)) {
            $filter = new TFilter('id_ranking', '=', $data->id_ranking); // create the filter
            TSession::setValue(__CLASS__.'_filter_id_ranking',   $filter); // stores the filter in the session
        }


        if (isset($data->col_ranking) AND ($data->col_ranking)) {
            $filter = new TFilter('col_ranking', 'like', "%{$data->col_ranking}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_col_ranking',   $filter); // stores the filter in the session
        }


        if (isset($data->atirador_ranking) AND ($data->atirador_ranking)) {
            $filter = new TFilter('atirador_ranking', 'like', "%{$data->atirador_ranking}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_atirador_ranking',   $filter); // stores the filter in the session
        }


        if (isset($data->clube_ranking) AND ($data->clube_ranking)) {
            $filter = new TFilter('clube_ranking', 'like', "%{$data->clube_ranking}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_clube_ranking',   $filter); // stores the filter in the session
        }


        if (isset($data->mes_ranking) AND ($data->mes_ranking)) {
            $filter = new TFilter('mes_ranking', 'like', "%{$data->mes_ranking}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_mes_ranking',   $filter); // stores the filter in the session
        }


        if (isset($data->total_ranking) AND ($data->total_ranking)) {
            $filter = new TFilter('total_ranking', 'like', "%{$data->total_ranking}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_total_ranking',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue(__CLASS__ . '_filter_data', $data);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'softgun'
            TTransaction::open('softgun');
            
            // creates a repository for Ranking
            $repository = new TRepository('Ranking');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id_ranking';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue(__CLASS__.'_filter_id_ranking')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_id_ranking')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_col_ranking')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_col_ranking')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_atirador_ranking')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_atirador_ranking')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_clube_ranking')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_clube_ranking')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_mes_ranking')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_mes_ranking')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_total_ranking')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_total_ranking')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('softgun'); // open a transaction with database
            $object = new Ranking($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
