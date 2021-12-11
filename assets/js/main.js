$(document).ready(function(){
// MultiLevel DropDown
    $('.dropdown-menu li .btn-group .dropdown-toggle').on("click", function(e){
      if($(this).hasClass('show')){
        $(this).next('.dropdown-menu').show();
      }
      e.stopPropagation();
      e.preventDefault();
    });

    function loadComments(id, element, type){
      $.ajax({
        url: "inc/fetch_comments.php",
        method: "POST",
        data: {id: id, type: type},
        success: function(res){
          $(res).insertBefore(element).slideDown(300, "swing");
          element.removeClass("view-replies");
          element.addClass("hide-replies");
          element.fadeOut(150, function(){
            element.html("hide replies").fadeIn(150);
          })
        }
      });
    }

    $(document).on("click", ".view-replies", function(e){
      var parentID= $(e.target).attr('data');
      var element= $(e.target);
      comments = loadComments(parentID, element, "reply");
    });
    $(document).on("click", ".hide-replies", function(e){
      var element= $(e.target);
      element.prev().slideUp(300,"swing",function(){
        this.remove();
      });
      element.removeClass("hide-replies");
      element.addClass("view-replies");
      element.fadeOut(150, function(){
        element.html("See replies").fadeIn(150);
      })
      
    });
});