$(document).ready(function(){
  $(document).on("click", ".post .react-btn",function(evt){
    var postData = $(this).closest(".post").attr("data");
    if($(this).hasClass("reacted")){
      var task = "undoReact";
    }else{
      var task = "react";
    }
    $.ajax({
      url: "inc/user_likes.php",
      method: "POST",
      data: {postData: postData, task: task, type: "post", reactType: 1},
      success: function(){
        if(task == "react"){
          $(evt.target).addClass("reacted");
          $(evt.target).text("Liked");
        }
        else if(task == "undoReact"){
          $(evt.target).removeClass("reacted");
          $(evt.target).text("React");
        }
      }
    });
  });

  $(document).on("click", ".post",function(evt){
    var postData = $(this).closest(".post").attr("data");
    var reactCount = parseInt($(this).closest(".post").find(".post-react-count").text());

    $.ajax({
      url: "inc/user_likes.php",
      method: "POST",
      data: {postData: postData, task: "countReact", type: "post"},
      success: function(res){
        res = parseInt(res);
        if(res !== reactCount){
          $(evt.target).closest(".post").find(".post-react-count").html(res);
        }
      }
    });
  });
});