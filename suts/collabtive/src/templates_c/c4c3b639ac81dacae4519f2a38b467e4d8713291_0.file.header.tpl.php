<?php
/* Smarty version 3.1.29, created on 2026-04-27 01:56:02
  from "/var/www/html/templates/standard/header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_69eea612aa5936_38815797',
  'file_dependency' => 
  array (
    'c4c3b639ac81dacae4519f2a38b467e4d8713291' => 
    array (
      0 => '/var/www/html/templates/standard/header.tpl',
      1 => 1504043104,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header_main.tpl' => 1,
  ),
),false)) {
function content_69eea612aa5936_38815797 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, 'lng.conf', "strings", 96);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 @ <?php echo $_smarty_tpl->tpl_vars['settings']->value['name'];?>
</title>
    <link rel="shortcut icon" href="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/images/favicon.ico" type="image/x-icon"/>
    <?php if ($_smarty_tpl->tpl_vars['treeView']->value == "treeView" && $_smarty_tpl->tpl_vars['loggedin']->value) {?>
        <link rel="stylesheet" href="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/css/dtree.css" type="text/css"/>
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/dtree.min.js"><?php echo '</script'; ?>
>
    <?php }?>
    <?php if ((($tmp = @$_smarty_tpl->tpl_vars['jsload']->value)===null||$tmp==='' ? '' : $tmp) == "ajax") {?>
    
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/velocity.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/vue.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/ajax.min.js"><?php echo '</script'; ?>
>

        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/viewManager.min.js"><?php echo '</script'; ?>
>
        

        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/components/paginationComponent.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/components/progressComponent.min.js"><?php echo '</script'; ?>
>
        <!--conferenceScripts-->
        <!--taskCommentsScripts-->
        <!--autoTimetrackerScripts-->

        

        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/systemMessage.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/jsval.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript">
            function _jsVal_Language() {
                this.err_enter = "<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'wrongfield');?>
";
                this.err_form = "<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'wrongfields');?>
";
                this.err_select = "<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'wrongselect');?>
";
            }
        <?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/mycalendar.min.js"><?php echo '</script'; ?>
>
    
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['jsload3']->value == "lightbox") {?>
        <link rel="stylesheet" href="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/css/lytebox.css" type="text/css"/>
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/lytebox.js"><?php echo '</script'; ?>
>
    <?php }?>
    <link rel="stylesheet" type="text/css" href="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/css/style_main.css"/>
    <link rel="stylesheet" type="text/css" href="templates/<?php echo $_smarty_tpl->tpl_vars['settings']->value['template'];?>
/theme/<?php echo $_smarty_tpl->tpl_vars['settings']->value['theme'];?>
/css/style_helpers.css"/>


    <?php if ($_smarty_tpl->tpl_vars['jsload1']->value == "tinymce") {?>
    
        <?php echo '<script'; ?>
 type="text/javascript" src="include/js/tiny_mce/tiny_mce.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript">
            //	theme_advanced_statusbar_location : "bottom",
            function initTinyMce() {
                tinyMCE.init({
                    mode: "textareas",
                    theme: "advanced",
                    language: "<?php echo $_smarty_tpl->tpl_vars['locale']->value;?>
",
                    width: "450px",
                    height: "250px",
                    plugins: "inlinepopups,style,advimage,advlink,xhtmlxtras,safari,template",
                    theme_advanced_buttons1: "bold,italic,underline,|,fontsizeselect,forecolor,|,bullist,numlist,|,link,unlink,image",
                    theme_advanced_buttons2: "",
                    theme_advanced_buttons3: "",
                    theme_advanced_toolbar_location: "top",
                    theme_advanced_toolbar_align: "left",
                    theme_advanced_path: false,
                    extended_valid_elements: "a[name|href|target|title],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name],font[face|size|color|style],span[class|align|style]",
                    theme_advanced_statusbar_location: "bottom",
                    theme_advanced_resizing: true,
                    theme_advanced_resizing_use_cookie: false,
                    theme_advanced_resizing_min_width: "450px",
                    theme_advanced_resizing_max_width: "600px",
                    theme_advanced_resize_horizontal: false,
                    force_br_newlines: true,
                    cleanup: true,
                    cleanup_on_startup: true,
                    force_p_newlines: false,
                    convert_newlines_to_brs: false,
                    forced_root_block: false,
                    external_image_list_url: 'manageajax.php?action=jsonfiles&id=<?php echo $_smarty_tpl->tpl_vars['project']->value['ID'];?>
',
                    setup: function (editor) {
                        editor.onChange.add(function () {
                            tinyMCE.triggerSave();
                        });
                    }

                });
            }
            window.addEventListener("load",initTinyMce);
        <?php echo '</script'; ?>
>
    
    <?php }?>
</head>
<body>

<?php if ($_smarty_tpl->tpl_vars['showheader']->value != "no") {?>
    <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header_main.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
}
