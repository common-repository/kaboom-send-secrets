<style>
  .reveal-stand-alone-secret{
    width: 100%;
    padding: 20px;
    border: 2px solid #f7f7f7;
    border-radius: 10px;
    min-height:120px;  
  }
  input.copy-link{
    width: calc(100% - 140px);
    padding: 13px;
    border: 2px solid #f7f7f7;
    border-radius: 10px;
    background: #f7f7f7;
  }
  .no-secret-found{
    padding: 10px 20px;
    background: #f8d7da;
    color: #721c24;
    border-radius: 4px;
    margin-bottom: 10px;
    border: 1px solid #f5c6cb;    
  }
</style>
<?php 
if ( get_option( 'send_secrets_button_text', null) !== null ){
  $button_text = sanitize_text_field(get_option( 'send_secrets_button_text' )); 
} else {
  $button_text = 'Generate link';
}
if ( get_option( 'send_secrets_default_text_for_sending', null) !== null ){
  $default_text = sanitize_textarea_field(get_option( 'send_secrets_default_text_for_sending' )); 
} else {
  $default_text = 'Your secret.';
}
if ( get_option( 'send_secrets_no_secret_found', null) !== null ){
  $no_secret_found = sanitize_text_field(get_option( 'send_secrets_no_secret_found' )); 
} else {
  $no_secret_found = 'No secret found, you may have already seen this secret?';
}

function bot_detected() {
  return (
    isset($_SERVER['HTTP_USER_AGENT'])
    && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
  );
}

if(isset($_GET['token']) && !bot_detected()){ 
  
  global $wpdb;
  $request_path = $_SERVER['REQUEST_URI'];
  $table_name = 'stand_alone_send_secret';
  $correct_path = explode("?", $request_path);

  $result = $wpdb->get_results ("
    SELECT * 
    FROM  `$wpdb->prefix$table_name` WHERE url LIKE '".$request_path."'
  ");
  // Get secret
  foreach ( $result as $s ){
    echo '<textarea readonly onclick="this.select()" class="reveal-stand-alone-secret auto-height">' . $s->secret . '</textarea>';
    $wpdb->query ("
      DELETE FROM  `$wpdb->prefix$table_name` WHERE ID = '".$s->id."'
    ");
  }
  // Remove old secrets older than 4 weeks
  $remove_result = $wpdb->get_results ("
    SELECT * FROM `$wpdb->prefix$table_name` WHERE created_at < NOW() - INTERVAL 4 WEEK
  ");
  foreach ( $remove_result as $s ){
    $wpdb->query ("
      DELETE FROM  `$wpdb->prefix$table_name` WHERE ID = '".$s->id."'
    ");
  }
  // If no result, print no secret found option
  if ( count($result) == 0 ){
    echo '<div class="no-secret-found">' . $no_secret_found . '</div>';
  }
} else { 
  if (sanitize_text_field(get_option( 'send_secrets_only_admin' )) == 1 && current_user_can('administrator')){ ?>
    <form method="post">
     <textarea type="text" name="secret" class="reveal-stand-alone-secret auto-height" value="Mouse"><?php echo $default_text; ?></textarea>
     <input type="submit" name="submit" value="<?php echo $button_text; ?>" style="float: right; padding: 13px 20px; border-radius: 30px; color: white; background: #4bad42;border:none;">
    </form>
  <?php } else if (sanitize_text_field(get_option( 'send_secrets_only_admin' )) == 1 && !current_user_can('administrator')) { ?>
    <div class="no-secret-found">This plugin is setup for administrators use only, please login into your administrator WordPress account</div>
  <?php } else { ?>
    <form method="post">
     <textarea type="text" name="secret" class="reveal-stand-alone-secret auto-height" value="Mouse"><?php echo $default_text; ?></textarea>
     <input type="submit" name="submit" value="<?php echo $button_text; ?>" style="float: right; padding: 13px 20px; border-radius: 30px; color: white; background: #4bad42;border:none;">
    </form>
<?php } }
  if(isset($_POST['submit'])){
    if (sanitize_text_field(get_option( 'send_secrets_only_admin' )) == 1 && current_user_can('administrator') || sanitize_text_field(get_option( 'send_secrets_only_admin' )) == 0){
      global $wpdb;
      $secret = isset($_POST['secret']) ? sanitize_text_field($_POST['secret']) : '';

      $token = uniqid(rand(), true);
      if(empty($err)){
        $url = $_SERVER['REQUEST_URI'] . '?token=' . $token;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'stand_alone_send_secret';
        $data = array(
          'secret' => sanitize_textarea_field($_POST['secret']), 
          'url' => sanitize_text_field($url),
          'created_at' => date("Y/m/d")
        );

        $wpdb->insert($table_name,$data);
        $full_token_link = $_SERVER['HTTP_REFERER'] . '?' .  explode("?", $data['url'])[1];
        echo '<input onclick="this.select()" readonly class="copy-link" value="' . $full_token_link . '">' ;
      } else {
        echo '<div class="no-secret-found">You did not enter a secret to send</div>';
      }
    }
  }
?>
<script>
  jQuery(document).ready(function($){
    $('textarea.auto-height').keyup(function(){
      var height = $(this).prop('scrollHeight')
      $(this).animate({height: height },50)
    })
    $.each($('textarea.auto-height'), function(){
      var height = $(this).prop('scrollHeight')
      $(this).animate({height: height },50)
    })
  })
</script>