<?php

class CRM_Mailchimp_BAO_Import_Source {
    public static function getAllAsOptions() {
        //matusz: TODO this should be using db table?
        return array(
            'MailchimpAccount' => 'Mailchimp Account (CSV)',
        );
    }
    
    public static function factory($typeName) {
        switch($typeName) {
            case 'MailchimpAccount':
                require_once('CRM/Mailchimp/BAO/Import/MailchimpAccount.php');
                $bao = new CRM_Mailchimp_BAO_Import_MailchimpAccount();
                break;
            default:
                throw new Exception("No source BAO '$typeName'");
        }
        $bao->setSourceName($typeName);
        return $bao;
    }
}