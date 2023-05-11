<?php
/*
Plugin Name: Yes No Counter
Description: Simple plugin to collect and display Yes/No feedback and provide an admin overview of the counts.
Version: 1.0
Author: Jordan Burch
Author URI: https://www.metadigital.co.nz/
*/

// Register the shortcode [yes_no_counter]
function yes_no_counter_shortcode() {
    // Enqueue the necessary JavaScript file
    wp_enqueue_script('yes-no-counter-script', plugin_dir_url(__FILE__) . 'assets/yes-no-counter.js', array('jquery'), '1.0', true);
    
    // Output the shortcode HTML
    $html = '
        <div id="yes-no-counter">
            <input type="checkbox" name="yes-no" id="yes-no-yes" value="yes">
            <label for="yes-no-yes" class="pe-4">Yes</label>
            <input type="checkbox" name="yes-no" id="yes-no-no" value="no">
            <label for="yes-no-no">No</label>
        </div>
        <div id="yes-no-counter-result"></div>
    ';
    
    return $html;
}
add_shortcode('yes_no_counter', 'yes_no_counter_shortcode');

// Register the admin menu page
function yes_no_counter_options_page() {
    add_options_page('Yes No Counter Options', 'Yes No Counter', 'manage_options', 'yes-no-counter-options', 'yes_no_counter_options_page_content');
}
add_action('admin_menu', 'yes_no_counter_options_page');

// Render the options page content
function yes_no_counter_options_page_content() {
  // Get the count of Yes and No from the database
  $yes_count = get_option('yes_no_counter_yes_count', 0);
  $no_count = get_option('yes_no_counter_no_count', 0);

  // Check if the reset button is clicked
  if (isset($_POST['reset_counts'])) {
      // Reset the counts to zero
      update_option('yes_no_counter_yes_count', 0);
      update_option('yes_no_counter_no_count', 0);

      // Redirect to the options page to display the updated counts
      wp_redirect(admin_url('options-general.php?page=yes-no-counter-options'));
      exit();
  }

  // Output the options page HTML
  $html = '
      <div class="wrap">
          <h1>Yes No Counter Options</h1>
          <p>Yes count: ' . $yes_count . '</p>
          <p>No count: ' . $no_count . '</p>
          
          <form method="post" action="">
              <input type="hidden" name="reset_counts" value="1">
              <button type="submit" class="button button-primary">Reset Counts</button>
          </form>
      </div>
  ';

  echo $html;
}


// Process the AJAX request
function yes_no_counter_ajax_callback() {
    $option_name = 'yes_no_counter_' . $_POST['value'] . '_count';
    
    // Increase the count for the selected option
    $count = get_option($option_name, 0);
    update_option($option_name, ++$count);
    
    // Return the updated count
    wp_send_json(array('count' => $count));
}
add_action('wp_ajax_yes_no_counter_ajax', 'yes_no_counter_ajax_callback');
add_action('wp_ajax_nopriv_yes_no_counter_ajax', 'yes_no_counter_ajax_callback');
