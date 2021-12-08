$(document).ready(function(){
// MultiLevel DropDown
    $('.dropdown-menu li .btn-group .dropdown-toggle').on("click", function(e){
      if($(this).hasClass('show')){
        $(this).next('.dropdown-menu').show();
      }
      e.stopPropagation();
      e.preventDefault();
    });
});