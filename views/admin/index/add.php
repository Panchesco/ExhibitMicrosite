<?php
queue_css_file("exhibit-microsite");
$head = [
  "bodyclass" => "exhibit-microsite primary",
  "title" => html_escape(__("Exhibit Microsites | Add Microsite")),
];
echo head($head);
?>

<?php echo flash(); ?>
<?php echo $form; ?>
<?php echo foot(); ?>
