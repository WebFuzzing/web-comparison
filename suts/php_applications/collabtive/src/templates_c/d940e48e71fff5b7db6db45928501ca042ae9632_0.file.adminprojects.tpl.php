<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:47
  from "/var/www/html/templates/standard/adminprojects.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b53b6560a5_87728284',
  'file_dependency' => 
  array (
    'd940e48e71fff5b7db6db45928501ca042ae9632' => 
    array (
      0 => '/var/www/html/templates/standard/adminprojects.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:tabsmenue-admin.tpl' => 1,
    'file:forms/addproject.tpl' => 1,
    'file:sidebar-a.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_69c5b53b6560a5_87728284 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('jsload'=>"ajax",'jsload1'=>"tinymce"), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:tabsmenue-admin.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('projecttab'=>"active"), 0, false);
?>


<div id="content-left">
    <div id="content-left-in">
        <div class="projects" id="adminProjects">

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
            <h1><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'administration');?>
<span>/ <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projectadministration');?>
</span></h1>

            <div class="headline">
                <a href="javascript:void(0);" id="acc-projects_toggle" class="win_none" onclick="toggleBlock('acc-projects');"></a>

                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['add']) {?>
                    <div class="wintools">
                        <loader block="adminProjects" loader="loader-project3.gif"></loader>
                        <a class="add" href="javascript:blindtoggle('form_addmyproject');" id="add_myprojects"
                           onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_myprojects','butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">
                            <span><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addproject');?>
</span>
                        </a>
                    </div>
                <?php }?>

                <h2>
                    <img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/projects.png" alt=""/>
                    <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'openprojects');?>

                    <pagination view="adminProjectsView" :pages="pages" :current-page="currentPage"></pagination>
                </h2>

            </div>
            <div class="block" id="acc_projects"> 
                <div id="form_addmyproject" class="addmenue display-none">
                    <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:forms/addproject.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                </div>
                <div class="nosmooth" id="sm_myprojects">
                    <table id="adminprojects" cellpadding="0" cellspacing="0" border="0">

                        <thead>
                        <tr>
                            <th class="a"></th>
                            <th class="b"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'project');?>
</th>
                            <th class="c"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'done');?>
</th>
                            <th class="d text-align-right"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'daysleft');?>
&nbsp;&nbsp;</th>
                            <th class="tools"></th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        </tfoot>

                        
                        <tbody v-for="project in items.open" class="alternateColors" id="proj_{{project.ID}}">

                        <tr v-bind:class="{ 'marker-late': project.islate, 'marker-today': project.istoday }">
                            <td>
                                
                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['del']) {?>
                                
                                    <a class="butn_check"
                                    href="javascript:closeElement('proj_{{project.ID}}','manageproject.php?action=close&amp;id={{project.ID}}',adminProjectsView);"
                                    title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>
"></a>
                                <?php }?>
                            </td>
                            
                            <td>
                                <div class="toggle-in">
                                    <span id="acc_projects_toggle{{project.ID}}" class="acc-toggle"
                                          onclick="javascript:accord_projects.toggle(css('#acc_projects_content{{$index}}'));"></span>
                                    <a href="manageproject.php?action=showproject&amp;id={{*project.ID}}" title="{{*project.name}}">
                                        {{{*project.name | truncate '35'}}}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="statusbar_b">
                                    <div class="complete" id="completed" style="width:{{*project.done}}%;"></div>
                                </div>
                                <span>{{*project.done}}%</span>
                            </td>
                            <td class="text-align-right">{{*project.daysleft}}&nbsp;&nbsp;</td>
                            <td class="tools">

                                
                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['edit']) {?>
                                
                                    <a class="tool_edit" href="javascript:void(0);" onclick="change('manageproject.php?action=editform&amp;id={{*project.ID}}','form_addmyproject');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_addmyproject');" title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edit');?>
"></a>
                                <?php }?>

                                <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['del']) {?>
                                
                                    <a class="tool_del"
                                    href="javascript:confirmDelete('<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'confirmdel');?>
','proj_{{*project.ID}}','manageproject.php?action=del&amp;id={{*project.ID}}',adminProjectsView);" title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>
"></a>
                                <?php }?>
                            </td>
                        </tr>
                        
                        <tr class="acc">
                            <td colspan="5">
                                <div class="accordion_content">
                                    <div class="acc-in">
                                        {{{*project.desc}}}
                                        <p class="tags-miles">
                                            <strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'user');?>
:</strong>
                                        </p>

                                        <div class="inwrapper">
                                            <ul>
                                                <li v-for="member in project.members">
                                                    <div class="itemwrapper" id="iw_{{*project.ID}}_{{*member.ID}}">

                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td class="leftmen" valign="top">
                                                                    <div class="inmenue">
                                                                        <a v-show="member.avatar != ''" class="more"
                                                                           href="javascript:fadeToggle('info_{{project.ID}}_{{*member.ID}}');"></a>
                                                                    </div>
                                                                </td>
                                                                <td class="thumb">
                                                                    <a href="manageuser.php?action=profile&amp;id={{project.members[member].ID}"
                                                                       title="{{project.members[member].name}">
                                                                        <img v-if="member.gender == 'f'"
                                                                             src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['setting']->value['theme'];?>
/images/symbols/user-icon-female.png"
                                                                             alt=""/>
                                                                        <img v-if="member.gender == 'm'"
                                                                             src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/user-icon-male.png"
                                                                             alt=""/>
                                                                    </a>
                                                                </td>
                                                                <td class="rightmen" valign="top">
                                                                    <div class="inmenue">
                                                                        <a class="del"
                                                                           href="manageproject.php?action=deassign&amp;user={{*member.ID}}&amp;id={{*project.ID}}&amp;redir=admin.php?action=projects"
                                                                           title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'deassignuser');?>
