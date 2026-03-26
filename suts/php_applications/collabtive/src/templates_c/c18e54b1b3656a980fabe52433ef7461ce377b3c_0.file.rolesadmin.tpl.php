<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:52
  from "/var/www/html/templates/standard/rolesadmin.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b540cf6797_24157663',
  'file_dependency' => 
  array (
    'c18e54b1b3656a980fabe52433ef7461ce377b3c' => 
    array (
      0 => '/var/www/html/templates/standard/rolesadmin.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:forms/addroles.tpl' => 1,
  ),
),false)) {
function content_69c5b540cf6797_24157663 ($_smarty_tpl) {
?>
<div class="user" id="adminRoles">
    <div class="headline">
        <a href="javascript:void(0);" id="acc-roles_toggle" class="win_block" onclick="toggleBlock('acc-roles');"></a>
        <div class="wintools"></div>
        <h2>
            <img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/userlist.png"
                 alt=""/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'roles');?>

        </h2>
    </div>

    <div class="block" id="acc_roles">

        <!-- Add Roles-->
        <div id="form_addmyroles" class="addmenue display-none">
            <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:forms/addroles.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('myroles'=>"1"), 0, false);
?>

        </div>

        <table cellpadding="0" cellspacing="0" border="0">

            <thead>
            <tr>
                <th class="a"></th>
                <th class="b"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
</th>
                <th class="c"></th>
                <th class="tools"></th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <td colspan="5"></td>
            </tr>
            </tfoot>

            
            <tbody class="alternateColors" v-for="role in items" id="role_{{*role.ID}}">
            <tr>
                <td></td>
                <td>
                    <div class="toggle-in">
                        <span class="acc-toggle"
                              onclick="accord_roles.toggle(css('#acc_roles_content{{$index}}'));"></span>
                        <a href="#">
                            {{*role.name}}
                        </a>
                    </div>
                </td>
                <td></td>
                <td class="tools">
                    <!--	<a class="tool_edit" href="manageproject.php?action=editform&amp;id={{*role..ID}" title="{#edit#}" ></a>-->
                    <a class="tool_del"
                       href="javascript:confirmDelete('<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'confirmdel');?>
','role_{{*role.ID}}','manageroles.php?action=delrole&amp;id={{*role.ID}}',adminRolesView);"
                       title="{#delete#}"></a>
                </td>
            </tr>
            <tr class="acc">
                <td></td>
                <td colspan="4">
                    <div class="accordion_content">
                        <form class="main" method="post" action="manageroles.php?action=editrole&id={{*role.ID}}"
                              onsubmit="return validateCompleteForm(this);">
                            <fieldset>
                                <div class="clear_both_b"></div>
                                <div class="row">
                                    <label for="rolename"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
:</label>
                                    <input type="text" name="rolename" id="rolename" v-model="role.name"/>
                                </div>

                                <div class="row"><label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'permissions');?>
:</label>

                                    <!-- Permissions for projects -->
                                    <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projects');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[add]"
                                               v-model="role.projects.add"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[edit]"
                                               v-model="role.projects.edit"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[del]"
                                               v-model="role.projects.del"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[close]"
                                               v-model="role.projects.close"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>

                                    </div>

                                    <!-- Permissions for milestones -->
                                    <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'milestones');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_milestones[view]"
                                               v-model="role.milestones.view"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[add]"
                                                              v-model="role.milestones.add"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[edit]"
                                                              v-model="role.milestones.edit"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[del]"
                                                              v-model="role.milestones.del"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[close]"
                                                              v-model="role.milestones.close"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>

                                    </div>

                                    <!-- Permissions for tasks -->
                                    <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'tasks');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[view]"
                                                              v-model="role.tasks.view" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[add]"
                                                              v-model="role.tasks.add" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[edit]"
                                                              v-model="role.tasks.edit" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[del]"
                                                              v-model="role.tasks.del" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[close]"
                                                              v-model="role.tasks.close" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>

                                    </div>


                                    <!-- Permissions for messages, close = reply -->
                                    <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'messages');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[view]"
                                                              v-model="role.messages.view" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[add]"
                                                              v-model="role.messages.add" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[edit]"
                                                              v-model="role.messages.edit" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[del]"
                                                              v-model="role.messages.del" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[close]"
                                                              v-model="role.messages.close" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'answer');?>

                                    </div>

                                    <!-- Permissions for files -->
                                    <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'files');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[view]"
                                                              v-model="role.files.view" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[add]"
                                                              v-model="role.files.add" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[edit]"
                                                              v-model="role.files.edit" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[del]"
                                                              v-model="role.files.del" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                                    </div>

                                    <!-- Permissions for timetracker, read = read other's entries -->
                                    <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'timetracker');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[view]"
                                                              v-model="role.timetracker.view" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[read]"
                                                              v-model="role.timetracker.read"
                                        /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'permissionread');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[add]"
                                                              v-model="role.timetracker.add" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[edit]"
                                                              v-model="role.timetracker.edit" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[del]"
                                                              v-model="role.timetracker.del" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                                    </div>

                                    <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chat');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_chat[add]"
                                                              v-model="role.chat.add" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chat');?>

                                    </div>
                                   <div class="row">
                                        <label></label>
                                        <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'admin');?>
</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_admin[add]"
                                                              v-model="role.admin.add"
                                        /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'administration');?>

                                    </div>

                                </div>

                                <div class="clear_both_b"></div>

                                <div class="row-butn-bottom">
                                    <label>&nbsp;</label>
                                    <button type="submit" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'save');?>
</button>
                                    <button onclick="javascript:accord_roles.activate(css('#acc-roles .accordion_toggle')[{$smarty.section.role.index}]);"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'cancel');?>

                                    </button>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </td>
            </tr>
            </tbody>
            

        </table>

        <!-- add role menu -->
        <div class="tablemenue">
            <div class="tablemenue-in">

                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add']) {?>
                    <a class="butn_link" href="javascript:blindtoggle('form_addmyroles');" id="add_butn_myprojects"
                       onclick="toggleClass('addrolelink','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">
                        <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addrole');?>

                    </a>
                <?php }?>

            </div>
        </div>
    </div>
    <!-- admin roles end --><?php }
}
