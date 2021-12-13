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

    function genCommentForm(id, element, type){
      $.ajax({
        url: "inc/fetch_comments.php",
        method: "POST",
        data: {id: id, type: "gen_comment"},
        success: function(res){
          element.closest(".post").append(res);
          element.closest(".post").children(".comment-form").slideDown(150, "swing");
          element.removeClass("show");
          element.addClass("hide");
        }
      });
    }
    function postComment(form){
      if($.trim(form.find("[name='comment-text']").val())){
        var comment = $.trim(form.find("[name='comment-text']").val());
        var id      = form.attr('data');

        $.ajax({
          url: "inc/fetch_comments.php",
          method: "POST",
          data: {id: id, type: "post_comment", comment_text: comment},
          success: function(res){
            var postedComment = res;
            var prevElement = form.prev();
            if(prevElement.hasClass("comment-container")){
              showComments(prevElement, parseInt(id), parseInt(postedComment));
            }
            else{
              showComments(prevElement, id);
            }
          }
        });
      }
    }
    function showComments(prevElement, parentID, singleComment = null, fromBefore = null){
      var id = parentID;
      $.ajax({
        url: "inc/fetch_comments.php",
        method: "POST",
        data: {id: id, type: "comment", singleComment: singleComment, fromBefore: fromBefore},
        success: function(res){
          if(prevElement.hasClass("comment-container")){
            var topComment = prevElement.find(".comment:first");
            var html = "<div style='display:none'>" + res + "</div>"
            $(html).insertBefore(topComment).slideDown(300, "swing");
          }else if(prevElement.hasClass("actions")){
            var html = "<div class='comment-container' style='display:none'><h5 class='comment-heading my-2 border-bottom'>Comments</h5><div class='ps-2'>" + res + "</div></div>"
            $(html).insertAfter(prevElement).slideDown(300, "swing");
          }
          prevElement.closest(".post").find(".comment-gen-btn").click();
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

    $(document).on("click", ".comment-gen-btn", function(e){
      if($(e.target).hasClass("show")){
        var parentID= $(e.target).attr('data');
        var element= $(e.target);
        comments = genCommentForm(parentID, element, "comment");
      }
      else{
        var element= $(e.target);
        element.closest(".post").children(".comment-form").slideUp(100,"swing",function(){
          this.remove();
        });
        element.removeClass("hide");
        element.addClass("show");
      }
    });
    $(document).on("keydown", "[name='comment-text']", function(e){
      if (e.which == 13 && !e.shiftKey) {
        e.preventDefault();
        var form = $(this).closest(".comment-form");
        postComment(form);
      }
    });

    $(document).on("submit", ".comment-form", function(e){
      e.preventDefault();
      var form = $(e.target);
      postComment(form);
    });

    $(document).on("click", ".view-more-comments", function(evt){
      var post = $(this).closest(".post");
      var id = post.attr("data");
      var last_comment =  $(this).parent().find(".comment:last");
      var last_comment_data =  last_comment.attr("data");
      $.ajax({
              url: "inc/fetch_comments.php",
              method: "POST",
              data: {id: id, type: "comment", fromBefore: last_comment_data},
              success: function(res){
                var html = "<div class='loaded-comments' style='display:none'>" + res + "</div>"
                if(last_comment.parent().hasClass("comment-box")){
                  $(html).insertAfter(last_comment).slideDown(300, "swing");
                }else{
                  $(html).insertAfter(last_comment.parent()).slideDown(300, "swing");
                }

                var insertedLastData = post.find(".comment:last").attr("data")
                $.ajax({
                  url: "inc/fetch_comments.php",
                  method: "POST",
                  data: {id: id, type: "comment", fromBefore: insertedLastData},
                  success: function(response){
                    if(response == ""){
                      $(evt.target).fadeOut(function(){
                      if(!res == ""){
                        if(!$(evt.target).next().hasClass("hide-few-comments")){
                          $(`<span style="display:none" class="hide-few-comments link-colored">Hide few comments</span>`).insertAfter($(evt.target)).fadeIn();
                        }else{
                          if(!$(evt.target).next().next().hasClass("hide-loaded-comments")){
                            $(`<span style="display:none" class="hide-loaded-comments link-colored">Hide all loded comments</span>`).insertAfter($(evt.target).next()).fadeIn();
                          }
                        }
                        $(evt.target).remove();
                      }
                      });
                    }else{
                      if(!$(evt.target).next().hasClass("hide-few-comments")){
                        $(`<span style="display:none" class="hide-few-comments link-colored">Hide few comments</span>`).insertAfter($(evt.target)).fadeIn();
                      }else{
                        if(!$(evt.target).next().next().hasClass("hide-loaded-comments")){
                          $(`<span style="display:none" class="hide-loaded-comments link-colored">Hide all loded comments</span>`).insertAfter($(evt.target).next()).fadeIn();
                        }
                      }
                    }
                  }
                });
              }
            });
    });

    $(document).on("click", ".hide-few-comments", function(evt){
      var commentBox = $(this).parent();

      commentBox.find(".loaded-comments:last").slideUp(500, function(){
        this.remove();
        if(commentBox.find(".loaded-comments").length ===0){
          $(evt.target).fadeOut(function(){

            if(commentBox.find(".view-more-comments").length ===0){
              $(`<span style="display:none" class="view-more-comments link-colored">See more comments</span>`).insertBefore($(evt.target)).fadeIn();
            }
            this.remove();
          });

          if(commentBox.find(".hide-loaded-comments").length !==0){
            commentBox.find(".hide-loaded-comments").fadeOut(function(){
              this.remove();
            })
          }
        }
        else{
          if(commentBox.find(".view-more-comments").length ===0){
            $(`<span style="display:none" class="view-more-comments link-colored">See more comments</span>`).insertBefore($(evt.target)).fadeIn();
          }

        }
      }); 
    });

    $(document).on("click", ".hide-loaded-comments", function(evt){
      var commentBox = $(this).parent();
      commentBox.find(".loaded-comments").slideUp(500, function(){
        this.remove();
      });

      if(commentBox.find(".hide-few-comments").length !==0){
        $(evt.target).prev(".hide-few-comments").fadeOut(function(){
          this.remove();
        });
      }
      
      $(evt.target).fadeOut(function(){
        if(commentBox.find(".view-more-comments").length ===0){
          $(`<span style="display:none" class="view-more-comments link-colored">See more comments</span>`).insertBefore($(evt.target)).fadeIn();
        }
        this.remove();
      });
      if(commentBox.find(".view-more-comments").length ===0){
        $(`<span style="display:none" class="view-more-comments link-colored">See more comments</span>`).insertBefore($(evt.target).prev()).fadeIn();
      }
    });

    $(document).on("click", ".user-control .delete-comment", function(){
      var comment = $(this).closest(".comment");
      var id = comment.attr('data');
      $.ajax({
              url: "inc/fetch_comments.php",
              method: "POST",
              data: {id: id, type: "delete"},
              success: function(){
                comment.animate({ height: 0, opacity: 0 }, function(){
                  comment.remove();
                });
              }
            });
    });
    $(document).on("click", ".user-control .edit-comment", function(){
      var comment = $(this).closest(".comment");
      var id = comment.attr('data');
      var text = comment.children(".comment-text").text();
      var height = comment.children(".comment-text").height();

      comment.children(".comment-text").replaceWith(`<textarea style="resize: none;margin: 9px 0 1px -2px;height:`+height+`px" class="form-control bg-transparent text-white" name="comment-text" placeholder="type your comment here.." autocomplete="off" >`+text+`</textarea>`);

      comment.children("[name='comment-text']").on("keydown", function(e){
        if (e.which == 13 && !e.shiftKey) {
          e.preventDefault();
          var newText = $(this).val();
          $(this).replaceWith("<div style='position:relative' class='comment-text'><div class='cover' style='background:#ccc;position:absolute;top:0;left:0;width:100%;height:100%;border-radius: 5px;'></div>"+newText+"</div>");
          comment.children(".comment-text").children(".cover").fadeOut(1000, function(){
            this.remove();
          });

          if($.trim(newText) != $.trim(text)){

            $.ajax({
              url: "inc/fetch_comments.php",
              method: "POST",
              data: {id: id, type: "edit_comment", comment_text: newText}
            });
          }
        }
      })

      
    });
});