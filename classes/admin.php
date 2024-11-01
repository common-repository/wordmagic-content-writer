<?php

namespace WORDMAGIC;

class WMAI_Admin
{
  private $basichtml;

  public function __construct()
  {
    add_action("enqueue_block_editor_assets", [$this, 'admin_scripts']);

    add_action('admin_menu', [$this, 'wmai_admin_page']);

    add_action('admin_enqueue_scripts', [$this, 'wmai_settings_css']);

    add_action('admin_init', [$this, 'wmai_settings_save']);

    if (isset($_GET['wmai_success'])) {
      add_action('admin_notices', [$this, 'wmai_saved']);
    }

    add_action('wp_ajax_get_wmai_settings', [$this, 'wmai_send_settings']);

    $this->basichtml = array(
      'div' => [
        'class' => [],
        'id' => []
      ],
      'img' => [
        'src' => []
      ],
      'form' => [
        'method' => []
      ],
      'label' => [],
      'small' => [],
      'a' => [
        'href' => []
      ],
      'span' => [],
      'input' => [
        'type' => [], 'placeholder' => [], 'name' => [], 'value' => [], 'required' => []
      ],
      'select' => [
        'class' => [], 'name' => []
      ],
      'option' => [
        'name' => [], 'selected' => []
      ],
      'button' => [
        'type' => []
      ]
    );
  }

  public function wmai_settings_css()
  {
    wp_enqueue_style(WMAI_NAME . '-settings', WMAI_URL . 'admin/assets/settings.css');
  }

  public function admin_scripts()
  {
    $deps = array('react', 'react-dom', 'wp-blocks', 'wp-components', 'wp-data', 'wp-edit-post', 'wp-element', 'wp-i18n', 'wp-plugins', 'wp-primitives');

    wp_enqueue_script(WMAI_NAME, WMAI_URL . 'admin/build/bundle.js', $deps, WMAI_VERSION, true);

    wp_localize_script(WMAI_NAME, 'wordmagic', array(
      'ajaxURL' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('wmai_fetch'),
      'favicon' => WMAI_URL . 'admin/assets/icon-guten.png'
    ));

    wp_enqueue_style(WMAI_NAME, WMAI_URL . 'admin/css/wordmagic.min.css');
  }

  public function wmai_admin_page()
  {
    add_menu_page(WMAI_NAME, WMAI_NAME, 'manage_options', WMAI_SLUG, [$this, 'wmai_settings_page'], WMAI_URL . 'admin/assets/icon-xs.png');
  }

  public function wmai_settings_page()
  {
    $settings = get_option('wmai_settings');

    $key = $style = $tone = '';
    if (FALSE !== $settings) {
      $key = $settings['key'];
      $style = $settings['wstyle'];
      $tone = $settings['wtone'];
    }

    $output = '<div class="wrap">
    <div id="wmai-settings">
      <div class="wmai-logo"><img src="' . WMAI_URL . 'admin/assets/dash-logo-white.png"/></div>
      <div class="wmai-settings-wrap">

      <form method="post">
        <div class="wmai-option">
          <label>OpenAI Key</label>
          <span>
            <input type="text" placeholder="Enter your API key" name="wmai_key" value="' . esc_attr($key) . '" required>
            <small>Obtain key from <a href="https://beta.openai.com">beta.openai.com</a></small>
          </span>
        </div>

        <div class="wmai-option">
          <label>Writing Style</label>
          <span>
            <select class="wmai-dropdown" name="wmai_writing_style">';

    $opts = array(
      'Informative', 'Analytical', 'Argumentative', 'Creative', 'Critical', 'Descriptive', 'Evaluative', 'Expository', 'Journalistic', 'Narrative', 'Persuasive', 'Reflective', 'Simple', 'Technical'
    );

    foreach ($opts as $opt) {
      $output .= '<option name="' . esc_attr($opt) . '" ' . selected($style, $opt, false) . '>' . esc_attr($opt) . '</option>';
    }

    $output .= '</select>
          </span>
        </div>

        <div class="wmai-option">
          <label>Writing Tone</label>
          <span>
            <select class="wmai-dropdown" name="wmai_writing_tone">';

    $opts = array(
      'Formal', 'Assertive', 'Cheerful', 'Humorous', 'Informal', 'Inspirational', 'Neutral', 'Professional', 'Sarcastic', 'Skeptical'
    );

    foreach ($opts as $opt) {
      $output .= '<option name="' . esc_attr($opt) . '" ' . selected($tone, $opt, false) . '>' . esc_attr($opt) . '</option>';
    }

    $output .= '</select>
          </span>
        </div>

        ' . wp_nonce_field('wmai_settings_save', 'wmai_auth', FALSE, FALSE) . '

        <div class="wmai-save">
          <button type="submit">Save Settings</button>
        </div>
      </form>

      </div>
    </div>
    </div>';

    echo wp_kses($output, $this->basichtml);
  }

  public function wmai_settings_save()
  {

    if (isset($_POST['wmai_auth'])) {

      if (!wp_verify_nonce($_POST['wmai_auth'], 'wmai_settings_save')) {
        exit('Unauthorized');
      }

      $AIkey = sanitize_text_field($_POST['wmai_key']);
      $style = sanitize_text_field($_POST['wmai_writing_style']);
      $tone = sanitize_text_field($_POST['wmai_writing_tone']);

      $arr = array(
        'key' => $AIkey,
        'wstyle' => $style,
        'wtone' => $tone
      );

      update_option('wmai_settings', $arr);

      wp_redirect(admin_url('admin.php?page=wordmagic-ai&wmai_success=1'));
      exit();
    }
  }

  public function wmai_saved()
  {
    echo '<div class="notice notice-success"><p>Settings Saved!</p></div>';
  }

  public function wmai_send_settings()
  {

    $error = array('error' => 'unauthorized');

    if (!wp_verify_nonce($_GET['nc'], 'wmai_fetch') || !current_user_can('edit_posts')) {
      echo json_encode($error);
      exit();
    }

    $settings = get_option('wmai_settings');

    if (FALSE === $settings || !isset($settings['key'])) {
      echo json_encode(array('error' => 'key_not_set'));
      exit();
    }

    echo json_encode($settings);

    exit();
  }
}
