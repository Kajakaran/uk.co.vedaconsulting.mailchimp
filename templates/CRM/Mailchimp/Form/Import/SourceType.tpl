<!-- Customised Civi File - .tpl file invoked: /sites/all/modules/civicrm_finance/templates/CRM/Finance/Form/Import/SourceType.tpl. -->
{* WizardHeader.tpl provides visual display of steps thru the wizard as well as title for current step *}
{include file="CRM/common/WizardHeader.tpl"}
<div id="common-form-controls" class="form-item">
    <table class="form-layout-compressed">
        <tr class="">
             <td class="label">{$form.source.label}</td>
             <td>{$form.source.html}</td>
        </tr>
    </table>
</div>
  
<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"} </div>