<?php
require_once 'CRM/Core/Form.php';
require_once 'CRM/Core/Selector/Controller.php';
require_once 'CRM/Mailchimp/Selector/Import.php';

class CRM_Mailchimp_Form_ImportBrowser extends CRM_Core_Form
{
    function preProcess() {
        CRM_Utils_Request::retrieve('id', 'Int', $this, false);

        $readonly = CRM_Utils_Request::retrieve('readonly', 'Boolean', $this, false);
        $this->assign('readonly', $readonly);

        $id = $this->get('id');
        $params = $this->controller->exportValues();
        $selector = new CRM_Mailchimp_Selector_Import($id, $params);

        $this->assign('importId', $id);

        $dataExchange = new CRM_Mailchimp_Utils_DataExchange();
        $processData = $dataExchange->getProcessById($id);
        if(isset($processData['data']['status'])) {
            $this->assign('validationSummary', $processData['data']['status']);
        }

        require_once('CRM/Mailchimp/BAO/Import/Source.php');
        $sourceOptions = CRM_Mailchimp_BAO_Import_Source::getAllAsOptions();
        $processData['sourceName'] = $sourceOptions[$processData['source']];
        $this->assign('importSummary', $processData);

        $output = CRM_Core_Selector_Controller::TEMPLATE;

        $sortID = null;
        if ( $this->get( CRM_Utils_Sort::SORT_ID  ) ) {
            $sortID = CRM_Utils_Sort::sortIDValue( $this->get( CRM_Utils_Sort::SORT_ID  ),
                                                   $this->get( CRM_Utils_Sort::SORT_DIRECTION ) );
        }
        $controller = new CRM_Core_Selector_Controller( $selector ,
                                                               $this->get( CRM_Utils_Pager::PAGE_ID ),
                                                               $sortID,
                                                               CRM_Core_Action::VIEW,
                                                               $this,
                                                               $output);
        $controller->setEmbedded( true );
        $controller->run();
    }

    function postProcess() {

    }

    function buildQuickForm() {
        //MV: 26052015, rename Soft Credit button to Fundraiser
        // $options = array('0' => 'Donor', '1' => 'Soft Credit');
        $options = array('0' => 'Donor', '1' => 'Fundraiser');
        $this->addRadio('is_soft_credit', ts('Contact to update'), $options);
        
        $campaigns = CRM_Campaign_BAO_Campaign::getCampaigns(NULL, NULL, TRUE, FALSE);
        $this->add('select',
                'campaign_id',
                ts('Campaign'),
                array('' => ts('- select -')) + $campaigns,
                FALSE,
                array('class' => 'campaign_id')
        );
        $statuses = array(
            '' => 'All',
            'error' => 'Error',
            'ok' => 'OK',
        );
        $this->add('select', 'status', ts('Status'), $statuses);
        
        $this->addButtons( array(
                                 array ( 'type'      => 'refresh',
                                         'name'      => ts('Search') ,
                                         'isDefault' => true     )
                                 )
                           );
    }
    
}
