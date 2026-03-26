<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:52
  from "/var/www/html/templates/standard/forms/adduserform.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b54090aea7_81302607',
  'file_dependency' => 
  array (
    '96836c6df077019a9729b63371a2996d85a7152d' => 
    array (
      0 => '/var/www/html/templates/standard/forms/adduserform.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69c5b54090aea7_81302607 ($_smarty_tpl) {
?>
<div class="block_in_wrapper">
	<form novalidate class="main" method="post" action="admin.php?action=adduser"  onsubmit="return validateCompleteForm(this);"  >
		<fieldset>

			<div class="row">
				<label for="name"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
:</label>
				<input type="text" name="name" id="name" required="1" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
" />
			</div>

            <div class="row">
                <label for="pass"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'password');?>
:</label>
                <input type="password" name="pass" id="pass" required="1" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'password');?>
" />
            </div>

            <div class="row">
                <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'role');?>
:</label>
                <select name="role" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'role');?>
" required="1" exclude="-1" id="roleselect">
                    <option value="-1" selected="selected"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
                    <?php
$__section_role_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_role']) ? $_smarty_tpl->tpl_vars['__smarty_section_role'] : false;
$__section_role_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['roles']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_role_0_total = $__section_role_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_role'] = new Smarty_Variable(array());
if ($__section_role_0_total != 0) {
for ($__section_role_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_role']->value['index'] = 0; $__section_role_0_iteration <= $__section_role_0_total; $__section_role_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_role']->value['index']++){
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['roles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_role']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_role']->value['index'] : null)]['ID'];?>
" id="role<?php echo $_smarty_tpl->tpl_vars['roles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_role']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_role']->value['index'] : null)]['ID'];?>
"><?php echo $_smarty_tpl->tpl_vars['roles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_role']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_role']->value['index'] : null)]['name'];?>
</option>
                    <?php
}
}
if ($__section_role_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_role'] = $__section_role_0_saved;
}
?>
                </select>
            </div>

            <div class="row">
                <label for="email"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'email');?>
:</label>
                <input type="text" name="email" id="email" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'email');?>
" />
            </div>

			<div class="row">
				<label for="company"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'company');?>
:</label>
				<input type="text" name="company" id="company" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'company');?>
" />
			</div>

			<div class="row">
				<label id="rate"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'rate');?>
:</label>
				<input type="text" name="rate" id="rate" />
			</div>

			<?php if ($_smarty_tpl->tpl_vars['projects']->value) {?> 
			<div class="clear_both_b"></div>

            <div class="row">
                <label for="assignto"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projects');?>
:</label>
                <select name="assignto[]" multiple="multiple" style="height:80px;" id="assignto">
                    <option value="" disabled><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
                    <?php
$__section_project_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_project']) ? $_smarty_tpl->tpl_vars['__smarty_section_project'] : false;
$__section_project_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['projects']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_project_1_total = $__section_project_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_project'] = new Smarty_Variable(array());
if ($__section_project_1_total != 0) {
for ($__section_project_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_project']->value['index'] = 0; $__section_project_1_iteration <= $__section_project_1_total; $__section_project_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_project']->value['index']++){
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['projects']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_project']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_project']->value['index'] : null)]['ID'];?>
"><?php echo $_smarty_tpl->tpl_vars['projects']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_project']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_project']->value['index'] : null)]['name'];?>
</option>
                    <?php
}
}
if ($__section_project_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_project'] = $__section_project_1_saved;
}
?>
                </select>
            </div>
			<?php }?>

            <div class="clear_both_b"></div>

			<div class="row">
				<label>&nbsp;</label>
				<div class="butn">
					<button type="submit"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addbutton');?>
</button>
				</div>
				<a href = "javascript:blindtoggle('form_member');" class="butn_link"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'cancel');?>
</span></a>
			</div>

		</fieldset>
	</form>

</div> 
<?php }
}
