<?php
require_once 'CRM/Finance/BAO/Import/CsvAbstract.php';

class CRM_Mailchimp_BAO_Import_JustGivingCsv extends CRM_Mailchimp_BAO_Import_CsvAbstract {
    private $directTransferCode = 'JUSTGIVING';
    protected $hideDefaultFields = array('campaign_id', 'received_date');

    public function __construct() {
        $this->setCsvImportParam('characterSet', 'latin1');
        $this->setCsvFields(array(
            'FundraiserUserId',
            'FundraiserTitle',
            'FundraiserFirstName',
            'FundraiserLastName',
            'FundraiserAddressLine1',
            'FundraiserAddressLine2',
            'FundraiserTown',
            'FundraiserCounty',
            'FundraiserPostcode',
            'FundraiserCountry',
            'FundraiserEmail',
            'FundraiserFurtherContact',
            'FundraiserConnectedBenefit',
            'FundraisingPageId',
            'FundraisingPageStatus',
            'PageCreatedDate',
            'PageEventDate',
            'PageExpiryDate',
            'FundraisingPageOfflineAmount',
            'FundraisingPageTargetAmount',
            'FundraisingPageTitle',
            'FundraisingPageURL',
            'FundraisingPageTeamName',
            'FundraisingPageTeamURL',
            'FundraisingPageTeamMembers',
            'InMemoriamFund',
            'OrganisationPortal',
            'OrganisationPortalURL',
            'FundraisingPageInMemoriamName',
            'FundraisingPageBirthdayName',
            'FundraisingPageWeddingNames',
            'PagePledgeReleaseDate',
            'ReferralSite',
            'ReferralSiteURL',
            'EventId',
            'EventName',
            'PromotedEvent',
            'UserCreatedEvent',
            'EventCategory',
            'OverseasEvent',
            'EventDate',
            'EventExpiryDate',
            'DonorUserId',
            'DonorTitle',
            'DonorFirstName',
            'DonorLastName',
            'DonorAddressLine1',
            'DonorAddressLine2',
            'DonorTown',
            'DonorCounty',
            'DonorPostcode',
            'DonorCountry',
            'DonorEmail',
            'DonorFurtherContact',
            'DonorIsConnected',
            'DonorUKTaxPayerStatus',
            'DonationRef',
            'DonationDate',
            'IsPledge',
            'DonationSource',
            'ProductSource',
            'PaymentFrequency',
            'RecurringMandateCreationDate',
            'AppealName',
            'PaymentType',
            'SMSOperator',
            'SMSOperatorDonorTransactionFee',
            'DonationPaymentReference',
            'received_date',//DonationPaymentReferenceDate
            'BlankColumn1',
            'BlankColumn2',
            'BlankColumn3',
            'gross_amount',//DonationAmount
            'IsDonationGAEligible',
            'PaymentProcessingFeeRate',
            'PaymentProcessingFeeAmount',
            'JustGivingTransactionFeeRate',
            'JustGivingTransactionFeeAmount',
            'NetDonationAmount',
            'EstimatedVAT',
            'AmountOfJustGivingTransactionFeePaid',
            'AmountOfPaymentProcessingFeePaid',
            'CommissionPayer',
            'fee_amount',//NetTotalChargesByJustGiving',
            'net_amount',//NetDonationAmountPaid
            'DonationOrigin',
            'DonationNickname',
            'MessageFromDonor',
            'EventCodeIssue',
            'EventCodeApproach',
            'CustomEventCode3',
            'VAReference',
            'Issue',
            'Approach',
            'CustomFundraisingCode4',
            'CustomFundraisingCode5',
            'CustomFundraisingCode6',
          ));
    }

    public function getSearchContactName(array $rec) {
        return "{$rec['FundraiserFirstName']} {$rec['FundraiserLastName']}";
    }

    public function getStatuses() {
        return parent::getStatuses() + array(
            //101 => 'Not paid to the charity yet',
        );
    }

