{strip}
{if $quotaList}
	{form legend="Assign Quota to Groups"}
		<input type="hidden" name="page" value="{$page}" />

		{formfeedback error=$errors.group}

		<div class="control-group">
			{formlabel label="Group" for=""}
			{forminput}
				<strong>{tr}Quota{/tr}</strong>
			{/forminput}
		</div>
		{foreach item=grp key=groupId from=$systemGroups}
			<div class="control-group">
				{formlabel label=$grp.group_name for=""}
				{forminput}
					{$groupQuota.$groupId}
				{/forminput}
			</div>
		{/foreach}

		<div class="control-group submit">
			<input type="submit" class="btn btn-default" name="assignquota" value="{tr}Assign quota{/tr}" />
		</div>
	{/form}

	<a href="{$smarty.server.SCRIPT_NAME}?page=quota&newquota=1">{tr}Create New Quota{/tr}</a>
	<table class="table data">
		<caption>{tr}Defined Quotas{/tr}</caption>
		<tr>
			<th>{tr}Quota{/tr}</th>
			<th>{tr}Disk Usage{/tr}</th>
			<th>{tr}Monthly Transfer{/tr}</th>
		</tr>
		{foreach key=quotaId item=quota from=$quotaList}
			<tr class="{cycle values=odd,even}">
				<td><a href="{$smarty.server.SCRIPT_NAME}?page=quota&quota_id={$quotaId}">{$quota.title|escape}</a></td>
				<td align="right">{$quota.disk_usage/1000000} MB</td>
				<td align="right">{$quota.monthly_transfer/1000000} MB</td>
			</tr>
		{/foreach}
	</table>
{else}
	{assign var=editLabel value=$gQuota->mInfo.title|escape|default:"New Quota"}
	{form legend="Edit `$editLabel`"}
		<input type="hidden" name="page" value="{$page}" />
		<input type="hidden" name="quota_id" value="{$gQuota->mQuotaId}" />
		<div class="control-group">
			{formfeedback error=$errors.title}
			{formlabel label="Quota Title" for="title"}
			{forminput}
				<input size="40" type="text" name="title" id="title" value="{$gQuota->mInfo.title|escape}" />
				{formhelp note="This title is used to identify the quota limitations when you assign them to users and groups."}
			{/forminput}
		</div>
		<div class="control-group">
			{formfeedback error=$errors.disk_usage}
			{formlabel label="Disk Usage" for="disk_usage"}
			{forminput}
				<input size="10" type="text" name="disk_usage" id="disk_usage" value="{$gQuota->mInfo.disk_usage/1000000}" />
				{formhelp note="Please enter the desired value in MegaBytes."}
			{/forminput}
		</div>
		<div class="control-group">
			{formfeedback error=$errors.monthly_transfer}
			{formlabel label="Monthly Transfer" for="monthly_transfer"}
			{forminput}
				<input size="10" type="text" name="monthly_transfer" id="monthly_transfer" value="{$gQuota->mInfo.monthly_transfer/1000000}" />
				{formhelp note="Please enter the desired value in MegaBytes."}
			{/forminput}
		</div>

		<div class="control-group submit">
			<input type="submit" class="btn btn-default" name="cancelquota" value="{tr}Cancel{/tr}" />&nbsp;
			<input type="submit" class="btn btn-default" name="savequota" value="{tr}Save quota{/tr}" />
		</div>
	{/form}
{/if}
{/strip}
