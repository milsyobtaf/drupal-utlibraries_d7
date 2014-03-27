<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Article" it would result in "node-article". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. page, article, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 */
?>

<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <header>
    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a></h2>
    <?php endif; ?>
      <?php print render($title_suffix); ?>
  </header>

  <?php if ($region['sidebar_first'] = render($region['sidebar_first'])): ?>
    <aside class="sidebar sidebar-first">
      <?php print $region['sidebar_first']; ?>
    </aside>
  <?php endif; ?>

  <div class="content content-group calendar-event calendar-event-public"<?php print $content_attributes; ?>>
    <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
    <?php
      // We hide the content here so we can print it by individual field later
      hide($content);
      ?>

      <?php // Beginning of custom content output ?>
      <div class="cal-display-details"><!-- Beginning of calendar event detail display -->
        <p><?php print render($content['field_cal_event_date']); ?></p>
        <!-- Checking to see if this is an online event, one of the two unmappable options. If yes, print out a notice about it being online -->
        <?php if ($content['field_cal_event_location']['#items']['0']['value'] == 'online'):
          print '<p class="cal-display-details-location">This is an online event</p>';
        ?>
        <!-- Checking to see if this is an other event, one of the two unmappable options. If yes, do nothing, since it doesn't make sense to print "This is an other event". -->
        <?php elseif ($content['field_cal_event_location']['#items']['0']['value'] == 'other'): ?>
          <p class="cal-display-details-location">At the<?php print render($content['field_cal_event_location_other']) . 'in' . render($content['field_cal_event_room']); ?></p>
        <?php else: ?>
          <p class="cal-display-details-location">At the<?php print render($content['field_cal_event_location']) . 'in' . render($content['field_cal_event_room']); ?></p>
      <?php endif; ?>
      <?php print render($content['field_cal_event_image']); ?>
      <?php print render($content['field_cal_event_description']); ?>
      </div><!-- End of calendar event detail display -->

      <!-- Beginning of metadata and map display -->
      <div class="cal-display-metadata">

        <!-- Checks to see if Target Audience is empty, if not it prints the values -->
        <?php if (!empty($content['field_cal_campus_target_au'])): ?>
          <div class="cal-display-target-audience">
            <h4>Audience</h4>
            <?php print render($content['field_cal_campus_target_au']); ?>
          </div>
        <?php endif; ?>

          <div class="cal-display-event-category">
            <h4>Event Type</h4>
            <?php print render($content['field_cal_event_category']); ?>
          </div>

        <!-- Checking to see if this is an online event, one of the two unmappable options. If yes, print out a notice about it being online -->
        <?php if ($content['field_cal_event_location']['#items']['0']['value'] == 'online'):
          print 'This is an online-only event';
          ?>
        <!-- Checking to see if this is an other event, one of the two unmappable options. If yes, do nothing, since it doesn't make sense to print "This is an other event". -->
        <?php elseif ($content['field_cal_event_location']['#items']['0']['value'] == 'other'): ?>
        <?php else: ?>
        <div class="cal-event-location-map">
          <h4>Getting to the<?php print render($content['field_cal_event_location']); ?></h4>
          <a href="http://www.google.com/maps/place/<?php print urlencode(render($content['field_cal_event_location']['0'])); ?>"target="_blank">
            <img src="http://www.utexas.edu/maps/main/buildings/graphics/insets/<?php print render($content['field_cal_event_location']['#items']['0']['value']);?>_inset.gif" />
          </a>
          <p><a href="http://www.google.com/maps/place/<?php print urlencode(render($content['field_cal_event_location']['0'])); ?>" target="_blank">Click Here To Open Map In A New Page</a></p>
        </div>
        <?php endif; ?>
      </div><!-- End of calendar image and map display -->


    <?php if ($region['content_suffix'] = render($region['content_suffix'])): ?>
      <section class="content-suffix">
        <?php print $region['content_suffix']; ?>
      </section>
    <?php endif; ?>
  </div>

  <?php if ($region['sidebar_second'] = render($region['sidebar_second'])): ?>
    <aside class="sidebar sidebar-second">
      <?php print $region['sidebar_second']; ?>
    </aside>
  <?php endif; ?>

  <?php print render($content['links']); ?>

</article>
