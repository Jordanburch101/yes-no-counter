jQuery(document).ready(function($) {
  // Handle the checkbox change event
  $('#yes-no-counter input[type="checkbox"]').change(function() {
      var value = $(this).val();
      
      // Disable the checkboxes
      $('#yes-no-counter input[type="checkbox"]').attr('disabled', true);
    // Display the loading message
    $('#yes-no-counter-result').text('Submitting...');

    // Send an AJAX request to update the count
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'yes_no_counter_ajax',
            value: value
        },
        success: function(response) {
            // Update the count
            var count = response.count;
            // $('#yes-no-counter-result').text('Thank you for your feedback.');
            // if yes is checked
            if (value == 'yes') {
                $('#yes-no-counter-result').text('Thank you for your feedback.');
            } else {
                $('#yes-no-counter-result').html('Let us know how we can improve <a href="/contact" target="_blank">here</a>.');
            }
            // Remove the checkboxes after a brief delay
            setTimeout(function() {
                $('#yes-no-counter').remove();
            }, 2000);
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('#yes-no-counter-result').text('An error occurred. Please try again.');
        }
    });
});
});
