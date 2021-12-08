
// starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

$(document).ready(function(){
  var change =   true;
  var pass    =   $('#input-password').val();
  var cpass   =   $('#re-input-password').val();
  $('#re-input-password').keyup(function(){
    cpass   =   $('#re-input-password').val();
    if(pass){
      if(change && cpass){
        $('#re-input-password + .invalid-feedback').html("Passwords do not match.");
        change = false;
      }
      else if(!cpass){
        $('#re-input-password + .invalid-feedback').html("Confirm password field is empty.");
        change = true;
      }
      
      if(pass!=cpass  && cpass){
        $('#re-input-password').css("border-color", "red");
        // $('#regbtn').attr({disabled:true});
      }
      else if(!cpass){
        $('#re-input-password').css("border-color", "lightcoral");
      }
      else{
        $('#re-input-password').css("border-color", "forestgreen");
        // $('#regbtn').attr({disabled:false});
      }
    }
    else{
      $('#re-input-password').css("border-color", "#6c757d");
    }
  });

  $('#input-password').keyup(function(){
    pass    =   $('#input-password').val();
    if(cpass){
      if(pass!=cpass  && pass){
        $('#re-input-password').css("border-color", "red");
        // $('#regbtn').attr({disabled:true});
      }
      else if(!pass){
        $('#re-input-password').css("border-color", "lightcoral");
      }
      else{
        $('#re-input-password').css("border-color", "forestgreen");
        // $('#regbtn').attr({disabled:false});
      }
    }
  });
  
});
 