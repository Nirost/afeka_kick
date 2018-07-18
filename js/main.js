var userID = 0;
var files;
var fStr = "";
$(document).ready(function () {

    var split = location.search.split('=').map(function (val) {
        return val;
    });
    if (split.length - 1 > 0) {
        userID = split[1];
        $.ajax({
            url: "php/getUserName.php",
            type: "GET",
            data: {userID: userID},
            success: function (response) {
                var name = JSON.parse(response);
                $("#userName").text(name.first + " " + name.last);
            }
        });
    }

    $('#searchFriendsBtn').attr("disabled", true);
    if (userID > 0) {
        getPosts(userID);
        getUsers(userID);
    }


    $("#srch").multiselect({
        includeSelectAllOption: false,
        enableFiltering: true,
        disableIfEmpty: true,
        maxHeight: 200,
        width: 700,
        nonSelectedText: "Search Friends...",
        enableCaseInsensitiveFiltering: true,
        onChange: function (element) {

            if (element.parent().find(":selected").length > 0) {
                $("#searchFriendsBtn").attr("disabled", false);
            }
            else {
                $("#searchFriendsBtn").attr("disabled", true);
            }
            var brands = $('#srch option:selected');
            var selected = [];
            $(brands).each(function (index, brand) {
                selected.push([$(this).val()]);
            });
            fStr = createFriendsStr(selected);
            console.log(selected);
        }
    });

    $("#photoBtn").on("click", function () {
        $('#myInput').click();
    });

    $('input[name=file]').on("change", function () {
        readURL(this);
        $("#imgUpload").show();
        $("#clear").show();
    });


    var control = $('input[name=file]'),
        clearBn = $("#clear");

    clearBn.on("click", function () {
        control.replaceWith(control.val('').clone(true));
        $("#imgUpload").attr("src", "");
        $("#imgUpload").hide();
        clearBn.hide();
    });

    $("#searchFriendsBtn").on("click", function (e) {
        e.preventDefault();
        addFriends(userID, fStr);
    });

    $("#updatePost").on("click", function () {
        var input = $("#modalInput");
        var postId = input.attr("post-id");
        var postText = input.val();
        var isPrivate = $("#isPrivate").find(":selected").val();
        updatePost(postId, postText, isPrivate, userID, $("ul[post-id$='" + postId + "']"));
    });

    $("#postBtn").on("click", function (e) {
        e.preventDefault();
        var postTxt = $("#status_message").val();
        var isPrivate = $("#ddlPrivate").find(":selected").val();
        var data = new FormData();
        data.append("post_text", postTxt);
        data.append("file", files);
        data.append("user_id", userID);
        data.append("isPrivate", isPrivate);
        $.ajax({
            url: 'php/uploadPost.php',
            type: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function () {

                getPosts(userID);
            },
            error: function () {
                alert("There has been an error in submitting your post");
            },
            complete: function () {
                var control2 = $('input[name=file]');
                $("#clear").click();
                $("#status_message").val("");
                control2.replaceWith(control2.val('').clone(true));
            }


        });
    });

});

// Show comment textbox
$(document).on("click", "[id$='commentBtn']", function () {
    $(this).parent().parent().find("#txtComment").show();
});

// Like a post
$(document).on("click", "[id$='likeBtn']", function () {

    var isClicked = $(this).hasClass("btn-default");
    var postId = $(this).parent().parent().attr("post-id");

    if (isClicked) {
        $(this).removeClass("btn-default");
        $(this).addClass("btn-primary");
        like(true, userID, postId, 0, $(this))
    }
    else {
        var postLikeId = $(this).attr("post-like-id");
        $(this).addClass("btn-default");
        $(this).removeClass("btn-primary");
        like(false, userID, postId, postLikeId, $(this));
    }
});

