<?php
/* Smarty version 3.1.29, created on 2026-01-12 18:49:00
  from "/var/www/html/templates/standard/install1.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_6965340cdd48e2_17370146',
  'file_dependency' => 
  array (
    '0b7426bbb767d7bc5713ec1842426e74658b85a1' => 
    array (
      0 => '/var/www/html/templates/standard/install1.tpl',
      1 => 1475624063,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_6965340cdd48e2_17370146 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>((string)$_smarty_tpl->tpl_vars['title']->value),'showheader'=>"no",'jsload'=>"ajax"), 0, false);
?>

 			<div class="install" style="text-align:center;padding:5% 0;">
				<div style="text-align:left;width:500px;margin:0 auto;padding:25px 25px 15px 25px;background:white;border:1px solid;">
				<h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installcollabtive');?>
</h1>
				<div style="border-bottom:1px dashed;padding:16px 0 16px 0;">

					<form class="main" method="get" action="install.php">
						<fieldset>

							<div class="row">
								<label for="language" style="width:210px;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installerlanguage');?>
</label>
								<select name="locale" id="language" onchange="document.forms[0].submit();">
									<option value=""><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
                                    <option value="al"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'al');?>
</option>
                                    <option value="ar"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ar');?>
</option>
                                    <option value="bg"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'bg');?>
</option>
                                    <option value="ca"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ca');?>
</option>
                                    <option value="cs"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'cs');?>
</option>
                                    <option value="da"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'da');?>
</option>
                                    <option value="de"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'de');?>
</option>
                                    <option value="el"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'el');?>
</option>
                                    <option value="en"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'en');?>
</option>
                                    <option value="es"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'es');?>
</option>
                                    <option value="et"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'et');?>
</option>
                                    <option value="fa"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'fa');?>
</option>
                                    <option value="fi"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'fi');?>
</option>
                                    <option value="fr"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'fr');?>
</option>
                                    <option value="gl"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'gl');?>
</option>
                                    <option value="he"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'he');?>
</option>
                                    <option value="hr"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'hr');?>
</option>
                                    <option value="hu"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'hu');?>
</option>
                                    <option value="id"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'id');?>
</option>
                                    <option value="it"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'it');?>
</option>
                                    <option value="ja"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ja');?>
</option>
                                    <option value="lt"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'lt');?>
</option>
                                    <option value="nb"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'nb');?>
</option>
                                    <option value="nl"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'nl');?>
</option>
                                    <option value="nn"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'nn');?>
</option>
                                    <option value="pl"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'pl');?>
</option>
                                    <option value="pt"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'pt');?>
</option>
                                    <option value="pt_br"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'pt_br');?>
</option>
                                    <option value="ro"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ro');?>
</option>
                                    <option value="ru"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ru');?>
</option>
                                    <option value="se"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'se');?>
</option>
                                    <option value="sk"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'sk');?>
</option>
                                    <option value="sl"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'sl');?>
</option>
                                    <option value="sr"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'sr');?>
</option>
                                    <option value="tr"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'tr');?>
</option>
                                    <option value="uk"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'uk');?>
</option>
                                    <option value="vi"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'vi');?>
</option>
                                    <option value="zh"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'zh');?>
</option>
                                    <option value="zh_tw"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'zh_tw');?>
</option>
								</select>
							</div>

						</fieldset>
					</form>

				</div>

				<div style="border-bottom:1px dashed;padding:16px 0 20px 0;">
					<h2>1. <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installerconditions');?>
</h2>

					<div class="row" style="padding-bottom:12px;">
						<i><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'installerchecksconditions');?>
</i>
					</div>

					<table cellpadding="0" cellspacing="0" style="font-style:italic;line-height: 23px">
						<tr>
							<td style="width:260px;"><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'condition');?>
:</strong></td>
							<td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'status');?>
:</strong></td>
						</tr>
						<tr valign="top">
							<td>PHP 5.5+</td>
							<?php if ($_smarty_tpl->tpl_vars['phpver']->value >= 5.5) {?>
								<td><span style="color:green;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-ok.png" alt="OK" /></span></td>
							<?php } else { ?>
								<td><span style="color:red;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-notok.png" alt="Not OK" /><br />(PHP <?php echo $_smarty_tpl->tpl_vars['phpver']->value;?>
 - <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'phpversion');?>
)</span></td>
							<?php }?>
						</tr>
                        <tr valign="top">
                            <td><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'mb_string_enabled');?>
</td>
                            <?php if ($_smarty_tpl->tpl_vars['is_mbstring_enabled']->value) {?>
                                <td><span style="color:green;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-ok.png" alt="OK" /></span></td>
                            <?php } else { ?>
                                <td><span style="color:red;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-notok.png" alt="Not OK" /><br /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'enable_mb_string');?>
</span></td>
                            <?php }?>
                        </tr>
						<tr valign="top">
							<td>'config.php' <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'iswritable');?>
</td>
							<?php if ($_smarty_tpl->tpl_vars['configfile']->value == 1) {?>
								<td><span style="color:green;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-ok.png" alt="OK" /></span></td>
							<?php } else { ?>
								<td><span style="color:red;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-notok.png" alt="Not OK" /><br /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'makefilewritable');?>
</span></td>
							<?php }?>
						</tr>
						<tr valign="top">
							<td>'./files' <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'iswritable');?>
</td>
							<?php if ($_smarty_tpl->tpl_vars['filesdir']->value == 1) {?>
							<td><span style="color:green;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-ok.png" alt="OK" /></span></td>
							<?php } else { ?>
							<td><span style="color:red;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-notok.png" alt="Not OK" /><br /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'makedirwritable');?>
</span></td>
							<?php }?>
						</tr>
						<tr valign="top">
							<td>'./templates_c' <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'iswritable');?>
</td>
							<?php if ($_smarty_tpl->tpl_vars['templatesdir']->value == 1) {?>
							<td><span style="color:green;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-ok.png" alt="OK" /></span></td>
							<?php } else { ?>
							<td><span style="color:red;font-weight:bold;"><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/butn-notok.png" alt="Not OK" /><br /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'makedirwritable');?>
</span></td>
							<?php }?>
						</tr>
					</table>
				</div>

				<?php if ($_smarty_tpl->tpl_vars['configfile']->value == 1 && $_smarty_tpl->tpl_vars['phpver']->value >= 5.5 && $_smarty_tpl->tpl_vars['templatesdir']->value == 1 && $_smarty_tpl->tpl_vars['filesdir']->value == 1 && $_smarty_tpl->tpl_vars['is_mbstring_enabled']->value) {?>
					<div style="padding:16px 0 12px 0;">

						<h2>2. <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'db');?>
</h2>
						<form class="main" method="post" action="install.php?action=step2&locale=<?php echo $_smarty_tpl->tpl_vars['locale']->value;?>
">
							<fieldset>
								<div class="row" style="padding-bottom:12px;"><i>Select your database driver</i></div>
								<label for="db_driver" style="width:210px;">Database Driver:</label>
								<select name="db_driver" id="db_driver">
								<option value="mysql" onclick="$('dbdata').blindDown();">MySQL</option>
								<option value="sqlite" onclick="$('dbdata').blindUp();">SQLite</option>
								</select>
							</fieldset>
						<fieldset id = "dbdata">
						<div style="border-bottom:1px dashed;height:16px;display:block;clear:both;margin-bottom:16px;"></div>
						<div class="row" style="padding-bottom:12px;"><i><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'insertdbaccessdata');?>
</i></div>
								<div class="row">
									<label for="db_host" style="width:210px;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'dbhost');?>
:</label><input type="text" name="db_host" id="db_host" value="localhost" />
								</div>
								<div class="row">
									<label for="db_name" style="width:210px;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'dbname');?>
:</label><input type="text" name="db_name" id="db_name" />
								</div>
								<div class="row">
									<label for="db_user" style="width:210px;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'dbuser');?>
:</label><input type="text" name="db_user" id="db_user" />
								</div>
								<div class="row">
									<label for="db_pass" style="width:210px;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'dbpass');?>
:</label><input type="password" name="db_pass" id="db_pass" />
								</div>
								</fieldset>
								<fieldset>
								<div style="border-bottom:1px dashed;height:16px;display:block;clear:both;margin-bottom:16px;"></div>
								<div class="row" style="padding-bottom:12px;">
									<i><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'clickcontinue');?>
</i>
								</div>
								<div class="row-butn-bottom">
									<label style="width:210px;">&nbsp;</label>
									<button type="submit" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'continue');?>
</button>
								</div>

							</fieldset>
						</form>

					</div>
				<?php } else { ?>
					<br />
					<span style="color: red;font-weight:bold;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'correctfaults');?>
</span>
				<?php }?>

				<div class="content-spacer"></div>

			</div>
		</div> 

	</body>
</html>
<?php }
}
