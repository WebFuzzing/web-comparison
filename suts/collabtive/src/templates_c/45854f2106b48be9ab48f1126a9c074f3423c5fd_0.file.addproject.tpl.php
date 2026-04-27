<?php
/* Smarty version 3.1.29, created on 2026-04-27 01:56:04
  from "/var/www/html/templates/standard/forms/addproject.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69eea614818912_32596239',
  'file_dependency' => 
  array (
    '45854f2106b48be9ab48f1126a9c074f3423c5fd' => 
    array (
      0 => '/var/www/html/templates/standard/forms/addproject.tpl',
      1 => 1490339474,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69eea614818912_32596239 ($_smarty_tpl) {
?>
<div class="block_in_wrapper">
	<h2><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addproject');?>
</h2>
	<form id="addprojectform" class="main" method="post" action="admin.php?action=addpro">
		<fieldset>
            <!--projectTemplatesSelect-->
			<div class="row">
				<label for="name"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
:</label>
				<input type="text" class="text" name="name" id="name" required="1" />
			</div>

			<div class="row">
				<label for="desc"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'description');?>
:</label>
				<div class="editor">
					<textarea name="desc" id="desc" rows="3" cols="1"></textarea>
				</div>
			</div>

		    <div class="clear_both_b"></div>

			<div class="row">
				<label for="end"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'due');?>
:</label>
				<input type="text" class="text" name="end" id="endP" />
			</div>

			<div class="row">
				<label for="neverdue"></label>
				<input type="checkbox" class="checkbox" value="neverdue" name="neverdue" id="neverdue" onclick="document.getElementById('endP').value='';
				document.getElementById('endP').disabled=!document.getElementById('endP').disabled;">
				<label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'neverdue');?>
</label>
			</div>

			<div class="datepick">
				<div id="add_project" class="picker display-none"></div>
			</div>

			<div class="row">
				<label for="budget"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'budget');?>
:</label>
				<input type="text" class="text" name="budget" id="budget" value="0" />
			</div>

			<div class = "row">
				<label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'customer');?>
:</label>
				<select name="company" id="company">
					<option value="-1"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
					<?php
$__section_customer_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_customer']) ? $_smarty_tpl->tpl_vars['__smarty_section_customer'] : false;
$__section_customer_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['customers']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_customer_0_total = $__section_customer_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_customer'] = new Smarty_Variable(array());
if ($__section_customer_0_total != 0) {
for ($__section_customer_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_customer']->value['index'] = 0; $__section_customer_0_iteration <= $__section_customer_0_total; $__section_customer_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_customer']->value['index']++){
?>
						<option value = "<?php echo $_smarty_tpl->tpl_vars['customers']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_customer']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_customer']->value['index'] : null)]['ID'];?>
"><?php echo $_smarty_tpl->tpl_vars['customers']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_customer']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_customer']->value['index'] : null)]['company'];?>
</option>
					<?php
}
}
if ($__section_customer_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_customer'] = $__section_customer_0_saved;
}
?>
				</select>
			</div>

            <div class="row">
                <label for="assignto"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'members');?>
:</label>
                <select name="assignto[]" multiple="multiple" style="height:80px;" id="assignto" required>
                    <option value="" disabled><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
                    <?php
$__section_user_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_user']) ? $_smarty_tpl->tpl_vars['__smarty_section_user'] : false;
$__section_user_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['users']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_user_1_total = $__section_user_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_user'] = new Smarty_Variable(array());
if ($__section_user_1_total != 0) {
for ($__section_user_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] = 0; $__section_user_1_iteration <= $__section_user_1_total; $__section_user_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']++){
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['users']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['ID'];?>
" <?php if ($_smarty_tpl->tpl_vars['users']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['ID'] == $_smarty_tpl->tpl_vars['userid']->value) {?> selected <?php }?> ><?php echo $_smarty_tpl->tpl_vars['users']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_user']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_user']->value['index'] : null)]['name'];?>
</option>
                    <?php
}
}
if ($__section_user_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_user'] = $__section_user_1_saved;
}
?>
                </select>
            </div>

			<input type="hidden" name="assignme" value="1" />
		    <div class="clear_both_b"></div>

			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();" onclick="tinyMCE.triggerSave();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addbutton');?>
</button>

				<button type="reset" onclick="blindtoggle('form_<?php echo $_smarty_tpl->tpl_vars['myprojects']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_project']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_project']->value['index'] : null)]['ID'];?>
');return false;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'cancel');?>
</button>

			</div>

		</fieldset>
	</form>
    <?php if ($_smarty_tpl->tpl_vars['myprojects']->value != 1) {?>
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
        theCal.relateTo = "endP";
        theCal.dateFormat = "<?php echo $_smarty_tpl->tpl_vars['settings']->value['dateformat'];?>
";
        theCal.getDatepicker("add_project");
    <?php echo '</script'; ?>
>
    <?php }?>
</div> 
<?php }
}