// Insert a comment
$(document).on("keypress", "[id$='txtComment']", function (e) {
    var key = e.which;
    var obj;
    var isEmpty;
    if (key == 13)  // Enter
    {
        e.preventDefault();

        if ($(this).parent().find(".comments").length > 0) {
            obj = $(this).parent().find(".comments");
            isEmpty = false;
        }
        else {
            obj = $(this).parent().find('.post-values');
            isEmpty = true;
        }
        var postId = $(this).parent().attr("post-id");
        insertComment(userID, postId, $(this).val(), obj, isEmpty);
        return false;
    }

});

// Show editing options for my posts
$(document).on("mouseenter", ".posts", function () {
    $(this).find(".myPost").show();
});

// Hide editing options for my posts
$(document).on("mouseleave", ".posts", function () {
    $(this).find(".myPost").hide();
});

// Edit my post
$(document).on("click", "#editPost", function () {
    var postId = $(this).parent().parent().parent().attr("post-id");
    var postText = $(this).parent().parent().parent().find("#post-text").text();

    editPost(postId, postText);
});

// Remove my post
$(document).on("click", "#removePost", function () {
    var postId = $(this).parent().parent().parent().attr("post-id");
    $.ajax({
        url: 'php/deletePost.php',
        data: {user_id: userID, post_id: postId},
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            getPosts(userID);
        },
        error: function () {
            console.log("removePost error")
        },
        complete: function () {
            console.log("removePost complete")
        }
    })
});

// Show enlraged image
$(document).on("click", "#post-pic", function () {
    var imgPath = $(this).find('img').attr('src');
    if (imgPath != "")
        modalPic(imgPath);
});

// Show likes, bugged
$(document).on("click", "#seeLikeBtn", function () {
    $('[data-toggle="popover"]').popover();

});

function getPosts(userId) {
    $.ajax({
        url: 'php/getPosts.php',
        data: {user_id: userId},
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            if (response !== null || typeof(response) !== undefined) {
                var postWrapper = $("#postsWrapper");
                postWrapper.empty();
                $.each(response, function (key, val) {
                    var isLiked;
                    var comments = "";
                    var likeCount;
                    if (val.post_text === null || val.post_text === undefined)
                        val.post_text = "";
                    if (val.post_pic_path === null || val.post_pic_path === undefined)
                        val.post_pic_path = "";
                    if (val.comments !== undefined) {
                        if (val.comments.length > 0) {
                            comments += "<div class='well bs-component collapse comments in' id='postCollapse" + val.post_id + "'>";
                            $.each(val.comments, function (k1, v1) {
                                comments += "<ul class='list-group '>";
                                comments += "<h4 class='list-group-item-heading'>" + v1.userName + " <small> " + v1.comment_datetime + "</small></h4>";
                                comments += "<li class='list-group-item comment'>" + v1.comment + "</li>";
                                comments += "</ul>";
                            });
                            comments += "<br/></div>";
                        }
                    }
                    var likes = "";
                    if (val.likes !== undefined) {
                        likeCount = val.likes.length;
                        likes = createLikes(val.likes);
                    }
                    else {
                        likeCount = "";
                    }
                    isLiked = isLikedFunc(val.isLiked);

                    var hasPic = hasPicPath(val.post_pic_path);
                    var isMy = isMyPost(userID, val.user_id);
                    var editRemove;
                    if (isMy) {
                        editRemove = "<a id='editPost' class='myPost' style='display: none' href='#'><span class='glyphicon glyphicon-pencil'></span></a><a class='myPost' style='display: none' id='removePost' href='#'><span class='glyphicon glyphicon-remove' ></span></a>";
                    }
                    else {
                        editRemove = "";
                    }


                    postWrapper.append($("<ul href='#' class='list-group-item posts' style='cursor: default' post-id='" + val.post_id + "'>"
                        + "<h4 class='list-group-item-heading post'>" + val.userName + "<small> " + val.post_date + "</small> <small>" + editRemove + "</small></h4>"
                        + "<li class='list-group-item post-values'>"
                        + "<br/>"
                        + "<div id='post-text'>" + val.post_text + "</div>"
                        + "<br/>"
                        + "<div id='post-pic' style='" + hasPic + "'><img src='" + val.pathImg + "' class='img-thumbnail' id='post-pic-thumb' /></div>"
                        + "<br/>"
                        + "<hr/>"
                        + "<button class='btn " + isLiked + " glyphicon glyphicon-thumbs-up' id='likeBtn'> Like <span id='likeCount'>" + likeCount + "</span></button>"
                        + "<button class='btn btn-default glyphicon glyphicon glyphicon-comment' id='commentBtn'> Comment</button>"
                        + "<button class='btn btn-default' id='showComment' type='button' data-target='#postCollapse" + val.post_id + "' aria-expanded='false' aria-controls='postCollapse" + val.post_id + "' data-toggle='collapse'>Show Comments <span class='glyphicon glyphicon-menu-down'></span></button>"
                        + "<button class='btn btn-default' style='margin-left: 6em' data-content='" + likes + "' data-html='true' data-placement='top'   data-toggle='popover'  title='Post Likes' type='button' id='seeLikeBtn'>Likes</button>"
                        + "</li>"
                        + comments
                        + "<input type='text' id='txtComment' placeholder='Write a comment...' class='form-control' style='display: none;'/>"
                        + "</ul><br/>"));
                });
                console.log(JSON.stringify(response));
            }
            else {
                console.log("empty response")
            }
        },
        error: function (response) {
            console.log("getPosts error");
        },
        complete: function () {
            console.log("getPosts complete");
        }
    });
}

