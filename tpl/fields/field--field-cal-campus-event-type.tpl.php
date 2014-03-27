<?php
/**
 * Field Formatter to put commas between items
 */
?>

<?php foreach ($items as $delta => $item) : ?>
  <span class="field-item <?php print $delta % 2 ? 'odd' : 'even'; ?>"<?php print $item_attributes[$delta]; ?>>
    <?php
      print render($item);
      // Add comma if not last item
      if ($delta < (count($items) - 1)) {
        print ',';
      }
    ?>
  </span>
<?php endforeach; ?>
