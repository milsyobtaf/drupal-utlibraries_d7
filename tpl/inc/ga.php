<!-- This is kind of a kludge. This file is necessary, rather than using the standard Drupal GA module, because we need to send data to both the standard Google server and the UT Web Central server. Sorry. -->

<!-- call standard google tracking script -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
	try {
	  var pageTracker = _gat._getTracker("UA-3780897-3");

	  // begin additional code to avoid cookie conflicts
	  pageTracker._setDomainName(".utexas.edu");
	  pageTracker._setAllowHash(false);

	  // end additional code to avoid cookie conflicts
	  pageTracker._trackPageview();
	} catch(err) {}
</script>

<!-- call Web Central tracking script -->
<script type="text/javascript">
	var utJsHost = (("https:" == document.location.protocol) ? "https://www." : "http://www.");
	document.write(unescape("%3Cscript src='" + utJsHost + "utexas.edu/common/js/ga_subdomain.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<!-- all subdomains -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-3780897-21']);
  _gaq.push(['_setDomainName', 'lib.utexas.edu']);   
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
