<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:50
  from "/var/www/html/templates/standard/adminusers.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b53ee8cf07_85754057',
  'file_dependency' => 
  array (
    '180cf9cc4b5e5cfa8e32001d1299a4932e74d998' => 
    array (
      0 => '/var/www/html/templates/standard/adminusers.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:tabsmenue-admin.tpl' => 1,
    'file:forms/adduserform.tpl' => 1,
    'file:rolesadmin.tpl' => 1,
    'file:sidebar-a.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_69c5b53ee8cf07_85754057 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('jsload'=>"ajax"), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:tabsmenue-admin.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('usertab'=>"active"), 0, false);
?>


<div id="content-left">
    <div id="content-left-in">
        <div class="user" id="adminUsers">

            <!-- user text -->
            <div class="infowin_left display-none"
                 id="userSystemMessage"
                 data-icon="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/userlist.png"
                 data-text-added="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'userwasadded');?>
"
                 data-text-edited="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'userwasedited');?>
"
                 data-text-deleted="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'userwasdeleted');?>
"
                 data-text-assigned="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'userwasassigned');?>
"
                 data-text-deassigned="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'permissionswereedited');?>
"
                 >
            </div>

            <!-- role text -->
            <div class="infowin_left display-none"
                 id="roleSystemMessage"
                 data-icon="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/userlist.png"
                 data-text-edited="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'roleedited');?>
"
                 data-text-added="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'roleadded');?>
"
                 data-text-deleted="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'roleadded');?>
"
                 >
            </div>

            <h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'administration');?>
<span>/ <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'useradministration');?>
</span></h1>

            <div class="headline">
                <a href="javascript:void(0);" id="block_files_toggle" class="win_block"
                   onclick="toggleBlock('block_files');"></a>

                <div class="wintools">
                    <loader block="adminUsers" loader="loader-users.gif"></loader>

                    <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add']) {?>
                        <a class="add" href="javascript:blindtoggle('form_member');" id="addmember"
                           onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');">
						<span>
                            <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'adduser');?>

						</span>
                        </a>
                    <?php }?>
                </div>

                <h2>
                    <img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/userlist.png"
                         alt=""/>
                    <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'useradministration');?>

                    <pagination view="adminUsersView" :pages="pages" :current-page="currentPage"></pagination>
                </h2>
            </div>
            <div id="block_files" class="blockwrapper">
                <!--Add User-->
                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add']) {?>
                    <div id="form_member" class="addmenue display-none">
                        <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:forms/adduserform.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    </div>
                <?php }?>
                <div class="nosmooth" id="sm_member">
                    <div class="content_in_wrapper">
                        <div class="content_in_wrapper_in">
                            <div class="inwrapper">
                                
                                <ul>
                                    <li v-for="user in items">
                                        <div class="itemwrapper" id="iw_{{*user.ID}}">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td class="leftmen" valign="top">
                                                        <div class="inmenue">
                                                            <a v-if="*user.avatar != ''" class="more"
                                                               href="javascript:fadeToggle('info_{{*user.ID}}');"></a>
                                                        </div>
                                                    </td>
                                                    <td class="thumb">
                                                        <a href="manageuser.php?action=profile&amp;id={{*user.ID}}"
                                                           title="{{*user.name}}">

                                                            <img v-if="user.gender == 'f'" 
                                                                 src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/user-icon-female.png" 
                                                                 alt=""/>
                                                            <img v-else 
                                                                 src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/user-icon-male.png" 
                                                                 alt=""/>
                                                        </a>
                                                    </td>
                                                    <td class="rightmen" valign="top">
                                                        <div class="inmenue">
                                                            <a class="edit"
                                                               href="admin.php?action=editform&amp;id={{*user.ID}}"
                                                               title="{#edit#}"></a>
                                                            <a v-if="user.ID != <?php echo $_smarty_tpl->tpl_vars['userid']->value;?>
"
                                                               class="del"
                                                               href="javascript:confirmit('<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'confirmdel');?>
','admin.php?action=deleteuserform&amp;id={{*user.ID}}');"
                                                               title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>
"></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
															<span class="name">
																<a href="manageuser.php?action=profile&amp;id={{*user.ID}}"
                                                                   title="{{*user.name}}">
                                                                    {{*user.name}}
                                                                </a>
															</span>
                                                    </td>
                                                </tr>
                                            </table>

                                            <template v-if="{{user.avatar != ''}}">
                                                <div class="moreinfo-wrapper">
                                                    <div class="moreinfo display-none" id="info_{{*user.ID}}">
                                                        <img src="thumb.php?pic=files/<?php echo $_smarty_tpl->tpl_vars['cl_config']->value;?>
/avatar/{{*user.avatar}}&amp;width=82"
                                                             alt="" onclick="fadeToggle('info_{{*user.ID}');"/>
                                                            <span class="name">
                                                                <a href="manageuser.php?action=profile&amp;id={{*user.ID}}">
                                                                    {{*user.name}}
                                                                </a>
                                                            </span>
                                                    </div>
                                                </div>
                                            </template>

                                        </div>
                                        <!--itemwrapper End-->
                                    </li>
                                     <!--loop folder End-->
                                </ul>
                            </div>
                            <!--inwrapper End-->
                        </div>
                        <!--content_in_wrapper_in End-->
                    </div>
                    <!--content_in_wrapper End-->

                    <div class="staterow">
                        <div class="staterowin">
                            <!--place for whatever-->
                        </div>
                        <div class="staterowin_right">
							<span>
								<?php echo (($tmp = @$_smarty_tpl->tpl_vars['langfile']->value['page'])===null||$tmp==='' ? '' : $tmp);?>

							</span>
                        </div>
                    </div>

                </div>
                <!--nosmooth End-->
                <div class="tablemenue">
                    <div class="tablemenue-in">

                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add']) {?>
                            <a class="butn_link" href="javascript:blindtoggle('form_member');" id="add_butn_member"
                               onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addmember','add-active','add');toggleClass('sm_member','smooth','nosmooth');">
                                <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'adduser');?>

                            </a>
                        <?php }?>

                    </div>
                </div><!-- admin users end -->
            </div><!--content left end-->
        </div><!-- content left in end-->
        <!--block END-->
        <div class="content-spacer"></div>
        <!-- Roles -->
        <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:rolesadmin.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <!-- Roles End -->
        <div class="content-spacer"></div>
    </div>
    <!--userAdmin END-->
</div> <!--content-left-in END-->
</div> <!--Content_left end-->



    <?php echo '<script'; ?>
 type="text/javascript" src="include/js/accordion.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="include/js/views/adminUsersView.min.js"><?php echo '</script'; ?>
>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sidebar-a.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