// List of like of a post
function createLikes(likes) {
    var like = "";
    if (likes.length > 0) {
        for (var i = 0; i < likes.length; i++) {
            like += "<p>" + likes[i].userName + "</p>";
        }
    }
    return like;
}


// Get friends as a string
function createFriendsStr(friends) {
    if (friends.length > 0) {
        var friendsStr = "";
        for (var i = 0; i < friends.length; i++) {
            if (i == 0) {
                friendsStr += friends[i];
            }
            else {
                friendsStr += ', ' + friends[i];
            }
        }
        return friendsStr;
    }
    return "";
}

// Add a friend function
function addFriends(userId, friendsStr) {
    $.ajax({
        url: 'php/addFriends.php',
        data: {user_id: userId, friendStr: friendsStr},
        type: 'POST',
        dataType: 'json',
        success: function () {
            getPosts(userId);
            getUsers(userId);
        },
        error: function () {
            console.log("addFriends error")
        },
        complete: function () {
            console.log("addFriends complete")
        }
    });
}

// Add friends and potentials (to the search box
function getUsers(userId) {
    $.ajax({
        url: 'php/getUsers.php',
        data: {user_id: userId},
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            var search = $("#srch");
            // Add to search box
            search.multiselect('rebuild');
            $.each(response[0].friends, function (key, val) {
                search.append($("<option value=" + val.userId + ">" + val.userName + "</option>"));
            });
            search.multiselect('rebuild');
            var friendsList = $("#SuggestionBox");

            //Add to my list of friends
            if (response[0].myFriends.length > 0) {
                friendsList.find(".friend-item").empty();
                $.each(response[0].myFriends, function (k, v) {
                    friendsList.find(".list-group").append($("<a class='list-group-item friend-item'>" +
                        "<strong>" + v.userName + "</strong>" +
                        "</a>"));
                });
                friendsList.show();
            } // no friends, hide the box
            else {
                friendsList.hide();
            }
            console.log(response)
        },
        error: function () {
            console.log("getUsers Error");
        },
        complete: function () {
            console.log("getUsers Complete");
        }
    });
}

