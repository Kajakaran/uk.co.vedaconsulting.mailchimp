<!-- Customised Civi File - .tpl file invoked: /sites/all/modules/civicrm_finance/templates/CRM/Finance/Form/Import/ImportSummary.tpl. -->
{if $importSummary}
    {php}
        require_once 'CRM/Mailchimp/Utils/DataExchange.php';
        require_once 'CRM/Mailchimp/BAO/Import/SourceAbstract.php';
        $importSummary = $this->get_template_vars('importSummary');
        switch($importSummary['status']) {
            case CRM_Mailchimp_Utils_DataExchange::STATUS_IMPORT_STARTED:
                $statusName = 'Uploading ...';
                break;
            case CRM_Mailchimp_Utils_DataExchange::STATUS_IMPORT_FINISHED:
                $statusName = 'Uploaded';
                break;
            case CRM_Mailchimp_BAO_Import_SourceAbstract::STATUS_VALIDATE_STARTED:
                $statusName = 'Validating ...';
                break;
            case CRM_Mailchimp_BAO_Import_SourceAbstract::STATUS_VALIDATE_FINISHED:
                $statusName = 'Validation finished';
                break;
            case CRM_Mailchimp_BAO_Import_SourceAbstract::STATUS_PROCESS_STARTED:
                $statusName = 'Processing ...';
                break;
            case CRM_Mailchimp_BAO_Import_SourceAbstract::STATUS_PROCESS_FINISHED:
                $statusName = 'Processed';
                break;
        }
        $this->assign("statusName", $statusName);
    {/php}
	<table style="width: 300px">
		<thead><tr><td colspan="2"><b>Import details (#{$importSummary.id}{if $importSummary.data.batch_id} / <a href="/civicrm/batch/process?bid={$importSummary.data.batch_id}&reset=1" target="_blank">Batch #{$importSummary.data.batch_id}</a>{/if}) - {$statusName}</b></td></tr></thead>
		<tbody>
		<tr><td style="width: 150px">Import Source</td><td>{$importSummary.sourceName}</td></tr>
        <tr><td>Number of records imported</td><td>{$importSummary.count}</td></tr>
        </tbody>
    </table>
{/if}