{strip}
{legend legend="Your Personal Usage Quota"}
	{if $gBitUser->isAdmin()}
		Administrators have no enforced quota limit
	{else}
		{formfeedback error=$errors.disk_quota}

		<div class="row">
			{formlabel label="Your disk quota"}
			{forminput}
				{formfeedback note="$quota MB"}
			{/forminput}
		</div>

		<div class="row">
			{formlabel label="Your current usage"}
			{forminput}
				{formfeedback note="$usage MB <small>( `$quotaPercent`% )</small>"}
			{/forminput}
		</div>

		<div class="row">
			<div style="border:1px solid #ccc;background:#eee;">
				<div style="width:{$quotaPercent}%;background:#f80;text-align:left;color:#000;line-height:30px;"><small>{$quotaPercent}%</small></div>
			</div>
		</div>
	{/if}
{/legend}
{/strip}