    /**
     * Used to validate one record from import table
     *
     * @param array $rec
     * @param array $importData
     * @return array(
     *    'status' => int
     *    'update' => array(field => newvalue)
     * )
     */
    protected function validateRec(array $rec, array $importData) {
        $update = $donorArray = $fundraiserArray = array();

        // Added code to determine if the FundraiserUserId is 0 then set the FundraiserUserId to the donor id
        // i.e. these contributions are contributions made directly to the charity without a fundraising page
        $validateCampaign = TRUE;
        if ($rec['FundraiserUserId'] == 0) {
            $validateCampaign = FALSE;
            $rec['FundraiserUserId'] = $rec['DonorUserId'];
        }


        if(empty($rec['contact_id'])) {
            // PS 43 UPG New section to use the JG Fundraising User ID before using the VARef
            // Check if DonorFurtherContact is set i.e. determining if the donor is Anonymous
            if(empty($rec['DonorFurtherContact']) || $rec['DonorFurtherContact'] == "No") {
                // Stick this against the Anon Contact
                // TODO This should be paramaterised
                // Get the contact wit the direct transfer ref set to Anonymous and ensure they get hard credited
                $donorArray = $this->validateDirectTransferRef($this->directTransferCode, 'ANONYMOUSJG', self::VALIDATE_ERR_DONOR_REF);
            } else {
                // This section of code is all about finding the contact
                // Three stages
                //  1. See if this person has been a fundraiser in the past i.e. same donor user id exists in the system already
                //  2. Try to Find the contact based on details in the file
                //  3. If neither of the above yeild results then create a new contact
                try {
                    $donorArray = $this->validateDirectTransferRef($this->directTransferCode, $rec['DonorUserId'], self::VALIDATE_ERR_DONOR_REF);
                } catch(CRM_Finance_BAO_Import_ValidateException $e) {
                  /* TO DO Should be dedupe and create a contact if needed, not off of VAref anymore */
                  try {
                    $donorArray = $this->dedupeRec($rec);
                  } catch(Exception $e) {
                    $donorArray = $this->createDonorWithDD($rec, $this->directTransferCode, $rec['DonorUserId']);
                  }
                }
            }
        }

        $update = array_merge($fundraiserArray, $donorArray);

        $rec['transaction_id'] = $update['transaction_id'] = "JG-" . $rec['DonationPaymentReference'] . '/' . $rec['DonationRef'];

        // PS 26/09/2013
        // Added to ensure the file format is correct i.e. the Fundraising user id is in the right column
        $this->validateField($rec, 'FundraiserFurtherContact', 'YesNo', $update);
        $this->validateField($rec, 'FundraiserConnectedBenefit', 'YesNo', $update);

        // This is the new functionality to puplate the soft credit stuff
        if(empty($rec['soft_credit_contact_id'])) {
            try {
                $fundraiserArray = $this->validateSoftCreditDirectTransferRef($this->directTransferCode, $rec['FundraiserUserId'], self::VALIDATE_ERR_FUNDRAISER_REF);
            } catch(CRM_Finance_BAO_Import_ValidateException $e) {
              try {
                $this->validateField($rec, 'VAReference', 'softCreditVARef', $fundraiserArray);
              } catch(CRM_Finance_BAO_Import_ValidateException $e) {
                try {
                    $fundraiserArray = $this->dedupeRec($rec, true);
                } catch(CRM_Finance_BAO_Import_ValidateException $e) {
                    $fundraiserArray = $this->createDonorWithDD($rec, $this->directTransferCode, $rec['FundraiserUserId'], self::VALIDATE_ERR_CREATE_FUNDRAISER, true);
                }
              }
            }
        }
        $update = array_merge($fundraiserArray, $donorArray);

        // PS 43 We should really validate the transaction id prior to importing
        $this->validateField($rec, 'transaction_id', 'transactionId', $update);

        // PS 43 UPG Moved
        //$this->validateField($rec, 'VAReference', 'VARef', $update);
        $this->validateField($rec, 'gross_amount', 'grossAmount', $update);
        $this->validateField($rec, 'net_amount', 'netAmount', $update);
        $this->validateField($rec, 'fee_amount', 'feeAmount', $update);
        $this->validateField($rec, 'DonationDate', 'donationDate', $update, array('format' => 'd/m/Y'));
        $this->validateField($rec, 'received_date', 'paidToCharityDate', $update, array('format' => 'd/m/Y'));

        // PS 03/10/2012
        // Now the campaign ID is coming from Just Giving
        // $this->validateField($rec, 'Approach', 'campaignCode', $update);
        if ($validateCampaign) {//KJ 02/06/2016If we have fundraiser user id then validate campaign
          $this->validateField($rec, 'CustomFundraisingCode4', 'campaignID', $update);
        } elseif(empty($update['campaign_id'])) {
          $campaignId = 71;//General Donations https://trello.com/c/bLvISe22/49-jg-payment-api-feedback
          $contTypeId = $this->getDefaultContributionTypeIdByCampaignId($campaignId);
          $updates = array(
            'campaign_id'       => $campaignId,
            'financial_type_id' => $contTypeId
          );
          $update = array_merge($update, $updates);
          $rec = array_merge($rec, $updates);
        }
        return array(
          'status' => $this->eitherValue('contact_id', $update, $rec) && $this->eitherValue('soft_credit_contact_id', $update, $rec) ? true : false,
          'update' => $update
        );
    }

    /**
     * Used to process one record from import table (already validated),
     * so checked for data consistencies etc.
     *
     * @param array $rec
     * @param int $batchId
     * @param array $batchDetails
     * @return int new status of the processed row
     */
    protected function processRec($weight, array $rec, array $importData) {
        /* PS 43 UPG Replaced with common code
        $batchDetails = $importData['data'];

        unset($batchDetails['financial_type_id']);

        $params = array_merge($rec, $batchDetails);
        $params['weight'] = $weight;
        */
        // PS 43 UPG introduced new routine to set the defaults common to all import types
        $params = self::setImportRecordDefaults($weight, $rec, $importData);

        // PS 43 UPG
        // We're creating a Fundraiser Record for all contacts going forward in the DT section
        if (!empty($rec['FundraiserUserId'])) {
            $hashParams = $params;
            $hashParams['direct_transfer_ref'] = $rec['FundraiserUserId'];
            $this->createDirectTransfer($this->directTransferCode, $hashParams);
        }

        $this->createBatchEntry($params);

        return true;
    }

}