// Actually update a post
function updatePost(postId, postText, isPrivate, userId, postObj) {
    $.ajax({
        url: 'php/updatePost.php',
        data: {user_id: userId, post_id: postId, post_text: postText, private: isPrivate},
        type: 'POST',
        dataType: 'json',
        success: function () {
            postObj.find("#post-text").text(postText);
        },
        error: function () {
            console.log("updatePost Error");
        },
        complete: function () {
            console.log("updatePost complete");
            $("#AfekaBookModal").modal("toggle");
        }

    })
}

// Populate the edit box modal
function editPost(postId, postText) {
    var content = "<p><strong>Post Text: </strong><input id='modalInput' type='text' value='" + postText + "' post-id='" + postId + "'/></p>"
    content += "<br/>";
    content += "<p><strong>Post Privacy: </strong><select id='isPrivate'><option value ='0'>Public</option><option value='1'>Private</option></select></p>";
    content += "<br/>"

    $("#modalBody").html(content);
    $("#AfekaBookModal").modal();
}

// Show full size image
function modalPic(imagePath) {
    $("#imgModal").find("#imgInModal").attr("src", imagePath);
    $("#imgModal").modal();
}

function isMyPost(userId, postUserId) {
    if (userId == postUserId) {
        return true;
    }
    return false;
}


function hasPicPath(picPath) {
    if (picPath == "" || picPath == undefined) {
        return "display:none;"
    }
    else {
        return "cursor: pointer";
    }
}

// Get appropriate like button
function isLikedFunc(isLike) {
    if (isLike == 0) {
        return "btn-default";
    } else {
        return "btn-primary";
    }
}

// Handle insert comment
function insertComment(userId, postId, val, commentObj, isEmpty) {
    $.ajax({
        url: 'php/insertComment.php',
        data: {user_id: userId, post_id: postId, val: val},
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            if (!isEmpty) {
                commentObj.append($("<ul class='list-group '>"
                    + "<h4 class='list-group-item-heading'>" + response.userName + " <small> " + response.datetime + "</small></h4>"
                    + "<li class='list-group-item comment'>" + response.comment + "</li>"
                    + "</ul><br/>"));
            }
            else {
                commentObj.after($("<div class='well bs-component collapse comments' id='postCollapse" + postId + "'>"
                    + "<ul class='list-group '>"
                    + "<h4 class='list-group-item-heading'>" + response.userName + " <small> " + response.datetime + "</small></h4>"
                    + "<li class='list-group-item comment'>" + response.comment + "</li>"
                    + "</ul>"
                    + "<br/></div>"))
            }
        },
        error: function (response) {
            console.log("getPosts error");
        },
        complete: function () {
            console.log("getPosts complete");
            commentObj.parent().find("#showComment").click();
            commentObj.parent().find("#txtComment").val("");
        }
    });
}

// Handle like and unlike
function like(isLike, userId, postId, postLikeId, likeObj) {
    if (isLike) {
        $.ajax({
            url: 'php/insertLike.php',
            data: {user_id: userId, post_id: postId},
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response !== null) {
                    likeObj.attr("post-like-id", response[0].postLikeId);
                    var likeCount = response[0].likeCount;
                    likeObj.find("#likeCount").text(likeCount);
                }
                //need to do: create a list of the people who liked this post.
            },
            error: function () {
                console.log("like error");
            },
            complete: function () {
                console.log("like complete");
            }

        });
    }
    else {
        var likeCount = "";
        $.ajax({
            url: 'php/deleteLike.php',
            data: {post_like_id: postLikeId, post_id: postId},
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response !== false) {
                    likeObj.attr("post-like-id", "");
                    likeCount = response[0].likeCount;
                    if (likeCount <= 0) {
                        likeCount = ""
                    }
                }
                likeObj.find("#likeCount").text(likeCount);
                //need to do: create a list of the people who liked this post.
            },
            error: function () {
                console.log("like error");
            },
            complete: function () {
                console.log("like complete");
            }

        });
    }
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imgUpload').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
        files = input.files[0];
    }
}


