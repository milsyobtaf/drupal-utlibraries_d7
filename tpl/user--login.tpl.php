<div class="user-login-wrapper">
<h2>User Login</h2>
<div class="user-login-box-shibboleth">
  <p class="user-login-button-shibboleth">
    <a href="https://cms-d7.lib.utexas.edu/Shibboleth.sso/Login?target=https%3A%2F%2Fcms-d7.lib.utexas.edu%2Fd7%2F%3Fq%3Dshib_login%2Fuser">Login Using Your UT EID</a>
  </p>
</div>

<div class="user-login-box-drupal">
  <p class="user-login-button-drupal">
    <span onclick="toggle_visibility('user-login-form-drupal');">
      <a href="#">Login Using Your Administrator Account</a>
    </span>
  </p>
  <div class="user-login-form-drupal" id="user-login-form-drupal" style="display:none;">
    <?php
     /* split the username and password from the submit button
       so we can put in links above */
        print drupal_render($form['name']);
        print drupal_render($form['pass']);
        print drupal_render($form['form_build_id']);
        print drupal_render($form['form_id']);
        print drupal_render($form['actions']);
    ?>
  </div>
</div>
</div>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
</script>
