<?php /* Smarty version Smarty-3.1-DEV, created on 2013-02-08 11:53:26
         compiled from "/home/petr/dev/test-mongo-php/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7733417725114d926c29a42-65644832%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9d0721e42fec9cfc2f3ac0773d6994dbeba8020a' => 
    array (
      0 => '/home/petr/dev/test-mongo-php/templates/index.tpl',
      1 => 1360320130,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7733417725114d926c29a42-65644832',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'items' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5114d926c646c7_60993113',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5114d926c646c7_60993113')) {function content_5114d926c646c7_60993113($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>NewsML G2</title>
</head>
<body>
    <h1>Total Items: <?php echo count($_smarty_tpl->tpl_vars['items']->value);?>
</h1>
    <ol>
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
        <li><?php echo $_smarty_tpl->tpl_vars['item']->value->headline;?>
</li>
        <?php } ?>
    </ol>
</body>
</html>
<?php }} ?>