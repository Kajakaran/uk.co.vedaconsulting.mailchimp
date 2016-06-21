<!-- Customised Civi File - .tpl file invoked: /sites/all/modules/civicrm_finance/templates/CRM/Finance/Form/Import/ValidationSummary.tpl. -->
{if $validationSummary}
    {*<table style="width: 300px">
      <thead><tr><td colspan="2"><b>Import summary</b></td></tr></thead>
      <tbody>
        <tr><td>Total Import Value</td><td>{$validationSummary.total_import_net_amount|crmMoney}</td></tr>
        </tbody>
    </table>*}
    <table style="width: 300px">
      <thead><tr><td colspan="2"><b>Validation summary</b></td></tr></thead>
      <tbody>
        <tr><td style="width: 150px">Errors</td><td {if $validationSummary.total_error}class="crm-error"{/if}>{$validationSummary.total_error}</td></tr>
        <tr><td>Valid Mailchimp Accounts</td><td>{$validationSummary.total_valid}</td></tr>
        {if !$nobrowserlink}<tr><td></td><td><a href="/civicrm/finance/import/browser?id={$importId}&reset=1{if $readonly}&readonly=1{/if}" target="_blank" id="link-open-browser">Open import browser</a></td></tr>{/if}
        </tbody>
    </table>
    {/if}