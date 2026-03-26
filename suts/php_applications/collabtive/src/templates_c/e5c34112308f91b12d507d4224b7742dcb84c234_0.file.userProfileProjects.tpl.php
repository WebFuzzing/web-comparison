<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:45
  from "/var/www/html/templates/standard/userProfileProjects.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b539c24562_62269356',
  'file_dependency' => 
  array (
    'e5c34112308f91b12d507d4224b7742dcb84c234' => 
    array (
      0 => '/var/www/html/templates/standard/userProfileProjects.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69c5b539c24562_62269356 ($_smarty_tpl) {
?>
    <div class="projects" id="userProjects">
        <div class="headline">
            <a href="javascript:void(0);" id="userProjects_toggle" class="win_none" onclick="">
                <h2>
                    <img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/projects.png" alt=""/><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'projects');?>

                    <pagination view="userProfileProjectsView" :pages="pages" :current-page="currentPage"></pagination>
                </h2>
            </a>
            <div class="wintools">
                <div class="progress display-none float-left" id="progressuserProjects" >
                    <img src="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/loader-project3.gif"/>
                </div>
            </div>

        </div>

        <!-- contentSlide for the blockAccordeon -->
        <div class="block blockaccordion_content overflow-hidden display-none" >

            <table cellpadding="0" cellspacing="0" border="0" id="userProjectsAccordeon">
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'project');?>
</th>
                    <th class="c"></th>
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

                
                <tbody v-for="project in items" class="alternateColors" id="proj_{{*project.ID}">
                <tr v-bind:class="{ 'marker-late': project.islate, 'marker-today': project.istoday }">
                    <td>
                        
                        <?php if ($_smarty_tpl->tpl_vars['userpermissions']->value['admin']['add']) {?>
                        
                            <a class="butn_check" href="javascript:closeElement('proj_{{*project.ID}}','manageproject.php?action=close&amp;id={{*project.ID}}');" title="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'close');?>
"></a>
                        <?php }?>
                    </td>
                    
                    <td>
                        <div class="toggle-in">
                            <span class="acc-toggle"
                                  onclick="javascript:accord_projects.toggle(css('#userProjectsAccordeon_content{{$index}}'));"></span>
                            <a href="manageproject.php?action=showproject&amp;id={{*project.ID}}" title="{{*project.name}}">
                                {{{*project.name | truncate '35' }}}
                            </a>
                        </div>
                    </td>
                    <td></td>
                    <td class="text-align-right">{{*project.daysleft}}&nbsp;&nbsp;</td>
                    <td class="tools">

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
                                            <div class="itemwrapper"
                                                 v-bind:id="'iw_'+project.ID+'_'+member.ID">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="leftmen" valign="top">
                                                            <div class="inmenue">
                                                                <a v-if="member.avatar != ''" class="more"
                                                                   href="javascript:fadeToggle('info_{{*project.ID}_{{*member.ID}}');"></a>
                                                            </div>
                                                        </td>
                                                        <td class="thumb">
                                                            <a href="manageuser.php?action=profile&amp;id={{*member.ID}}" title="{{*member.name}}">
                                                                <img src="./templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/user-icon-male.png" alt=""/>
                                                            </a>
                                                        </td>
                                                        <td class="rightmen" valign="top">
                                                            <div class="inmenue">
                                                                <a class="del"
                                                                   href="manageproject.php?action=deassign&amp;user={{*member.ID}}&amp;id={{*project.ID}}&amp;redir=admin.php?action=projects" title="{#deassignuser#}" onclick="fadeToggle('iw_{{*project.ID}}_{{*member.ID}}');"></a>
                                                                <a class="edit" href="admin.php?action=editform&amp;id={{*member.ID}}" title="{#edituser#}"></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <span class="name">
                                                                <a href="manageuser.php?action=profile&amp;id={{*member.ID}}" title="{{*member.name}}">
                                                                    {{*member.name}}
                                                                </a>
                                                            </span>
                                                        </td>
                                                    <tr/>
                                                </table>

                                                <template v-if="member.avatar != ''">
                                                <div class="moreinfo-wrapper">
                                                    <div class="moreinfo display-none" id="info_{{*project.ID}_{{*member.ID}}">
                                                        <img src="thumb.php?pic=files/{$cl_config}/avatar/{{*member.avatar}}&amp;width=82" alt="" onclick="fadeToggle('info_{{*project.ID}_{{*project.members[member].ID}');"/>
                                                        <span class="name">
                                                            <a href="manageuser.php?action=profile&amp;id={{*member.ID}}">{{*member.name}}</a></span>
                                                    </div>
                                                </div>
                                                </template>
                                            </div>
                                            <!--itemwrapper end-->
                                        </li>
                                    </ul>
                                </div>
                                <!--inwrapper End-->
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                
            </table>

            <div class="tablemenue"></div>
        </div> <!--block END-->
    </div>
    <div class="padding-bottom-two-px"></div>
    
<?php }
}
