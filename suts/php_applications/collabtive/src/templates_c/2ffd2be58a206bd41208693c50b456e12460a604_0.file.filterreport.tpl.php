<?php
/* Smarty version 3.1.29, created on 2026-01-12 18:51:22
  from "/var/www/html/templates/standard/filterreport.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_6965349a762aa3_12625991',
  'file_dependency' => 
  array (
    '2ffd2be58a206bd41208693c50b456e12460a604' => 
    array (
      0 => '/var/www/html/templates/standard/filterreport.tpl',
      1 => 1414568312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6965349a762aa3_12625991 ($_smarty_tpl) {
?>
<div class="block_in_wrapper">
	
	<h2><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'filterreport');?>
</h2>

	<form class="main" method="get" action="manageuser.php"  onsubmit="return validateCompleteForm(this);"  >
		<fieldset>
			
			<div class="row">
				<label for="start"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'start');?>
:</label>
				<input type="text" name="start" id="start" onfocus="dpck.close();" value="<?php echo $_smarty_tpl->tpl_vars['start']->value;?>
" />
			</div>
			
			<div class="datepick">
				<div id="datepicker_startfilter" class="picker" style="display:none;"></div>
			</div>
			
			<div class="row">
				<label for="end"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'end');?>
:</label>
				<input type="text" name="end" id="end" onfocus="dpck2.close();" value="<?php echo $_smarty_tpl->tpl_vars['end']->value;?>
" />
			</div>
			
			<div class="datepick">
				<div id="datepicker_endfilter" class="picker" style="display:none;"></div>
			</div>
			
			<div class="row">
				<label for="fproject"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'project');?>
</label>
				<select name="project" id="fproject">
					<option value=""><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
					<?php
$__section_proj_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_proj']) ? $_smarty_tpl->tpl_vars['__smarty_section_proj'] : false;
$__section_proj_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['opros']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_proj_0_total = $__section_proj_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_proj'] = new Smarty_Variable(array());
if ($__section_proj_0_total != 0) {
for ($__section_proj_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index'] = 0; $__section_proj_0_iteration <= $__section_proj_0_total; $__section_proj_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index']++){
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['opros']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index'] : null)]['ID'];?>
" <?php if ($_smarty_tpl->tpl_vars['opros']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index'] : null)]['ID'] == $_smarty_tpl->tpl_vars['fproject']->value) {?> selected="selected" <?php }?> >
							<?php echo $_smarty_tpl->tpl_vars['opros']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_proj']->value['index'] : null)]['name'];?>

						</option>
					<?php
}
}
if ($__section_proj_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_proj'] = $__section_proj_0_saved;
}
?>
				</select>
			</div>
			
			<input type="hidden" name="action" value="profile" />
			
			<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
" />
			
			<?php echo '<script'; ?>
 type="text/javascript">
				theCal = new calendar(<?php echo $_smarty_tpl->tpl_vars['theM']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['theY']->value;?>
);
				theCal.dayNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'monday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'tuesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'wednesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'thursday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'friday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'saturday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'sunday');?>
"];
				theCal.monthNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'january');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'february');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'march');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'april');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'may');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'june');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'july');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'august');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'september');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'october');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'november');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'december');?>
"];
				theCal.relateTo = "start";
				theCal.keepEmpty = true;
				theCal.dateFormat = "<?php echo $_smarty_tpl->tpl_vars['settings']->value['dateformat'];?>
";
				theCal.getDatepicker("datepicker_startfilter");
			<?php echo '</script'; ?>
>
			
			<?php echo '<script'; ?>
 type="text/javascript">
				theCal2 = new calendar(<?php echo $_smarty_tpl->tpl_vars['theM']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['theY']->value;?>
);
				theCal2.dayNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'monday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'tuesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'wednesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'thursday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'friday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'saturday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'sunday');?>
"];
				theCal2.monthNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'january');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'february');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'march');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'april');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'may');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'june');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'july');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'august');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'september');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'october');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'november');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'december');?>
"];
				theCal2.relateTo = "end";
				theCal2.keepEmpty = true;
				theCal2.dateFormat = "<?php echo $_smarty_tpl->tpl_vars['settings']->value['dateformat'];?>
";
				theCal2.getDatepicker("datepicker_endfilter");
			<?php echo '</script'; ?>
>
			
			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'filter');?>
</button>
				<button onclick= "javascript:blindtoggle('form_filter');toggleClass('filter_report','filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');return false;" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'cancel');?>
</button>
			</div>
			
		</fieldset>
	</form>

</div> 
<?php }
}
