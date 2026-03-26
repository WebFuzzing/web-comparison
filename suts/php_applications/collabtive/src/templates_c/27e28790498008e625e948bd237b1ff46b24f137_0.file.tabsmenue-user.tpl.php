<?php
/* Smarty version 3.1.29, created on 2026-01-12 18:51:22
  from "/var/www/html/templates/standard/tabsmenue-user.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_6965349a618550_65880205',
  'file_dependency' => 
  array (
    '27e28790498008e625e948bd237b1ff46b24f137' => 
    array (
      0 => '/var/www/html/templates/standard/tabsmenue-user.tpl',
      1 => 1414568312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6965349a618550_65880205 ($_smarty_tpl) {
?>
<div class="tabswrapper">
	<ul class="tabs">
		<?php if ($_smarty_tpl->tpl_vars['user']->value['gender'] == "f") {?>
			<?php if ($_smarty_tpl->tpl_vars['userid']->value == $_smarty_tpl->tpl_vars['user']->value['ID']) {?>
				<li class="user-female"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['usertab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="manageuser.php?action=profile&amp;id=<?php echo $_smarty_tpl->tpl_vars['userid']->value;?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myaccount');?>
</span></a></li>
			<?php } else { ?>
				<li class="user-female"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['usertab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href=""></a></li>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add'] && $_smarty_tpl->tpl_vars['userid']->value != $_smarty_tpl->tpl_vars['user']->value['ID']) {?>
				<li class="edit-male"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['edittab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="admin.php?action=editform&amp;id=<?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>
</span></a></li>
			<?php } elseif ($_smarty_tpl->tpl_vars['userid']->value == $_smarty_tpl->tpl_vars['user']->value['ID']) {?>
				<li class="edit-male"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['edittab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="manageuser.php?action=editform&amp;id=<?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>
</span></a></li>
			<?php }?>

		<?php } else { ?>
			<?php if ($_smarty_tpl->tpl_vars['userid']->value == $_smarty_tpl->tpl_vars['user']->value['ID']) {?>
				<li class="user-male"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['usertab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="manageuser.php?action=profile&amp;id=<?php echo $_smarty_tpl->tpl_vars['userid']->value;?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myaccount');?>
</span></a></li>
			<?php } else { ?>
				<li class="user-male"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['usertab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href=""></a></li>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add'] && $_smarty_tpl->tpl_vars['userid']->value != $_smarty_tpl->tpl_vars['user']->value['ID']) {?>
				<li class="edit-male"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['edittab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="admin.php?action=editform&amp;id=<?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>
</span></a></li>
			<?php } elseif ($_smarty_tpl->tpl_vars['userid']->value == $_smarty_tpl->tpl_vars['user']->value['ID']) {?>
				<li class="edit-male"><a <?php if ((($tmp = @$_smarty_tpl->tpl_vars['edittab']->value)===null||$tmp==='' ? '' : $tmp) == "active") {?>class="active"<?php }?> href="manageuser.php?action=editform&amp;id=<?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>
</span></a></li>
			<?php }?>
		<?php }?>
	</ul>
</div><?php }
}
