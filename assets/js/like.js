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

  $(document).on("click", ".comment .comment-action .react", function(evt){
    var commentData = $(this).closest(".comment").attr("data");
    if($(this).hasClass("reacted")){
      var task = "undoReact";
    }else{
      var task = "react";
    }
    
    $.ajax({
      url: "inc/user_likes.php",
      method: "POST",
      data: {commentData: commentData, task: task, type: "comment"},
      success: function(res){
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

  $(document).on("click", ".post", function(evt){
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

    $.each($(this).find(".comment"), function(){
      var comment       =   $(this);
      var commentData   =   comment.attr("data");
      var reactCount = parseInt(comment.children(".comment-info").find(".react-count").text());

      $.ajax({
        url: "inc/user_likes.php",
        method: "POST",
        data: {commentData: commentData, task: "countReact", type: "comment"},
        success: function(res){
          res = parseInt(res);
          if(res !== reactCount){
            comment.children(".comment-info").find(".react-count").html(res);
          }
        }
      });
    });
  });
});