"
                                                                           onclick="fadeToggle('iw_{{*project.ID}}_{{*member.ID}}');"></a>
                                                                        <a class="edit"
                                                                           href="admin.php?action=editform&amp;id={{project.members[member].ID}}"
                                                                           title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'edituser');?>
"></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <span class="name">
                                                                        <a href="manageuser.php?action=profile&amp;id={{*member.ID}}"
                                                                           title="{{*member.name}}">
                                                                            {{*member.name}}
                                                                        </a>
                                                                    </span>
                                                                </td>
                                                            <tr/>

                                                        </table>

                                                        <div v-show="member.avatar != ''" class="moreinfo display-none"
                                                             id="info_{{*project.ID}}_{{*member.ID}">
                                                            <img src="thumb.php?pic=files/{$cl_config}/avatar/{{project.members[member].avatar}}&amp;width=82"
                                                                 alt="" onclick="fadeToggle('info_{{project.ID}}_{{project.members[member].ID}}');"/>
																		<span class="name">
																			<a href="manageuser.php?action=profile&amp;id={{*member.ID}}">
                                                                                {{*member.name}}
                                                                            </a>
																		</span>
                                                        </div>
                                                    </div>
                                                    <!--itemwrapper end-->
                                                </li>
                                            </ul>
                                        </div>
                                        <!--inwrapper End-->
                                        
                                        <p class="tags-miles"> <!--assign users-->
                                            <strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'adduser');?>
:</strong>
                                        </p>
                                        
                                        <div class="inwrapper">

                                            <form class="main" method="post"
                                                  action="manageproject.php?action=assign&amp;id={{*project.ID}}&redir=admin.php?action=projects&mode=useradded"
                                                  onsubmit="return validateCompleteForm(this);">
                                                <fieldset>

                                                    
                                                    <div class="row">
                                                        <label for="addtheuser"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'user');?>
</label>
                                                        <select name="user" id="addtheuser">
                                                            <option value=""><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
                                                            <?php
$__section_usr_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_usr']) ? $_smarty_tpl->tpl_vars['__smarty_section_usr'] : false;
$__section_usr_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['users']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_usr_0_total = $__section_usr_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_usr'] = new Smarty_Variable(array());
if ($__section_usr_0_total != 0) {
for ($__section_usr_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_usr']->value['index'] = 0; $__section_usr_0_iteration <= $__section_usr_0_total; $__section_usr_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_usr']->value['index']++){
?>
                                                                <option value="<?php echo $_smarty_tpl->tpl_vars['users']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_usr']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_usr']->value['index'] : null)]['ID'];?>
"><?php echo $_smarty_tpl->tpl_vars['users']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_usr']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_usr']->value['index'] : null)]['name'];?>
</option>
                                                            <?php
}
}
if ($__section_usr_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_usr'] = $__section_usr_0_saved;
}
?>
                                                        </select>
                                                    </div>

                                                    <div class="row-butn-bottom">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" onfocus="this.blur();"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addbutton');?>
</button>
                                                    </div>

                                                </fieldset>
                                            </form>
                                        </div>
                                        <!--assign users end-->
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!--Projects End-->
                    
                    <!--Doneprojects-->
                    <div id="doneblock" class="doneblock display-none">

                        <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                               onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
                            <tr>
                                <td class="a"></td>
                                <td class="b"><span id="toggle-done"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'closedprojects');?>
</span></td>
                                <td class="c"></td>
                                <td class="days"></td>
                                <td class="tools"></td>
                            </tr>
                        </table>

                        <div class="toggleblock">

                            <table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">

                                <tbody v-for="closedProject in items.closed" class="alternateColors" id="proj_{{*closedProject.ID}}">
                                <tr>
                                    <td class="a">
                                        
                                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['add']) {?>
                                        
                                            <a class="butn_checked" href="manageproject.php?action=open&amp;id={{*closedProject.ID}}"
                                            title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'open');?>
"></a>
                                        <?php }?>
                                    </td>
                                    
                                    <td class="b">
                                        <div class="toggle-in">
                                            <a href="manageproject.php?action=showproject&amp;id={{*closedProject.ID}}"
                                               title="{{{*closedProject.name}}}">
                                                {{{*closedProject.name}}}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="c">

                                    </td>
                                    <td class="days text-align-right">{{*closedProject.daysleft}}&nbsp;&nbsp;</td>
                                    <td class="tools">
                                        
                                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['del']) {?>
                                        
                                            <a class="tool_del"
                                            href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{{*closedProject.ID}\',\'manageproject.php?action=del&amp;id={{*closedProject.ID}\')');"
                                            title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'delete');?>
"></a>
                                        <?php }?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--toggleblock End-->
                    </div>
                    <!--doneblock end-->
                </div>
                <!--smooth end-->
                
                <div class="tablemenue">
                    <div class="tablemenue-in">
                        
                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['projects']['add']) {?>
                        
                            <a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects"
                            onclick="toggleClass('add_myprojects','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">
                        <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'addproject');?>

                            </a>
                        <?php }?>
                        
                        <a class="butn_link" href="javascript:blindtoggle('doneblock');" id="donebutn"
                           onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
                            <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'closedprojects');?>

                        </a>
                    </div>
                </div>
            </div> 
            <!-- block END Doneprojects End-->

            <div class="content-spacer"></div>
            <!--projectTemplates-->
        </div>
        <!--Projects END-->


    </div>
    <!--content-left-in END-->
</div> <!--content-left END-->

<?php echo '<script'; ?>
 type="text/javascript" src="include/js/accordion.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="include/js/views/adminProjectsView.min.js"><?php echo '</script'; ?>
>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sidebar-a.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
