$(document).ready(function () {
  function sendData() {
      var formData = $('#update-profile-form').serializeArray();
      var jsonData = {};
      var email=localStorage.getItem("username");
      document.getElementById("email").value=email;
      $.each(formData, function (index, field) {
          jsonData[field.name] = field.value;
      });

      $.ajax({
          method: 'POST',
          url: './php/profile.php',
          data: JSON.stringify(jsonData),
          contentType: 'application/json',
          success: function (response) {
              alert('Data stored/updated successfully!');
              console.log('Data sent successfully');
              console.log(response);
              window.location.href = "profile.html";
          },
          error: function (xhr, status, error) {
              console.error('Error sending data:', error);
          }
      });
  }

  $('#update-profile-form').submit(function (event) {
      event.preventDefault();
      sendData();
  });
});
