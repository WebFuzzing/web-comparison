<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:45
  from "/var/www/html/templates/standard/userProfileTimetracker.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b539638978_43842420',
  'file_dependency' => 
  array (
    'cfddda662d62ae9f2c589e8a7fbebf4ec363ea72' => 
    array (
      0 => '/var/www/html/templates/standard/userProfileTimetracker.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:filterreport.tpl' => 1,
  ),
),false)) {
function content_69c5b539638978_43842420 ($_smarty_tpl) {
?>
<!-- container for the userTimetrackerAccordeon accordeon -->
<div class="timetrack" id="userTimetracker">
    <div class="headline">
        <!-- toggle for the blockaccordeon-->
        <a href="javascript:void(0);" id="userTimetracker_toggle" class="win_none" onclick = ""></a>
        <div class="wintools">
            <div class="export-main">
                <a class="export"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'export');?>
</span></a>
                <div class="export-in"  style="width:46px;left: -46px;"> 
                    <a class="pdf" href="managetimetracker.php?action=userpdf&amp;id=<?php echo $_smarty_tpl->tpl_vars['project']->value['ID'];
if ($_smarty_tpl->tpl_vars['start']->value != '' && $_smarty_tpl->tpl_vars['end']->value != '') {?>&amp;start=<?php echo $_smarty_tpl->tpl_vars['start']->value;?>
&amp;end=<?php echo $_smarty_tpl->tpl_vars['end']->value;
}
if ($_smarty_tpl->tpl_vars['usr']->value > 0) {?>&amp;usr=<?php echo $_smarty_tpl->tpl_vars['usr']->value;
}
if ($_smarty_tpl->tpl_vars['task']->value > 0) {?>&amp;task=<?php echo $_smarty_tpl->tpl_vars['task']->value;
}
if ($_smarty_tpl->tpl_vars['fproject']->value > 0) {?>&amp;project=<?php echo $_smarty_tpl->tpl_vars['fproject']->value;
}?>"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'pdfexport');?>
</span></a>
                    <a class="excel" href="managetimetracker.php?action=userxls&amp;id=<?php echo $_smarty_tpl->tpl_vars['project']->value['ID'];
if ($_smarty_tpl->tpl_vars['start']->value != '' && $_smarty_tpl->tpl_vars['end']->value != '') {?>&amp;start=<?php echo $_smarty_tpl->tpl_vars['start']->value;?>
&amp;end=<?php echo $_smarty_tpl->tpl_vars['end']->value;
}
if ($_smarty_tpl->tpl_vars['usr']->value > 0) {?>&amp;usr=<?php echo $_smarty_tpl->tpl_vars['usr']->value;
}
if ($_smarty_tpl->tpl_vars['task']->value > 0) {?>&amp;task=<?php echo $_smarty_tpl->tpl_vars['task']->value;
}
if ($_smarty_tpl->tpl_vars['fproject']->value > 0) {?>&amp;project=<?php echo $_smarty_tpl->tpl_vars['fproject']->value;
}?>"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'excelexport');?>
</span></a>
                </div>
            </div>

            <div class="toolwrapper">
                <a class="filter" href="javascript:blindtoggle('form_filter');" id="filter_report" onclick="toggleClass(this,'filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');"><span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'filterreport');?>
</span></a>
            </div>
        </div>

        <h2>
            <img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/timetracker.png" alt="" /><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'report');?>

        </h2>
    </div>

    <!-- contentSlide for the blockAccordeon -->
    <div class="block blockaccordion_content overflow-hidden display-none" > 
        <div id = "form_filter" class="addmenue display-none">
            <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:filterreport.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        </div>

        <div class="nosmooth" id="sm_report">
            <table cellpadding="0" cellspacing="0" border="0" id="userTimetrackerAccordeon">
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'project');?>
</th>
                    <th class="cf"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'day');?>
</th>
                    <th class="cf"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'started');?>
</th>
                    <th class="cf"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ended');?>
</th>
                    <th class="e text-align-right"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'hours');?>
&nbsp;&nbsp;</th>
                    <th class="tools"></th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td colspan="6"></td>
                </tr>
                </tfoot>

                

                    <tbody v-for="tracker in items" class="alternateColors" id="track_{{*tracker.ID}}">
                    <tr>
                        <td></td>
                        <td>
                            <div class="toggle-in">
                                <span class="acc-toggle" onclick="javascript:accord_tracker.toggle(css('#userTimetrackerAccordeon_content{{$index}}'))"></span>
                                <a href = "managetimetracker.php?action=showproject&amp;id={{*tracker.project}}" title="{{*tracker.pname}}">
                                    {{{*tracker.pname | truncate '30'}}}
                                </a>
                            </div>
                        </td>
                        <td>{{*tracker.daystring | truncate '12'}}</td>
                        <td>{{*tracker.startstring | truncate '12'}}</td>
                        <td>{{*tracker.endstring | truncate '12'}}</td>
                        <td class="text-align-right">{{*tracker.hours | truncate '12' }}&nbsp;&nbsp;</td>
                        <td class="tools">
                            
                            <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['timetracker']['edit']) {?>
                            
                                <a class="tool_edit"
                                href="managetimetracker.php?action=editform&amp;tid={$tracker[track].ID}&amp;id={$tracker[track].project}"
                                title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>
"></a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['timetracker']['del']) {?>
                            
                                <a class="tool_del"
                                   href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'track_{$tracker[track].ID}\',\'managetimetracker.php?action=del&amp;tid={$tracker[track].ID}&amp;id={$project.ID}\')');"
                                   title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>
"></a>
                            <?php }?>
                        </td>
                    </tr>
                    
                    <tr class="acc">
                        <td colspan="7">
                            <div class="accordion_content">
                                <div class="acc-in">
                                    <template v-if="tracker.comment != ''">
                                        <strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'comment');?>
:</strong><br />{{*tracker.comment}}
                                    </template>
                                    <template v-if="tracker.task > 0">
                                        <p class="tags-miles">
                                            <strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'task');?>
:</strong><br />
                                            <a href = "managetask.php?action=showtask&amp;tid={$tracker[track].task}&amp;id={{*tracker.project}}">{{*tracker.tname}}</a>
                                        </p>
                                    </template>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                

                <tbody class="tableend">
                <tr>
                    <td></td>
                    <td colspan="4"><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'totalhours');?>
:</strong></td>
                    <td class="text-align-right"><strong><?php echo $_smarty_tpl->tpl_vars['totaltime']->value;?>
</strong>&nbsp;&nbsp;</td>
                    <td class="tools"></td>
                </tr>
                </tbody>
            </table>

        </div> 

        <div class="tablemenue">
            <div class="tablemenue-in">
                <a class="butn_link" href="javascript:blindtoggle('form_filter');" id="filter_butn" onclick="toggleClass('filter_report','filter-active','filter');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'filterreport');?>
</a>
            </div>
        </div>
    </div> 
</div> 
<div class="padding-bottom-two-px"></div><?php }
}
