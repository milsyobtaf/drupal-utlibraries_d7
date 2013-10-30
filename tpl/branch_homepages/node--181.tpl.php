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



<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> branch-front clearfix"<?php print $attributes; ?>>

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

  <div class="content"<?php print $content_attributes; ?>>

    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['links']);
      hide($content['field_url_short_title']);
      print render($content);
    ?>

    <div class="frontpage-columns">
      <div class="frontpage-column">
      <div class="frontpage-block">
        <h3 class="frontpage-block-title gradient">Recent Arrivals</h3>
          <!-- Begin Recent Arrivals Block -->
          <?php
            $stylesheet = "/usr/local/docs/sites/all/themes/utlibraries_d7/tpl/recent_arrivals/arrivals_spotlight_extended.xsl";         //location of stylesheet
            //$request_directory = $_SERVER['DOCUMENT_ROOT'] ."/recentarrivals/xml"; //directory of xml files
            $file = array("fi.xml"); //location of xml file
            $request = "/home/utlol/htdocs/lib-recentarrivals/xml/". $file[0];
            //$request = "/usr/local/docs/sites/all/themes/utlibraries/inc/xml/". $file[0];
            
            /*
            #############
            # functions #
            #############
            */
            
            function isbn_split($isbn) {
                    $isbn_image = explode(" ", $isbn);
                    $image = $isbn_image[0];
                    return $image;
            }
            function recentarrivals_title($title) {
                    $newtitle = explode("/", $title);
                    return $newtitle[0];
            }
            
            /*
            #########################
            ######## MAIN ###########
            #########################
            #Create documents,      #
            #Load XML and Transform #
            #using XSL StyleSheets  #
            #########################
            */
            // Create document to work in and load content of file
            $XML = new DOMDocument();
            $XML->load( $request );
            
            // Start XSLT
            $XSLT = new XSLTProcessor();    
                    
            //Create document for stylesheet and load contents of xsl
            $XSL = new DOMDocument();
            $XSL->load( $stylesheet );
            $XSLT->importStylesheet( $XSL );  //import stylesheet
             
            $XSLT->registerPHPFunctions();  //register PHP functions
              
            print $XSLT->transformToXML( $XML );  //transform XML with StyleSheet
            
            
          ?>
          <div class="frontpage-block-footer">
            <hr>
            <p class="more-in-category"><a href="/d7/<?php print render($content['field_url_short_title'][0]['#markup']); ?>/about/holdings/recent-arrivals" alt="All Recent Arrivals">All Recent Arrivals</a></p>
            <p class="more-in-category"><a href="/d7/<?php print render($content['field_url_short_title'][0]['#markup']); ?>/about/holdings/recent-arrivals??sort_by=date_added&results_per_page=20&location=fi&type=a&language=any" alt="Recent Book Arrivals">Recent Book Arrivals</a></p>
            <p class="more-in-category"><a href="/d7/<?php print render($content['field_url_short_title'][0]['#markup']); ?>/about/holdings/recent-arrivals??sort_by=date_added&results_per_page=20&location=fi&type=l&language=any" alt="Recent DVD Arrivals">Recent DVD Arrivals</a></p>
            <p class="more-in-category"><a href="/d7/<?php print render($content['field_url_short_title'][0]['#markup']); ?>/about/holdings/recent-arrivals??sort_by=date_added&results_per_page=20&location=fi&type=h&language=any" alt="Recent CD Arrivals">Recent CD Arrivals</a></p>
            <p class="more-in-category"><a href="/d7/<?php print render($content['field_url_short_title'][0]['#markup']); ?>/about/holdings/recent-arrivals??sort_by=date_added&results_per_page=20&location=fi&type=c&language=any" alt="Recent Score Arrivals">Recent Score Arrivals</a></p>
          </div>
      </div>
      </div>
      <div class="frontpage-column">
      <div class="frontpage-block">
        <h3 class="frontpage-block-title gradient">FAL News</h3>
          <?php
            $block = module_invoke('views', 'block_view', 'branch_views-block_29');
            print render($block['content']);
          ?>
        <div class="frontpage-block-footer">  
          <hr>
          <p class="more-in-category"><a href="/d7/<?php print render($content['field_url_short_title'][0]['#markup']); ?>/news/" alt="More News">More News</a></p>
        </div>
      </div>
      </div>
      <div class="frontpage-column">
      <div class="frontpage-block">
        <h3 class="frontpage-block-title gradient">Featured Resource</h3>
          <?php
            $block = module_invoke('views', 'block_view', 'featured_resources-block_2');
            print render($block['content']);
          ?>
      </div>
      </div>
      <div class="frontpage-column">
      <div class="frontpage-block">
        <h3 id="frontpage-block-title" class="frontpage-block-title gradient">Connect With Us</h3>
          <div class="ask-a-librarian-widget">
            <iframe style="width: 98%; height: 300px;" src="//libraryh3lp.com/chat/utexas-fal@chat.libraryh3lp.com?sounds=true&title=Ask+A+Librarian&css=//cms-d7.lib.utexas.edu/sites/all/themes/utlibraries_d7/stylesheets/partials/design/chat-widget.css" frameborder="0"></iframe>
          </div>
          <div class="frontpage-block-footer">
            <hr>
            <p class="more-in-category"><a href="http://lib.utexas.edu/services/reference/chat/">After Hours Chat</p>
              <?php
                $block = module_invoke('views', 'block_view', 'branch_views-block_22');
                print render($block['content']);
              ?>
          </div>
      </div>
      </div>
    </div>
  </div>
</article>
