<?php echo $open ?>

<?php foreach($fields as $field) { ?>

<p>

<label for="<?php echo $field->get_name() ?>">
<?php echo $field->get_label() ?>
</label>

<?php echo $field->render() ?>

</p>

<?php if(isset($errors[$field->get_name()])) { ?>
<p><?php echo $field->render_error($errors); ?></p>
<?php } ?>

<?php } ?>

<?php echo $close ?>
