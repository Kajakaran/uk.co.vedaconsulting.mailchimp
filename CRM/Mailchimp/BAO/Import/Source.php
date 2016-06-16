<?php

class CRM_Mailchimp_BAO_Import_Source {
    public static function getAllAsOptions() {
        //matusz: TODO this should be using db table?
        return array(
            'VirginMoney' => 'Virgin Money',
            'VirginMoneyGiftAid' => 'Virgin Money - GiftAid',
            'JustGiving' => 'Just Giving (API)',
            'JustGivingCsv' => 'Just Giving (CSV)',
            'JustGivingGiftAidCsv' => 'Just Giving - GiftAid (CSV)',
            //'DirectDebit' => 'Direct Debit (Submission)',
            'DirectDebitAuth' => 'Direct Debit',
            'StandingOrder' => 'Standing Orders',
            'StandingOrderRBS' => 'Standing Orders (RBS)',
            'PayrollGiving' => 'Payroll Giving',
            'OnlineTrading' => 'Online Trading',
            'PayrollCTC' => 'Payroll CTC',
            'PayrollCAF' => 'Payroll CAF',
            'BTMyDonateCsv' => 'BT My Donate (CSV)',
            'BTMyDonateGiftAidCsv' => 'BT My Donate - Gift Aid (CSV)',
            'MyRaisingCsv' => 'My Raising (CSV)',
            'MyRaisingGAIDCsv' => 'My Raising GAID (CSV)',
        );
    }
    
    public static function factory($typeName) {
        switch($typeName) {
            case 'VirginMoneyGiftAid':
                require_once('CRM/Finance/BAO/Import/VirginMoneyGiftAid.php');
                $bao = new CRM_Finance_BAO_Import_VirginMoneyGiftAid();
                break;
           case 'VirginMoney':
                require_once('CRM/Finance/BAO/Import/VirginMoney.php');
                $bao = new CRM_Finance_BAO_Import_VirginMoney();
                break;
            case 'DirectDebit':
                require_once('CRM/Finance/BAO/Import/DirectDebit.php');
                $bao = new CRM_Finance_BAO_Import_DirectDebit();
                break;
            case 'DirectDebitAuth':
                require_once('CRM/Finance/BAO/Import/DirectDebitAuth.php');
                $bao = new CRM_Finance_BAO_Import_DirectDebitAuth();
                break;
            case 'StandingOrder':
                require_once('CRM/Finance/BAO/Import/StandingOrder.php');
                $bao = new CRM_Finance_BAO_Import_StandingOrder();
                break;
            case 'StandingOrderRBS':
                require_once('CRM/Finance/BAO/Import/StandingOrderRBS.php');
                $bao = new CRM_Finance_BAO_Import_StandingOrderRBS();
                break;
            case 'OnlineTrading':
                require_once('CRM/Finance/BAO/Import/OnlineTrading.php');
                $bao = new CRM_Finance_BAO_Import_OnlineTrading();
                break;
            case 'JustGiving':
                require_once('CRM/Finance/BAO/Import/JustGiving.php');
                $bao = new CRM_Finance_BAO_Import_JustGiving();
                break;
            case 'JustGivingCsv':
                require_once('CRM/Finance/BAO/Import/JustGivingCsv.php');
                $bao = new CRM_Finance_BAO_Import_JustGivingCsv();
                break;
            case 'BTMyDonateCsv':
                require_once('CRM/Finance/BAO/Import/BTMyDonateCsv.php');
                $bao = new CRM_Finance_BAO_Import_BTMyDonateCsv();
                break;
            case 'JustGivingGiftAidCsv':
                require_once('CRM/Finance/BAO/Import/JustGivingGiftAidCsv.php');
                $bao = new CRM_Finance_BAO_Import_JustGivingGiftAidCsv();
                break;
            case 'BTMyDonateGiftAidCsv':
                require_once('CRM/Finance/BAO/Import/BTMyDonateGiftAidCsv.php');
                $bao = new CRM_Finance_BAO_Import_BTMyDonateGiftAidCsv();
                break;
            case 'PayrollCTC':
                require_once('CRM/Finance/BAO/Import/PayrollCTC.php');
                $bao = new CRM_Finance_BAO_Import_PayrollCTC();
                break;
            case 'PayrollCAF':
                require_once('CRM/Finance/BAO/Import/PayrollCAF.php');
                $bao = new CRM_Finance_BAO_Import_PayrollCAF();
                break;
            case 'MyRaisingCsv':
                require_once('CRM/Finance/BAO/Import/MyRaisingCsv.php');
                $bao = new CRM_Finance_BAO_Import_MyRaisingCsv();
                break;
            case 'MyRaisingGAIDCsv':
                require_once('CRM/Finance/BAO/Import/MyRaisingGAIDCsv.php');
                $bao = new CRM_Finance_BAO_Import_MyRaisingGAIDCsv();
                break;
            default:
                throw new Exception("No source BAO '$typeName'");
        }
        $bao->setSourceName($typeName);
        
        //mzeman: TODO default payment method - load from DB?
        $methods = array(
            'VirginMoney' => 16,
            'VirginMoneyGiftAid' => 17,
            'JustGiving' => 13,
            'JustGivingCsv' => 13,
            'BTMyDonateCsv' => 19,
            'BTMyDonateGiftAidCsv' => 20,
            'JustGivingGiftAidCsv' => 14,
            'DirectDebit' => 8,
            'DirectDebitAuth' => 8,
            'StandingOrder' => 3,
            'StandingOrderRBS' => 3,
            'PayrollGiving' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'OnlineTrading' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'PayrollCTC' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'PayrollCAF' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'MyRaisingCsv' => 21,
            'MyRaisingGAIDCsv' => 22,
        );
        
        if(!isset($methods[$typeName])) {
            throw new Exception("No default payment method set for import type");
        }
        $bao->setDefaultPaymentMethodId($methods[$typeName]);

        //mzeman: TODO default payment method - load from DB?
        $methods = array(
            'VirginMoney' => 16,
            'VirginMoneyGiftAid' => 17,
            'JustGiving' => 13,
            'JustGivingCsv' => 13,
            'BTMyDonateCsv' => 19,
            'BTMyDonateGiftAidCsv' => 20,
            'JustGivingGiftAidCsv' => 14,
            'DirectDebit' => 1506,
            'DirectDebitAuth' => 1506,
            'StandingOrder' => 1506,
            'StandingOrderRBS' => 1506,
            'PayrollGiving' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'OnlineTrading' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'PayrollCTC' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'PayrollCAF' => CIVICRM_FINANCE_PAYMENT_FINANCE_IMPORT,
            'MyRaisingCsv' => 21,
            'MyRaisingGAIDCsv' => 22,
        );
        
        if(!isset($methods[$typeName])) {
            throw new Exception("No default financial type set for import type");
        }
        $bao->setDefaultFinancialTypeId($methods[$typeName]);
        
        return $bao;
    }
}