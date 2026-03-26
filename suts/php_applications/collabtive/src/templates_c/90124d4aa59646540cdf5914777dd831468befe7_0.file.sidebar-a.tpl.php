<?php
/* Smarty version 3.1.29, created on 2026-03-26 23:37:53
  from "/var/www/html/templates/standard/sidebar-a.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69c5b541276b65_18182295',
  'file_dependency' => 
  array (
    '90124d4aa59646540cdf5914777dd831468befe7' => 
    array (
      0 => '/var/www/html/templates/standard/sidebar-a.tpl',
      1 => 1774485390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69c5b541276b65_18182295 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_truncate')) require_once '/var/www/html/vendor/smarty/smarty/libs/plugins/modifier.truncate.php';
?>
<div id="content-right" class="overflow-hidden" data-opened="false">
    <!-- the overlay to be displayed when the sidebar is hidden -->
    <div id="sidebar-overlay" class="text-align-center">
       <img src="templates/standard/theme/standard/images/logo-b.png" onclick="toggleSidebar()" alt="" style="cursor: ew-resize" />
    </div>
    <div id="sidebar-content" class="overflow-hidden display-none">
        <!--testplugin-->
        <?php if ($_smarty_tpl->tpl_vars['showConferenceSidebarControls']->value == 1) {?>
            <!--conferenceSidebarControls-->
        <?php }?>
        <div class="content-right-in overflow-hidden">
            <div>
                <h2>
                    <a id="searchtoggle" class="win-up"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'search');?>
</a>
                </h2>
                <form id="search" method="get" action="managesearch.php"  onsubmit="return validateStandard(this,'input_error');">
                    <fieldset>
                        <div class="row">
                            <input type="text" class="text" id="query" name="query"/>
                        </div>
                        <div id="choices"></div>
                        <input type="hidden" name="action" value="search"/>

                        <div id="indicator1" class="display-none">
                            <img src="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/symbols/indicator_arrows.gif" alt=""/>
                        </div>
                        <button type="submit"></button>
                    </fieldset>
                </form>
            </div>
        </div>

        
        <?php if ($_smarty_tpl->tpl_vars['openProjects']->value[0]['ID'] > 0) {?>
            <div class="content-right-in overflow-hidden">
                <h2><a id="quickfindertoggle" class="win-up"
                       href="javascript:blindtoggle('quickfinder');toggleClass('quickfindertoggle','win-up','win-down');"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myprojects');?>
</a></h2>

                <div id="quickfinder">
                    <form>
                        <select style="background-color:#CCC;width:100%;"
                                onchange="window.location='manageproject.php?action=showproject&id='+this.value;">
                            <option><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'chooseone');?>
</option>
                            <?php
$__section_drop_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_drop']) ? $_smarty_tpl->tpl_vars['__smarty_section_drop'] : false;
$__section_drop_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['openProjects']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_drop_0_total = $__section_drop_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_drop'] = new Smarty_Variable(array());
if ($__section_drop_0_total != 0) {
for ($__section_drop_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_drop']->value['index'] = 0; $__section_drop_0_iteration <= $__section_drop_0_total; $__section_drop_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_drop']->value['index']++){
?>
                                <option value="<?php echo $_smarty_tpl->tpl_vars['openProjects']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_drop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_drop']->value['index'] : null)]['ID'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['openProjects']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_drop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_drop']->value['index'] : null)]['name'],40,"...",true);?>
</option>
                            <?php
}
}
if ($__section_drop_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_drop'] = $__section_drop_0_saved;
}
?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="content-spacer"></div>
        <?php }?>
    </div>
</div>
<?php echo '<script'; ?>
 type="text/javascript" src="include/js/components/searchWidgetComponent.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="include/js/views/sidebar.js"><?php echo '</script'; ?>
>
<?php }
}
