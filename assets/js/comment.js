$(document).ready(function(){

    function loadComments(id, element, type){
      $.ajax({
        url: "inc/user_comments.php",
        method: "POST",
        data: {id: id, type: type},
        success: function(res){
          $(res).insertBefore(element).slideDown(300, "swing");
          if(type == "reply"){
            element.removeClass("view-replies");
            element.addClass("hide-replies");
            element.fadeOut(150, function(){
              element.html("hide replies").fadeIn(150);
            });

            var comment = element.closest(".comment");
            if(comment.children(".view-latest-replies").length === 0){
              var commentData = comment.attr("data");
              var postedReply  = comment.children(".reply-container").children(".replied").length;
              var lastReplyData = parseInt(comment.children(".reply-container").children(".comment").last().attr("data"));
              $.ajax({
                url: "inc/user_comments.php",
                method: "POST",
                data: {id: lastReplyData, type: "latest_reply_count", post_id: commentData},
                success: function(res){
                  res = parseInt(res);
                  if(res > postedReply){
                    $(`<span style="display:none" class="view-latest-replies link-colored">see more replies</span>`).insertAfter(comment.children("span").last()).fadeIn();
                  }
                }
              });
            }
          }
        }
      });
    }

    function postComment(form){
      if($.trim(form.find("[name='comment-text']").val())){
        var comment = $.trim(form.find("[name='comment-text']").val());
        if(form.parent().hasClass("post")){
          var id      = form.closest(".post").attr('data');

          $.ajax({
            url: "inc/user_comments.php",
            method: "POST",
            data: {id: id, type: "post_comment", comment_text: comment},
            success: function(res){
              var postedComment = res;
              var prevElement = form.prev();
              if(prevElement.hasClass("comment-container")){
                showComments(prevElement, parseInt(id), parseInt(postedComment));
              }
              else{
                showComments(prevElement, parseInt(id));
              }
            }
          });
        }
        else if(form.parent().hasClass("reply-form")){
          var id        =   form.closest(".comment").attr('data');
          var post_id   =   form.closest(".post").attr('data');

          $.ajax({
            url: "inc/user_comments.php",
            method: "POST",
            data: {id: id, post_id: post_id, type: "post_reply", comment_text: comment},
            success: function(res){
              form.closest(".comment").children(".comment-action").find(".reply").click();
              var postedComment = res;
              var container = form.closest(".comment").find(".reply-container");
              if(container.length === 0){
                html = `<div style="display:none" class="reply-container"><div class="replied">`+res+`</div></div>`;
                $(html).insertAfter(form.closest(".comment").find(".comment-action").first()).slideDown();
              }
              else{
                html = `<div style="display:none" class="replied">`+res+`</div>`;
                $(html).appendTo(form.closest(".comment").find(".reply-container").first()).slideDown();
              }
            }
          });
        }
      }
    }

    function showComments(prevElement, parentID, singleComment = null, fromBefore = null){
      var id = parentID;
      $.ajax({
        url: "inc/user_comments.php",
        method: "POST",
        data: {id: id, type: "comment", singleComment: singleComment, fromBefore: fromBefore},
        success: function(res){
          if(prevElement.hasClass("comment-container")){
            var topComment = prevElement.find(".comment:first");
            var html = "<div class='commented' style='display:none'>" + res + "</div>"
            $(html).insertBefore(topComment).slideDown(300, "swing");
          }else if(prevElement.hasClass("actions")){
            var html = "<div class='comment-container' style='display:none'><h5 class='comment-heading my-2 border-bottom'>Comments</h5><div class='comment-box px-2'><div class='commented'>" + res + "</div></div></div>"
            $(html).insertAfter(prevElement).slideDown(300, "swing");
          }
          prevElement.closest(".post").find(".comment-gen-btn").click();
        }
      });
    }

    $(document).on("click", ".view-replies", function(e){
      var parentID= $(e.target).closest(".comment").attr('data');
      var element= $(e.target);
      loadComments(parentID, element, "reply");
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

      if(element.closest(".comment").children(".view-latest-replies").length === 0){
          element.closest(".comment").children(".view-latest-replies").fadeOut(200, function(){
            this.remove();
          });
      }
    });

    $(document).on("click", ".comment-gen-btn", function(e){
      var element= $(e.target);
      var id = 0;
      if(element.hasClass("show")){
        $.ajax({
          url: "inc/user_comments.php",
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
      else{
        element.closest(".post").children(".comment-form").slideUp(100,"swing",function(){
          this.remove();
        });
        element.removeClass("hide");
        element.addClass("show");
      }
    });
    $(document).on("keydown", ".comment-form [name='comment-text']", function(e){
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
              url: "inc/user_comments.php",
              method: "POST",
              data: {id: id, type: "comment", fromBefore: last_comment_data},
              success: function(res){
                var html = "<div class='loaded-comments' style='display:none'>" + res + "</div>"
                if(last_comment.parent().hasClass("comment-box")){
                  $(html).insertAfter(last_comment).slideDown(300, "swing",function(){
                    this.scrollIntoView({
                        behavior: "smooth", // or "auto" or "instant"
                        block: "nearest" // or "end"
                    });
                  });
                  // last_comment.next()
                }else{
                  $(html).insertAfter(last_comment.parent()).slideDown(300, "swing");
                }

                var insertedLastData = post.find(".comment:last").attr("data");
                $.ajax({
                  url: "inc/user_comments.php",
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
      console.log(comment.parent().find(".comment").length);
      $.ajax({
              url: "inc/user_comments.php",
              method: "POST",
              data: {id: id, type: "delete"},
              success: function(){
                if(comment.parent().find(".comment").length > 1){
                  comment.animate({ height: 0, opacity: 0 }, function(){
                    comment.remove();
                  });
                }
                else{
                  comment.closest(".comment-container").animate({ height: 0, opacity: 0 }, function(){
                    comment.remove();
                  });
                }
              }
            });
    });

    function submitCommentEdit(comment, oldText, newText){
      if(newText != "" && newText != null){
        comment.children("[name='comment-text']").replaceWith("<div style='position:relative' class='comment-text'><div class='cover' style='background:#ccc;position:absolute;top:0;left:0;width:100%;height:100%;border-radius: 5px;'></div>"+newText+"</div>");
        comment.children(".comment-text").children(".cover").fadeOut(1000, function(){
          this.remove();
        });
        var commentData = comment.attr('data');
        if($.trim(newText) != $.trim(oldText)){

          $.ajax({
            url: "inc/user_comments.php",
            method: "POST",
            data: {id: commentData, type: "edit_comment", comment_text: newText}
          });
        }

        comment.children(".comment-action").find(".cancel, .save").parent().fadeOut(200, function(){
          this.remove();
        });
      }
    }

    $(document).on("click", ".user-control .edit-comment", function(){
      var comment = $(this).closest(".comment");
      var text = comment.children(".comment-text").text();
      var height = comment.children(".comment-text").height();

      comment.children(".comment-text").replaceWith($(`<textarea style="resize: none;margin: 9px 0 1px -2px;height:`+height+`px" class="form-control bg-transparent text-white" name="comment-text" placeholder="type your comment edition here.." autocomplete="off" >`+text+`</textarea>`));

      $(`<li style="display:none; margin-left:auto"><span class="action cancel">cancel</span></li><li style="display:none"><span class="action save">save</span></li>`).appendTo(comment.children(".comment-action").children(".action-list")).fadeIn();
      var strLength = comment.children("textarea").val().length;
      comment.children("textarea").focus();
      comment.children("textarea")[0].setSelectionRange(strLength, strLength);


      comment.children("[name='comment-text']").on("keydown", function(e){
        if (e.which == 13 && !e.shiftKey) {
          e.preventDefault();
          var newText = $(this).val();
          submitCommentEdit(comment, text, newText)
        }
      });

      comment.children(".comment-action").find(".save").click(function(){
        var newText = comment.children("[name='comment-text']").val();
        submitCommentEdit(comment, text, newText)
      });

      comment.children(".comment-action").find(".cancel").click(function(){
        comment.children("[name='comment-text']").replaceWith(function(){
          return $("<div style='display:none' class='comment-text'>"+text+"</div>").fadeIn();
        });

        comment.children(".comment-action").find(".cancel, .save").parent().fadeOut(200, function(){
          this.remove();
        });
      });
    });

    $(document).on("click", ".comment .reply", function(){
      var element = $(this);
      var id = 0;
      if(element.hasClass("show")){
        $.ajax({
          url: "inc/user_comments.php",
          method: "POST",
          data: {id: id, type: "gen_comment"},
          success: function(res){
            res = jQuery.parseHTML(res);
            $(res).show().removeClass("mt-3");
            $(res).find("[name='comment-text']").attr("placeholder", "Type your reply to "+element.closest(".comment").find(".author").first().text()+" here..");
            res = $(`<div style="display:none; margin-bottom: 8px; padding: 8px 0 0 24px" class="reply-form border-start">`).append(res);
            $(res).appendTo(element.closest(".comment")).slideDown(300);
            element.removeClass("show");
            element.addClass("hide");
          }
        });
      }
      else{
        element.closest(".comment").children(".reply-form").slideUp(200,"linear",function(){
          this.remove();
        });
        element.removeClass("hide");
        element.addClass("show");
      }
    });
    $(document).on("click", ".view-latest-replies", function(evt){
      var comment = $(evt.target).closest(".comment");
      var commentData = comment.attr("data");
      var postedReply  = comment.children(".reply-container").children(".replied").length;
      if (comment.children(".reply-container").children(".loaded-reply").length !== 0) {
        var lastReplyData = parseInt(comment.children(".reply-container").children(".loaded-reply").last().children(".comment").last().attr("data"));  
      }
      else{
        var lastReplyData = parseInt(comment.children(".reply-container").children(".comment").last().attr("data"));
      }
      
      $.ajax({
        url: "inc/user_comments.php",
        method: "POST",
        data: {id: lastReplyData, type: "latest_reply", post_id: commentData},
        success: function(res){
          $(res).appendTo(comment.children(".reply-container")).slideDown();

          var lastReplyData = parseInt(comment.children(".reply-container").children(".loaded-reply").last().children(".comment").last().attr("data"));
          $.ajax({
            url: "inc/user_comments.php",
            method: "POST",
            data: {id: lastReplyData, type: "latest_reply_count", post_id: commentData},
            success: function(res){
              res = parseInt(res);
              if(!res > postedReply){
                $(evt.target).fadeOut(200,function(){
                  this.remove();
                });
              }
            }
          });
        }
      });
    });

    $(document).on("click keyup", ".post", function(){
      var post = $(this);
      var postData = $(this).attr("data");

      if(post.find(".view-latest-comments").length === 0){
        var postedComment = $(this).find(".comment-box").children(".commented").length;
        if(post.find(".comment-box").length !== 0){
          var lastCommentData = post.find(".comment-box").children(".comment").first().attr("data");
        }else{
          var lastCommentData = 0;
        }
        $.ajax({
          url: "inc/user_comments.php",
          method: "POST",
          data: {id: lastCommentData, type: "latest_comment_count", post_id: postData},
          success: function(res){
            res = parseInt(res);
            if(res > postedComment){
              $(`<span style="display:none" class="view-latest-comments link-colored">See latest comments</span>`).appendTo(post).fadeIn();
            }
          }
        });
      } 

      var commentCount = parseInt(post.find(".post-comment-count").text());
      $.ajax({
        url: "inc/user_comments.php",
        method: "POST",
        data: {id: postData, type: "comment_count"},
        success: function(res){
          res = parseInt(res);
          if(res !== commentCount){
            post.find(".post-comment-count").html(res);
          }
        }
      });

      var replyCount = parseInt(post.find(".post-reply-count").text());
      $.ajax({
        url: "inc/user_comments.php",
        method: "POST",
        data: {id: postData, type: "reply_count"},
        success: function(res){
          res = parseInt(res);
          if(res !== replyCount){
            post.find(".post-reply-count").html(res);
          }
        }
      });
    });

    $(document).on("click keyup", ".reply-container", function(){
      var comment = $(this).closest(".comment")
      var commentData = comment.attr("data")

      if(comment.children(".view-latest-replies").length === 0){
        var postedReply  = comment.children(".reply-container").children(".replied").length;
        var lastReplyData = comment.children(".reply-container").children(".comment").last().attr("data");
        $.ajax({
          url: "inc/user_comments.php",
          method: "POST",
          data: {id: lastReplyData, type: "latest_reply_count", post_id: commentData},
          success: function(res){
            res = parseInt(res);
            if(res > postedReply){
              $(`<span style="display:none" class="view-latest-replies link-colored">See latest replies</span>`).insertAfter(comment.children("span").last()).fadeIn();
            }
          }
        });
      }
    });

});