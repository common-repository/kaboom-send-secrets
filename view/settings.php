<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="kaboom-header">
  <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/send-secrets.png'; ?>" style="width:64px;float:left;margin-right:20px">
  <h1>Send Secrets by Kaboom</h1>
  <div class="alert alert-notice hidden"></div>
  <?php if (empty($_SERVER['HTTPS'])) {
    echo "<div class='alert alert-danger'>You don't use a SSL connection, please make sure you encrypt your connection before using this plugin. Your data can get stolen!</div>";
  }?>
</div>
<div class="wrap">
  <div class="card">
    <div class="card-body">
      <p class="title">Usage:</p>
      <p>Add the following shortcode <code class="shortcode">[stand_alone_send_secret]</code> on any page you would like to use.</p>
      <form method="post" autocomplete="off">
        <?php
          wp_nonce_field('kaboom-send-secrets-nonce');
        ?>
        <hr>
        <p class="title">Settings:</p>
        <p>
          <div class="selector-box">
            <div class="changable-selector">
              <label class="switch">
                <input type="checkbox" name="send_secrets_only_admin" value="1" <?php echo sanitize_text_field(get_option( 'send_secrets_only_admin' )) == 1 ? 'checked' : ''; ?>>
                <span class="slider"></span>
              </label>            
            </div>
            <p>Only loged in administrator WordPress users can send secrets</span></span></p>
          </div> 
        </p>     
        <hr>
        <p class="title">Translations:</p>
        <div class="row">
          <div class="col-4">
            <label>Generate link button</label>
          </div>
          <div class="col-8">
            <div class="form-group">
              <input type="text" name="send_secrets_button_text" placeholder='Generate link' class="form-control" value="<?php echo sanitize_text_field(get_option( 'send_secrets_button_text' )); ?>">
            </div>
          </div>
          <div class="col-4">
            <label>No secret found message.</label>
          </div>
          <div class="col-8">
            <div class="form-group">
              <input type="text" name="send_secrets_no_secret_found" placeholder='No secret found, you may have already seen this secret?' class="form-control" value="<?php echo sanitize_text_field(get_option( 'send_secrets_no_secret_found' )); ?>">
            </div>
          </div>
          <div class="col-4">
            <label>Default text for sending secrets</label>
          </div>
          <div class="col-8">
            <div class="form-group">
              <textarea type="text" name="send_secrets_default_text_for_sending" placeholder="Here is your secret<?="\n"?>Username:<?="\n"?>Password:" class="form-control auto-height"><?php echo sanitize_textarea_field(get_option( 'send_secrets_default_text_for_sending' )); ?></textarea>
            </div>
          </div>
        </div>
        <button class="button button-primary" type="submit">Update</button>
      </form>
    </div>
  </div>
<?php
  wp_enqueue_script('jquery');
  wp_enqueue_script('kaboom_script_settings', plugins_url( 'view/script.js', dirname(__FILE__) ) );
  if (!empty($_POST) && current_user_can('manage_options')){
    
    $send_secrets_button_text = sanitize_text_field(($_POST['send_secrets_button_text']));
    $send_secrets_no_secret_found = sanitize_text_field($_POST['send_secrets_no_secret_found']);
    $send_secrets_default_text_for_sending = sanitize_textarea_field($_POST['send_secrets_default_text_for_sending']);

    $send_secrets_only_admin = isset($_POST['send_secrets_only_admin']) ? 1 : 0;

    if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'],'kaboom-send-secrets-nonce')){
      update_option( 'send_secrets_button_text',  $send_secrets_button_text);
      update_option( 'send_secrets_no_secret_found', $send_secrets_no_secret_found);
      update_option( 'send_secrets_default_text_for_sending', $send_secrets_default_text_for_sending);
      update_option( 'send_secrets_only_admin', $send_secrets_only_admin);
      wp_add_inline_script('kaboom_script_settings', "window.location.reload()");
    } else {
      wp_add_inline_script('kaboom_script_settings', "alert('Something went wrong, try reloading the page!')");
    }

  }