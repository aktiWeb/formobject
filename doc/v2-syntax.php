<?php

// V2 will remove validation completely, setting values to forms will be pluggable

// Form::setRequest() will be optional but supported
// Form::setModel($someModel) will pass this to a handler $handler->fill(Form $form, $model)

?>


// Simple form (bootstrap.php)

<? $form = Form::create('name')->setRules($rules) ?>
<? $form->text('name','Please enter your name')->value('Billy') ?>
<? $form->text('surname','Please enter your surname')->value('Talent') ?>
<? $form->checkbox('rememberMe','Remember Me') ?>
<? $form->booleanRadio('rememberMyRadio','Remember my radio')->stringForTrue('Remember my radio')->stringForFalse('Forget my radio') ?>
<? $form->textarea('message','Message') ?>
<?= $form ?>


// Fieldset (fieldset.php)

<? $form = Form::create('name')->setRules($rules) ?>
<? $container = $form->fieldset('Group One');
<? $container->text('name','Please enter your name')->value('Jennifer') ?>
<? $container->text('surname','Please enter your surname')->value('Batten') ?>
<? $container->checkbox('rememberMe','Remember Me') ?>
<? $container->booleanRadio('rememberMyRadio','Remember my radio')->stringForTrue('Remember my radio')->stringForFalse('Forget my radio') ?>
<? $container2 = $form->fieldset('Group Two');
<? $container2->select('category','User Category')->setSrc($categories) ?>

<?= $form ?>

// No more forced controller usage

