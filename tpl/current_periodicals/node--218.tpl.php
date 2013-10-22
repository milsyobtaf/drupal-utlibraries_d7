<!-- I hate having to do this, but for now I do to get the links to work reliably -->

<?php $url_prefix = 'd7/fal/about/holdings/currentperiodicals'; ?>

<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <header>
    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php if ($region['group_nav'] = render($region['group_nav'])): ?>
      <div class="group-nav">
        <?php print $region['group_nav']; ?>
      </div>
    <?php endif; ?>
        <?php print render($title_suffix); ?>
  </header>

  <?php if ($region['sidebar_first'] = render($region['sidebar_first'])): ?>
    <aside class="sidebar sidebar-first">
      <?php print $region['sidebar_first']; ?>
    </aside>
  <?php endif; ?>

  <div class="content content-group current-periodicals current-periodicals-full"<?php print $content_attributes; ?>>
  <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
  <ul class="current-periodicals-list">
  <?php
    include("admin/config.php");
    include("admin/functions.php");
    
    //include("header.php");
    
    $connection = connect();
    
           	$sql = "
    		SELECT title, ugl_serials_id
    		FROM ugl_serials 
    		WHERE display_flag = '1'
    		ORDER BY title
    		";
    		$result = @mysql_query($sql, $connection) or die("no go");
            	while ($row = mysql_fetch_array($result)) {
            	$title = $row['title'];
            	$ugl_serials_id = $row['ugl_serials_id'];
    		$display .= "<li><a href=\"/$url_prefix/view_full_rec.php?ugl_serials_id=$ugl_serials_id\">$title</a></li>
    		";
    		}
    
    echo "
    
    $display
    	"; 
  ?>
  </ul>
  </div>
  <?php if ($region['sidebar_second'] = render($region['sidebar_second'])): ?>
    <aside class="sidebar sidebar-second">
      <?php print $region['sidebar_second']; ?>
    </aside>
  <?php endif; ?>

  </div>
</article>