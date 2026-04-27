<?php
/* Smarty version 3.1.29, created on 2026-04-27 01:55:39
  from "/var/www/html/templates/standard/login.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69eea5fb249df9_31240894',
  'file_dependency' => 
  array (
    '63fbac328d8671831f91f24281e8d388bbaef464' => 
    array (
      0 => '/var/www/html/templates/standard/login.tpl',
      1 => 1414568312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_69eea5fb249df9_31240894 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Login",'showheader'=>"no",'jsload'=>"ajax"), 0, false);
?>


		<div class="login">
			<div class="login-in">
				<div class="logo-name">
					<h1>
						<a href="http://collabtive.o-dyn.de/" title="<?php echo $_smarty_tpl->tpl_vars['settings']->value['name'];?>
 Open Source Project Management">
							<img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/logo-a.png" alt="<?php echo $_smarty_tpl->tpl_vars['settings']->value['name'];?>
" />
						</a>
					</h1>
					<h2><?php echo $_smarty_tpl->tpl_vars['settings']->value['subtitle'];?>
</h2>
				</div>

				<form id="loginform" name="loginform" method="post" action="manageuser.php?action=login"  onsubmit="return validateCompleteForm(this,'input_error');"  >
					<fieldset>

						<div class="row">
							<label for="username" class="username"></label>
							<input type="text" class="text" name="username" id="username" required="1" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
" />
						</div>
	
						<div class="row">
							<label for="pass" class="pass"></label>
							<input type="password" class="text" name="pass" id="pass" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'password');?>
" />
						</div>
	
						<div class="row">
							<label for="stay" class="keep" onclick="toggleClass(this,'keep','keep-active');"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'stayloggedin');?>
</span></label>
							<input type="checkbox" name="staylogged" id="stay" value="1" />
						</div>
	
						<div class="row">
							<button type="submit" class="loginbutn" title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'loginbutton');?>
" onfocus="this.blur();"></button>
						</div>
					
					</fieldset>
				</form>
				
			</div>

			<?php if ($_smarty_tpl->tpl_vars['loginerror']->value == 1) {?>
				<div class="login-alert timetrack">
					<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'loginerror');?>

					
					<?php if ($_smarty_tpl->tpl_vars['mailnotify']->value == 1) {?>
						<br /><br />
						<form id="blaform" name="resetform" class="main" method="post" action="manageuser.php?action=loginerror">
							<div class="row" style="text-align:center;">
								<button style="float:none;margin:0 0 0 0;" onclick="$('blaform').submit();" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'resetpassword');?>
</button>
							</div>
						</form>
					<?php }?>
					
					<div class="clear_both"></div>
				</div>
			<?php }?>
		</div>
		
	</body>
</html><?php }
}
