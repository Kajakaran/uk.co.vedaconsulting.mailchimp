<?php

require_once 'CRM/Core/Controller.php';
require_once 'CRM/Core/Action.php';
require_once 'CRM/Mailchimp/Form/Import/SourceUpload.php';

class CRM_Mailchimp_Controller_Import extends CRM_Core_Controller {
    private $importBAO;
    
    /**
     * class constructor
     */
    function __construct( $title = null, $action = CRM_Core_Action::NONE, $modal = true ) {
        parent::__construct( $title, $modal, null, false, true );

        $this->_stateMachine = new CRM_Core_StateMachine($this, $action);
        
        //matusz: TODO how to add extra step for justgiving?
        	$p = array(
	          'CRM_Mailchimp_Form_Import_SourceUpload' => null,
	        );
        
        $this->_stateMachine->addSequentialPages($p, $action);

        // create and instantiate the pages
        $this->addPages( $this->_stateMachine, $action);

        $this->addActions();
    }
    
    public function getImportBAO() {
        if($this->importBAO === null) {
            require_once 'CRM/Mailchimp/BAO/Import/Source.php';
			$this->importBAO = CRM_Finance_BAO_Import_Source::factory($this->get('sourceName'));
            $importId = $this->get('importId');
            if($importId) {
                $this->importBAO->setImportId($importId);
            }
        }
        return $this->importBAO;
    }

}


