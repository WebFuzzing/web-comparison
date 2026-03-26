<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:43
  from "/var/www/html/templates/standard/userprofile.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b5376ba3f0_67678258',
  'file_dependency' => 
  array (
    'a64c861c0b8b52ce1ff4feca49d15c6a331c2cd5' => 
    array (
      0 => '/var/www/html/templates/standard/userprofile.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:tabsmenue-user.tpl' => 1,
    'file:userProfileTimetracker.tpl' => 1,
    'file:userProfileProjects.tpl' => 1,
    'file:sidebar-a.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_69c5b5376ba3f0_67678258 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('jsload'=>"ajax"), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:tabsmenue-user.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('usertab'=>"active"), 0, false);
?>


<div id="content-left">
    <div id="content-left-in">
        <div id="userProfile">
            <div class="user" id="userDetails">
                <div class="headline">
                    <a href="javascript:void(0);" id="userDetails_toggle" class="win_none" onclick=""></a>

                    <h2><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/user.png" alt=""/><?php echo $_smarty_tpl->tpl_vars['user']->value['name'];?>
</h2>

                    <div class="wintools">
                        <div class="export-main">
                            <a class="export"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'export');?>
</span></a>

                            <div class="export-in" style="width:64px;left: -32px;"> 
                                <a class="vcardmale" href="manageuser.php?action=vcard&amp;id=<?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'vcardexport');?>
</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="userwrapper width-100 blockaccordion_content overflow-hidden display-none">
                    <table class="width-100" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="avatarcell width-20" valign="top">
                                <?php if ($_smarty_tpl->tpl_vars['user']->value['avatar'] != '') {?>
                                    <a href="#avatarbig" id="ausloeser">
                                        <div class="avatar-profile text-align-center">
                                            <img src="thumb.php?pic=files/<?php echo $_smarty_tpl->tpl_vars['cl_config']->value;?>
/avatar/<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
&amp;width=150;" alt=""/>
                                        </div>
                                    </a>
                                <?php } else { ?>
                                    <div class="avatar-profile">
                                        <img src="thumb.php?pic=templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/no-avatar-male.jpg&amp;width=122;"
                                             alt=""/>
                                    </div>
                                <?php }?>
                            </td>
                            <td>
                                <div class="message-fluid">
                                    <div class="block">

                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <colgroup>
                                                <col class="a"/>
                                                <col class="b"/>
                                            </colgroup>

                                            <thead>
                                            <tr>
                                                <th colspan="2"></th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                            </tr>
                                            </tfoot>

                                            <tbody class="color-b">
                                            <tr>
                                                <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'company');?>
:</strong></td>
                                                <td class="right"><?php if ($_smarty_tpl->tpl_vars['user']->value['company']) {
echo $_smarty_tpl->tpl_vars['user']->value['company'];
}?></td>
                                            </tr>
                                            </tbody>

                                            <tbody class="color-a">
                                            <tr>
                                                <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'email');?>
:</strong></td>
                                                <td class="right"><a href="mailto:<?php echo $_smarty_tpl->tpl_vars['user']->value['email'];?>
"><?php echo $_smarty_tpl->tpl_vars['user']->value['email'];?>
</a></td>
                                            </tr>
                                            </tbody>

                                            <tbody class="color-b">
                                            <tr>
                                                <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'url');?>
:</strong></td>
                                                <td class="right"><?php echo $_smarty_tpl->tpl_vars['user']->value['url'];?>
</td>
                                            </tr>
                                            </tbody>

                                            <tbody class="color-a">
                                            <tr>
                                                <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'phone');?>
:</strong></td>
                                                <td class="right"><?php echo $_smarty_tpl->tpl_vars['user']->value['tel1'];?>
</td>
                                            </tr>
                                            </tbody>

                                            <tbody class="color-b">
                                            <tr>
                                                <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'cellphone');?>
:</strong></td>
                                                <td class="right"><?php echo $_smarty_tpl->tpl_vars['user']->value['tel2'];?>
</td>
                                            </tr>
                                            </tbody>

                                            <tbody class="color-a">
                                            <tr>
                                                <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'address');?>
:</strong></td>
                                                <td class="right"><?php echo $_smarty_tpl->tpl_vars['user']->value['adress'];?>
</td>
                                            </tr>
                                            </tbody>

                                            <tbody class="color-b">
                                            <tr>
                                                <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'zip');?>
 / <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'city');?>
:</strong></td>
                                                <td class="right"><?php echo $_smarty_tpl->tpl_vars['zipcity']->value;?>
 </td>
                                            </tr>
                                            </tbody>

                                            <tbody class="color-a">
                                            <tr>
                                                <?php if ($_smarty_tpl->tpl_vars['user']->value['state'] == '') {?>
                                                    <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'country');?>
:</strong></td>
                                                    <td class="right"><?php echo $_smarty_tpl->tpl_vars['user']->value['country'];?>
</td>
                                                <?php } elseif ($_smarty_tpl->tpl_vars['user']->value['country'] == '') {?>
                                                    <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'state');?>
:</strong></td>
                                                    <td class="right"><?php echo $_smarty_tpl->tpl_vars['user']->value['state'];?>
</td>
                                                <?php } else { ?>
                                                    <td><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'country');?>
 (<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'state');?>
):</strong></td>
                                                    <td class="right"><?php echo $_smarty_tpl->tpl_vars['user']->value['country'];?>
 (<?php echo $_smarty_tpl->tpl_vars['user']->value['state'];?>
)</td>
                                                <?php }?>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div> 
                                </div> 
                            </td>
                        </tr>
                    </table>
                </div> 
            </div> 
            <div class="padding-bottom-two-px clear_both"></div>
            <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add'] || $_smarty_tpl->tpl_vars['userid']->value == $_smarty_tpl->tpl_vars['user']->value['ID']) {?> 
                <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:userProfileTimetracker.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:userProfileProjects.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <?php }?>
            <div class="padding-bottom-two-px"></div>
        </div>

    </div> 
</div> 

<?php echo '<script'; ?>
 type="text/javascript" src="include/js/accordion.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="include/js/views/userProfileView.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add'] || $_smarty_tpl->tpl_vars['userid']->value == $_smarty_tpl->tpl_vars['user']->value['ID']) {?>
    //create views
    userProfileTimetracker.url = userProfileTimetracker.url + "&id=" + <?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
;
    var userProfileTimetrackerView = createView(userProfileTimetracker);

    userProfileProjects.url = userProfileProjects.url + "&id=" + <?php echo $_smarty_tpl->tpl_vars['user']->value['ID'];?>
;
    var userProfileProjectsView = createView(userProfileProjects);

    var accord_tracker;
    userProfileTimetrackerView.afterUpdate(function() {
        accord_tracker = new accordion2('userTimetrackerAccordeon');
    });

    var accord_projects;
    userProfileProjectsView.afterUpdate(function(){
        accord_projects = new accordion2('userProjectsAccordeon');
    });
   <?php }?>

    //create accordeons
    accordUserprofile = new accordion2("userProfile", {
        classNames: {
            toggle: 'win_none',
            toggleActive: 'win_block',
            content: 'blockaccordion_content'
        }
    });
    window.addEventListener("load",initializeBlockaccordeon);
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sidebar-a.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
