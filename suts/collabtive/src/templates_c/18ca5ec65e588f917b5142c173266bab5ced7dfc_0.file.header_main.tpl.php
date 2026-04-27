<?php
/* Smarty version 3.1.29, created on 2026-04-27 01:56:03
  from "/var/www/html/templates/standard/header_main.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69eea613e0d842_30775854',
  'file_dependency' => 
  array (
    '18ca5ec65e588f917b5142c173266bab5ced7dfc' => 
    array (
      0 => '/var/www/html/templates/standard/header_main.tpl',
      1 => 1469074415,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69eea613e0d842_30775854 ($_smarty_tpl) {
?>
<div id="sitebody">
	<div id="header-wrapper">
		<div id="header">
			<div class="header-in">

				<div class="left">
					<div class="logo">
						<h1>
							<a href="index.php" title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'desktop');?>
">
								<img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/logo-b.png" alt="" />
							</a>
							<span class="title"><?php echo $_smarty_tpl->tpl_vars['settings']->value['name'];?>

								<span class="subtitle"> <?php if ($_smarty_tpl->tpl_vars['settings']->value['subtitle']) {?>/ <?php echo $_smarty_tpl->tpl_vars['settings']->value['subtitle'];?>
 <?php }?> </span>
							</span>
						</h1>
					</div>
				</div> 

				<div class="right">

					<?php if ($_smarty_tpl->tpl_vars['loggedin']->value == 1) {?>
						<ul id="mainmenue">
							<li class="desktop">
								<a class="<?php echo $_smarty_tpl->tpl_vars['mainclasses']->value['desktop'];?>
" href="index.php"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'desktop');?>
</span></a>
							</li>

							<?php if ($_smarty_tpl->tpl_vars['usergender']->value == "f") {?>
								<li class="profil-female">
									<a class="<?php echo $_smarty_tpl->tpl_vars['mainclasses']->value['profil'];?>
" href="manageuser.php?action=profile&amp;id=<?php echo $_smarty_tpl->tpl_vars['userid']->value;?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myaccount');?>
</span></a>
								</li>
							<?php } else { ?>
								<li class="profil-male">
									<a class="<?php echo $_smarty_tpl->tpl_vars['mainclasses']->value['profil'];?>
" href="manageuser.php?action=profile&amp;id=<?php echo $_smarty_tpl->tpl_vars['userid']->value;?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myaccount');?>
</span></a>
								</li>
							<?php }?>

							<?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add']) {?>
								<li class="admin">
									<a class="<?php echo $_smarty_tpl->tpl_vars['mainclasses']->value['admin'];?>
" href="admin.php?action=projects"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'administration');?>
</span><span class="submenarrow"></span></a>
									<div class="submen">
										<ul>
											<li class="project-settings"><a class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['classes']->value['overview'])===null||$tmp==='' ? '' : $tmp);?>
" href="admin.php?action=projects"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projectadministration');?>
</span></a></li>
											<li class="customer-settings"><a class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['classes']->value['customer'])===null||$tmp==='' ? '' : $tmp);?>
" href="admin.php?action=customers"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'customeradministration');?>
</span></a></li>
											<li class="user-settings"><a class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['classes']->value['users'])===null||$tmp==='' ? '' : $tmp);?>
" href="admin.php?action=users"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'useradministration');?>
</span></a></li>
											<li class="system-settings"><a class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['classes']->value['system'])===null||$tmp==='' ? '' : $tmp);?>
" href="admin.php?action=system"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'systemadministration');?>
</span></a></li>
										</ul>
									</div>
								</li>
							<?php }?>

							<li class="logout"><a href="manageuser.php?action=logout"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'logout');?>
</span></a></li>
						</ul>
					<?php }?>

				</div> <!-- right END -->

			</div> <!-- header-in END -->
		</div> <!-- header END -->
	</div> <!-- header-wrapper END -->

	<div id="contentwrapper">
<?php }
}
