$(document).ready(function() {
  $('#registerform').submit(function(e) {
    e.preventDefault(); // Prevent form from submitting normally

    // Get form data
    var formData = $(this).serialize();

    // AJAX request
    $.ajax({
      type: 'POST',
      url: 'php/register.php',
      data: formData,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert(response.message); // Show success message
          // You can redirect to another page here if needed
        } else {
          alert(response.message); // Show error message
        }
      },
      error: function(xhr, status, error) {
        alert("An error occurred: " + error); // Show AJAX error
      }
    });
  });
});