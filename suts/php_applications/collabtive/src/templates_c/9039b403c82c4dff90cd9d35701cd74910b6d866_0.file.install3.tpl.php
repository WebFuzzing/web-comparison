<?php
/* Smarty version 3.1.29, created on 2026-01-12 18:49:30
  from "/var/www/html/templates/standard/install3.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_6965342ae3a6a6_83780867',
  'file_dependency' => 
  array (
    '9039b403c82c4dff90cd9d35701cd74910b6d866' => 
    array (
      0 => '/var/www/html/templates/standard/install3.tpl',
      1 => 1414568312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_6965342ae3a6a6_83780867 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"install",'showheader'=>"no"), 0, false);
?>

				
		<div class="install" style="text-align:center; padding:5% 0;">
			<div style="text-align:left;width:500px;margin:0 auto;padding:25px 25px 0px 25px;background:white;border:1px solid;">
				
				<h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installstatus');?>
</h1>
				
				<div style="padding:16px 0 20px 0;">
					
					<h2><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installsuccess');?>
</h2>
					
					<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installsuccesstext');?>

					
				</div>
				
				<div class="row-butn-bottom">
					<button onclick="window.open('http://www.collabtive.o-dyn.de/plugins.php')" onfocus="this.blur();">Learn more about Plugins</button>
				</div>

				<div class="row-butn-bottom">
					<button onclick="location.href='index.php'" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>
</button>
				</div>
				
				<div class="content-spacer"></div>
			</div>
		</div> 
		
	</body>
</html>
<?php }
}
