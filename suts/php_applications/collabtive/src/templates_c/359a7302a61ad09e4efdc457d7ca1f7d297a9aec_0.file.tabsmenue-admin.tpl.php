<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:52
  from "/var/www/html/templates/standard/tabsmenue-admin.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b540667c25_48092783',
  'file_dependency' => 
  array (
    '359a7302a61ad09e4efdc457d7ca1f7d297a9aec' => 
    array (
      0 => '/var/www/html/templates/standard/tabsmenue-admin.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69c5b540667c25_48092783 ($_smarty_tpl) {
?>
<div class="tabswrapper">
	<ul class="tabs">
		<li class="projects"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['projecttab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="admin.php?action=projects"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projectadministration');?>
</span></a></li>
		<li class="customers"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['customertab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="admin.php?action=customers"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'customeradministration');?>
</span></a></li>
		<li class="user"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['usertab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="admin.php?action=users"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'useradministration');?>
</span></a></li>
		<li class="system-settings"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['settingstab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="admin.php?action=system"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'systemadministration');?>
</span></a></li>
	</ul>
</div><?php }
}
