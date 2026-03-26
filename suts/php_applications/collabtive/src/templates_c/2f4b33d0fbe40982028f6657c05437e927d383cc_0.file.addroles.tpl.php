<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:53
  from "/var/www/html/templates/standard/forms/addroles.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b5410b2ce0_35423774',
  'file_dependency' => 
  array (
    '2f4b33d0fbe40982028f6657c05437e927d383cc' => 
    array (
      0 => '/var/www/html/templates/standard/forms/addroles.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69c5b5410b2ce0_35423774 ($_smarty_tpl) {
?>
<div class="block_in_wrapper">
    <h2><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addrole');?>
</h2>
    <form id="addRoleForm" class="main" method="post" action="manageroles.php?action=addrole">
        <fieldset>

            <div class="row">
                <label for="name"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
:</label>
                <input type="text" class="text" name="name" id="name" required="1" realname="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'name');?>
"/>
            </div>
            <div class="clear_both_b"></div>
            <div class="row">
                <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'permissions');?>
:</label>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projects');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[add]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[edit]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[del]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[close]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>

                </div>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'milestones');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[view]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[add]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[edit]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[del]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[close]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>

                </div>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'tasks');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[view]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[add]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[edit]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[del]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[close]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>

                </div>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'messages');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[view]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[add]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[edit]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[del]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[close]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'answer');?>

                </div>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'files');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[view]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[add]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[edit]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[del]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                </div>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'timetracker');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[view]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'view');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[read]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'permissionread');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[add]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'add');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[edit]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>

                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[del]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>

                </div>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chat');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_chat[add]" <?php if ($_smarty_tpl->tpl_vars['roles']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_role']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_role']->value['index'] : null)]['chat']['add']) {?> checked <?php }?> /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chat');?>

                </div>

                <div class="row"><label></label>
                    <label><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'admin');?>
</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_admin[add]"/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'administration');?>

                </div>

            </div>

            <div class="clear_both_b"></div>

            <div class="row-butn-bottom">
                <label>&nbsp;</label>
                <button type="submit" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addbutton');?>
</button>

                <button type="reset"
                        onclick="blindtoggle('form_addmyroles');toggleClass('addrolelink','add-active','add');return false;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'cancel');?>
</button>
            </div>

        </fieldset>
    </form>

</div> 
<?php }
}
