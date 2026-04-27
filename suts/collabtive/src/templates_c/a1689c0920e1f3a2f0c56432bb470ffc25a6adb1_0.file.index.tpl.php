<?php
/* Smarty version 3.1.29, created on 2026-04-27 01:56:02
  from "/var/www/html/templates/standard/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69eea6126f8a08_73071579',
  'file_dependency' => 
  array (
    'a1689c0920e1f3a2f0c56432bb470ffc25a6adb1' => 
    array (
      0 => '/var/www/html/templates/standard/index.tpl',
      1 => 1504067251,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:tabsmenue-desk.tpl' => 1,
    'file:updateNotify.tpl' => 1,
    'file:desktopProjects.tpl' => 1,
    'file:desktopTasks.tpl' => 1,
    'file:calendar.tpl' => 1,
    'file:desktopMessages.tpl' => 1,
    'file:sidebar-a.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_69eea6126f8a08_73071579 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('treeView'=>"treeView",'jsload'=>"ajax",'jsload1'=>"tinymce",'jsload3'=>"lightbox",'stage'=>"index"), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:tabsmenue-desk.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('desktab'=>"active"), 0, false);
?>


<div id="content-left">
    <div id="content-left-in">
        <!-- project text -->
        <div class="infowin_left display-none"
             id="projectSystemMessage"
             data-icon="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/projects.png"
             data-text-deleted="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projectwasdeleted');?>
"
             data-text-edited="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projectwasedited');?>
"
             data-text-added="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projectwasadded');?>
"
             data-text-closed="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projectwasclosed');?>
"
                >
        </div>
        <!-- task text -->
        <div class="infowin_left display-none"
             id="taskSystemMessage"
             data-icon="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/tasklist.png"
             data-text-deleted="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'taskwasdeleted');?>
"
             data-text-edited="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'taskwasedited');?>
"
             data-text-added="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'taskwasadded');?>
"
             data-text-closed="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'taskwasclosed');?>
"
                >
        </div>
        <!-- messages text -->
        <div class="infowin_left display-none"
             id="messageSystemMessage"
             data-icon="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/msgs.png"
             data-text-deleted="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'messagewasdeleted');?>
"
             data-text-edited="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'messagewasedited');?>
"
             data-text-added="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'messagewasadded');?>
"
                >
        </div>

        <?php if ($_smarty_tpl->tpl_vars['isUpdated']->value) {?>
            <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:updateNotify.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <br/>
        <?php }?>

        <h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'desktop');?>
</h1>

        <div id="block_index" class="block">

            <!--testpluginTwo-->

            
            <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:desktopProjects.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            
            <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:desktopTasks.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            
            <?php if ($_smarty_tpl->tpl_vars['tasknum']->value) {?>
                <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:calendar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <?php }?>
            <!--ganttChart-->

            
            <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:desktopMessages.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


        </div> 
    </div> 
</div> 

<?php echo '<script'; ?>
 type="text/javascript" src="include/js/accordion.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="include/js/modal.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="include/js/views/index.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
        pagination.itemsPerPage = 15;
    <?php if ($_smarty_tpl->tpl_vars['tasknum']->value > 0) {?>
        var tasksView = createView(tasks);
        //add this view to the dependencies of projectsView
        projectsViewDependencies.push(tasksView);

        var accord_tasks;
        tasksView.afterUpdate(function () {
            accord_tasks = new accordion2('desktoptasks');
        });
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['msgnum']->value > 0) {?>
        var messagesView = createView(messages);
        //add this view to the dependencies of projectsView
        projectsViewDependencies.push(messagesView);

        var accord_msgs;
        messagesView.afterUpdate(function () {
            accord_msgs = new accordion2('desktopmessages');
            renderMilestoneTree(messagesView);
            renderFilesTree(messagesView);
        });
    <?php }?>
    //setup dependenciens
    Vue.set(projectsView, "dependencies", projectsViewDependencies);
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sidebar-a.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
