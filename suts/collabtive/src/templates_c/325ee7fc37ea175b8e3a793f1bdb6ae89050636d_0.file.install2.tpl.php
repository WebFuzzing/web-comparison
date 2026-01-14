<?php
/* Smarty version 3.1.29, created on 2026-01-12 18:49:20
  from "/var/www/html/templates/standard/install2.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_696534206e2cc2_67664738',
  'file_dependency' => 
  array (
    '325ee7fc37ea175b8e3a793f1bdb6ae89050636d' => 
    array (
      0 => '/var/www/html/templates/standard/install2.tpl',
      1 => 1414568312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_696534206e2cc2_67664738 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"install",'showheader'=>"no"), 0, false);
?>


			<div class="install" style="text-align:center;padding:5% 0 0 0;">
				<div style="text-align:left;width:500px;margin:0 auto;padding:25px 25px 15px 25px;background:white;border:1px solid;">
			
					<h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installcollabtive');?>
</h1>
			
					<div style="padding:16px 0 16px 0;">
						<h2><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installstep');?>
 3</h2>
			            <em><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'createadmin');?>
</em><br /><br />
			            
						<form class="main" name="adminuser" method="post" enctype="multipart/form-data" action="install.php?action=step3">
							<fieldset>
								
								<div class="row">
									<label for="username"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
:</label>
									<input type="text" name="name" id="username" />
								</div>
								
								<div class="row">
									<label for="pass"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'password');?>
:</label>
									<input type="password" name="pass" id="pass" />
								</div>
							</fieldset>
							
							<br />
							<div class="row-butn-bottom">
								<label>&nbsp;</label>
								<button type="submit" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'continue');?>
</button>
							</div>
							
						</fieldset>
					</form>

				</div>
			</div>
		</div> 
		
	</body>
</html>
<?php }
}
