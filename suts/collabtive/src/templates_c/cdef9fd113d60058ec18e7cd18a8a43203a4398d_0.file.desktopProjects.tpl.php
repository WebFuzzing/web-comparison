<?php
/* Smarty version 3.1.29, created on 2026-04-27 01:56:04
  from "/var/www/html/templates/standard/desktopProjects.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69eea614411725_78101358',
  'file_dependency' => 
  array (
    'cdef9fd113d60058ec18e7cd18a8a43203a4398d' => 
    array (
      0 => '/var/www/html/templates/standard/desktopProjects.tpl',
      1 => 1495584515,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:forms/addproject.tpl' => 1,
  ),
),false)) {
function content_69eea614411725_78101358 ($_smarty_tpl) {
?>
<div id="desktopprojects" class="projects padding-bottom-two-px">
    <div class="headline">
        <a href="javascript:void(0);" id="projecthead_toggle" class="win_block" onclick=""></a>

        <div class="wintools">
            <loader block="desktopprojects" loader="loader-project3.gif"></loader>
        </div>

        <h2><img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/projects.png" alt=""/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myprojects');?>

            
            <pagination view="projectsView" :pages="pages" :current-page="currentPage"></pagination>
        </h2>

    </div>
    <div class="block blockaccordion_content overflow-hidden display-none" id="projecthead">
        <div id="form_addmyproject" class="addmenue display-none">
            <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:forms/addproject.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('myprojects'=>"1"), 0, false);
?>

        </div>
        <div class="nosmooth" id="sm_deskprojects">
            <table cellpadding="0" cellspacing="0" border="0" id="desktopProjectsTable" v-cloak>
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b" class="cursor-pointer"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'project');?>
</th>
                    <th class="c" class="cursor-pointer"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'done');?>
</th>
                    <th class="d" class="text-align-right"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'daysleft');?>
&nbsp;&nbsp;</th>
                    <th class="tools"></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="5"></td>
                </tr>
                </tfoot>

                
                <tbody v-for="item in items.open"
                       v-bind:id="'proj_'+item.ID" class="alternateColors">

                <tr v-bind:class="{ 'marker-late': item.islate, 'marker-today': item.istoday }">
                    <td>
                        
                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['close']) {?>
                        
                            <a class="butn_check"
                            v-bind:href="'javascript:closeElement(\'proj_'+item.ID+'\',\'manageproject.php?action=close&amp;id='+item.ID+'\',projectsView);'"
                            title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>
"></a>
                        
                        <?php }?>
                        
                    </td>
                    <td>
                        <div class="toggle-in">
                                <span
                                        v-bind:id="'desktopprojects_toggle'+item.ID"
                                        class="acc-toggle"
                                        :onclick="'accord_projects.toggle(css(\'#desktopprojects_content'+$index+'\'));'"></span>
                            <a v-bind:href="'manageproject.php?action=showproject&amp;id=' + item.ID"
                               v-bind:title=item.name>
                                {{{item.name | truncate '35' }}}
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="statusbar_b">
                            <div class="complete" id="completed"
                                 v-bind:style="'width:' + item.done + '%'"></div>
                        </div>
                        <span>{{item.done}}%</span>
                    </td>
                    <td class="text-align-right">{{item.daysleft}}&nbsp;&nbsp;</td>
                    <td class="tools">
                        
                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['edit']) {?>
                        
                            <a class="tool_edit"
                               v-bind:href="'javascript:change(\'manageproject.php?action=editform&amp;id='+item.ID+'\',\'form_addmyproject\');blindtoggle(\'form_addmyproject\');'"
                               title="{#edit#}"></a>
                        
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['del']) {?>
                        
                            <a class="tool_del"
                            v-bind:href="'javascript:confirmDelete(\'<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'confirmdel');?>
\',\'proj_'+item.ID+'\',\'manageproject.php?action=del&amp;id='+item.ID+'\',projectsView);'"
                            title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>
"></a>
                        
                        <?php }?>
                        

                    </td>
                </tr>

                <tr class="acc">
                    <td colspan="5">
                        <div class="accordion_content">
                            <div class="acc-in">
                                <div class="message-in-fluid" v-html="item.desc">
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                
            </table>

            
            <div id="projectsDoneblock" class="projects display-none">
                <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                       onclick="blindtoggle('projectsDoneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
                    <tr>
                        <td class="a"></td>
                        <td class="b" class="cursor-pointer"><span id="toggle-done"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'closedprojects');?>
</span></td>
                        <td class="c" class="cursor-pointer"></td>
                        <td class="d" class="text-align-right"></td>
                        <td class="tools"></td>
                    </tr>
                </table>

                
                <div class="toggleblock">
                    <table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">

                        <tbody v-for="item in items.closed" class="alternateColors"
                               v-bind:id="'proj_'+item.ID">
                        <tr>
                            <td class="a">
                                
                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['add']) {?>
                                    <a class="butn_checked"
                                       v-bind:href="'manageproject.php?action=open&amp;id='+ item.ID"
                                       title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'open');?>
"></a>
                                <?php }?>
                                
                            </td>
                            <td class="b">
                                {{item.name | truncate '40' }}
                            </td>
                            <td></td>
                            <td></td>
                            <td class="tools">
                                
                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['del']) {?>
                                
                                    <a class="tool_del"
                                    v-bind:href="'javascript:confirmDelete(\'<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'confirmdel');?>
\',\'proj_'+item.ID+'\',\'manageproject.php?action=del&amp;id='+item.ID+'\',projectsView);'"
                                    title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>
"></a>
                                
                                <?php }?>
                                
                            </td>
                        </tr>
                        </tbody>
                        
                    </table>
                </div> 
            </div> 
            

            
            <?php if ($_smarty_tpl->tpl_vars['openProjectnum']->value < 1 && $_smarty_tpl->tpl_vars['userpermissions']->value['projects']['add']) {?>
                <?php echo '<script'; ?>
 type="text/javascript">
                    toggleClass('sm_deskprojects', 'smooth', 'nosmooth');
                    blindtoggle('form_addmyproject');
                <?php echo '</script'; ?>
>
            <?php }?>

            <div class="tablemenue">
                <div class="tablemenue-in">
                    <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['add']) {?>
                        <a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects"
                           onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_deskprojects','smooth','nosmooth');"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addproject');?>
</a>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['closedProjectnum']->value > 0) {?>
                        <a class="butn_link" href="javascript:blindtoggle('projectsDoneblock');" id="donebutn"
                           onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'closedprojects');?>
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
    theCal.relateTo = "endP";
    theCal.dateFormat = "<?php echo $_smarty_tpl->tpl_vars['settings']->value['dateformat'];?>
";
    theCal.getDatepicker("add_project");
<?php echo '</script'; ?>
>



<?php }
}
