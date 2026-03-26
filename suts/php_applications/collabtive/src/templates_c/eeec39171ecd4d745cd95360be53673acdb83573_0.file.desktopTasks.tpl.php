<?php
/* Smarty version 3.1.29, created on 2026-01-12 18:49:52
  from "/var/www/html/templates/standard/desktopTasks.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69653440dbbb82_79034138',
  'file_dependency' => 
  array (
    'eeec39171ecd4d745cd95360be53673acdb83573' => 
    array (
      0 => '/var/www/html/templates/standard/desktopTasks.tpl',
      1 => 1495584561,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:forms/addmytask_index.tpl' => 1,
  ),
),false)) {
function content_69653440dbbb82_79034138 ($_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['tasknum']->value > 0) {?>
    <div class="tasks padding-bottom-two-px" id="desktoptasks">
        <div class="headline">
            <a href="javascript:void(0);" id="taskhead_toggle" class="win_none" onclick=""></a>

            <div class="wintools">
                <loader block="desktoptasks" loader="loader-tasks.gif"></loader>
            </div>
            <h2><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/tasklist.png" alt="" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'mytasks');?>
</h2>
        </div>
        <div class="block blockaccordion_content overflow-hidden display-none" id="taskhead">
            <div id="form_addmytask" class="addmenue display-none">
                <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:forms/addmytask_index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </div>
            <div class="nosmooth" id="sm_desktoptasks">
                <table  cellpadding="0" cellspacing="0" border="0" v-cloak>
                    <thead>
                    <tr>
                        <th class="a"></th>
                        <th class="b" class="cursor-pointer"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'task');?>
</th>
                        <th class="c" class="cursor-pointer"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'project');?>
</th>
                        <th class="d" class="cursor-pointer"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'daysleft');?>
&nbsp;&nbsp;</th>
                        <th class="tools"></th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                    </tfoot>
                 
                    
                    <tbody v-for="item in items" class="alternateColors" v-bind:id="'task_' + item.ID">
                        <tr v-bind:class="{ 'marker-late': item.islate, 'marker-today': item.istoday }" >
                            <td>
                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['tasks']['close']) {?>
                                    <a class="butn_check"
                                    v-bind:href="'javascript:closeElement(\'task_'+item.ID+'\',\'managetask.php?action=close&amp;tid='+item.ID+'&amp;id='+item.project+'\', tasksView);'"
                                    title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>
"></a>
                                <?php }?>

                            </td>
                            <td>
                                <div class="toggle-in">
                                    <span v-bind:id="'desktoptasks_toggle' + item.ID"
                                          class="acc-toggle"
                                          :onclick="'accord_tasks.toggle(document.querySelector(\'#desktoptasks_content'+$index+'\'));'"></span>
                                    <a v-bind:href="'managetask.php?action=showtask&amp;id=' + item.project + '&amp;tid=' + item.ID"
                                       v-bind:title=item.title>
                                        {{{item.title | truncate '30' }}}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <a v-bind:href="'managetask.php?action=showproject&amp;id=' +item.project">{{item.pname | truncate '30' }}</a>
                            </td>
                            <td class="text-align-right">{{item.daysleft}}&nbsp;&nbsp;</td>
                            <td class="tools">

                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['tasks']['edit']) {?> 
                                    <a class="tool_edit" href="javascript:void(0);"
                                       v-bind:href="'javascript:change(\'managetask.php?action=editform&amp;tid='+item.ID+'&amp;id='+item.project+'\',\'form_addmytask\');blindtoggle(\'form_addmytask\');'"
                                       title="Edit"></a>
                                <?php }?>

                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['tasks']['del']) {?>
                                    <a class="tool_del"
                                    v-bind:href="'javascript:confirmDelete(\'<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'confirmdel');?>
\',\'task_'+item.ID+'\',\'managetask.php?action=del&amp;tid='+item.ID+'&amp;id='+item.project+'\',tasksView);'"  title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>
"></a>

                                <?php }?>

                            </td>
                        </tr>

                        <tr class="acc">
                            <td colspan="5">
                                <div class="accordion_content" >
                                    <div class="acc-in">
                                        <div class="message-in-fluid" >
                                            {{{item.text}}}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>

                </table>
                

                <div class="tablemenue">
                    <div class="tablemenue-in">
                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['tasks']['add']) {?>

                            <a class="butn_link" href="javascript:void(0);"
                               id="add_butn_mytasks"
                               onclick="blindtoggle('form_addmytask');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_desktoptasks','smooth','nosmooth');"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addtask');?>
</a>
                        <?php }?>
                    </div>
                </div>
                <div class="content-spacer"></div>
            </div> 
        </div> 
    </div> 
    <?php echo '<script'; ?>
 type="text/javascript">
        theCalStart = new calendar(<?php echo $_smarty_tpl->tpl_vars['theM']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['theY']->value;?>
);
        theCalStart.dayNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'monday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'tuesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'wednesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'thursday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'friday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'saturday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'sunday');?>
"];
        theCalStart.monthNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'january');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'february');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'march');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'april');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'may');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'june');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'july');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'august');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'september');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'october');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'november');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'december');?>
"];
        theCalStart.relateTo = "start";
        theCalStart.dateFormat = "<?php echo $_smarty_tpl->tpl_vars['settings']->value['dateformat'];?>
";
        theCalStart.getDatepicker("datepicker_start_task");
        theCal = new calendar(<?php echo $_smarty_tpl->tpl_vars['theM']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['theY']->value;?>
);
        theCal.dayNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'monday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'tuesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'wednesday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'thursday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'friday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'saturday');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'sunday');?>
"];
        theCal.monthNames = ["<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'january');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'february');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'march');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'april');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'may');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'june');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'july');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'august');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'september');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'october');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'november');?>
","<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'december');?>
"];
        theCal.relateTo = "end<?php echo $_smarty_tpl->tpl_vars['myprojects']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_project']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_project']->value['index'] : null)]['ID'];?>
";
        theCal.dateFormat = "<?php echo $_smarty_tpl->tpl_vars['settings']->value['dateformat'];?>
";
        theCal.getDatepicker("datepicker_task");
    <?php echo '</script'; ?>
>
<?php }?> 
<?php }
}
