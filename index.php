<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afeka KickStarter</title>
    <link rel="stylesheet" href="css/bootstrapmodal.css" type="text/css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/app.css"/>
    <script src="lib/jquery-1.12.4.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
    <script type="text/javascript" src="js/bootstrapmodal.min.js"></script>


</head>
<body>
<?php
session_start();
include('php/header.php');
if (!(isset($_SESSION['userID']))) {
    ?>
    <script type="text/javascript">
        window.location.href = 'Login.php';
    </script>
<?php
} else {
if (!strpos($_SERVER['REQUEST_URI'], 'userID')) {
?>
    <script type="text/javascript">
        window.location.href = 'index.php?userID=<?php echo $_SESSION['userID'] ?>';
    </script>
    <?php
}
}
?>
<div class="container">
    <div class="page-header">
        <h1>Afeka KickStarter Login
            <small></small>
        </h1>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div id="SuggestionBox">
                <div class="list-group">
                    <a class="list-group-item active">Friends List</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 share-box-style">
            <form action="#" id="uploadPost" method="post" role="form" enctype="multipart/form-data"
                  class="facebook-share-box">
                <div class="share">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-file"></i> <strong>Update Status</strong></div>
                        <div class="panel-body">
                            <div class="">
                                <textarea name="message" cols="40" rows="10" id="status_message"
                                          class="form-control message" style="height: 62px; overflow: hidden;"
                                          placeholder="What's on your mind?"></textarea>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input id="myInput" name="file" type="file" style="display: none;"/>
                                        <button id="photoBtn" type="button" class="btn btn-default btn-sm"><i
                                                    class="glyphicon glyphicon-picture"></i> Photo
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="privacy" id="ddlPrivate"
                                                class="form-control privacy-dropdown pull-left input-sm"
                                                title="Please Select">
                                            <option value="0">Public</option>
                                            <option value="1">Private</option>
                                        </select>

                                        <input type="submit" name="submit" id="postBtn" value="Post"
                                               class="btn btn-primary btn-sm" style="margin-left: 0.8em"/>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img id="imgUpload" class="img-thumbnail" style="display: none;"
                                                     width="150" height="150" src="#" alt="..."">
                                            </a>
                                            <a href="#" id="clear" style="display: none;">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div id="postsWrapper"></div>

        </div>
    </div>

    <div id="AfekaBookModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span
                                class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title">Edit Post</h4>
                </div>
                <div id="modalBody" class="modal-body"></div>
                <div class="modal-footer">
                    <button id="updatePost" class="btn btn-success text-center" style="cursor: pointer;">Update Post
                    </button>
                    <button id="cancelUpdtae" class="btn btn-danger text-center"
                            style="display: none; cursor: pointer;">Cancel
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div id="likesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span
                                class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title">Post Likes</h4>
                </div>
                <div id="modalBody" class="modal-body"></div>
            </div>
        </div>

    </div>

    <div id="imgModal" class="modal fade pop-up-1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel-1"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myLargeModalLabel-1"></h4>
                </div>
                <div class="modal-body">
                    <img id="imgInModal" src="#" class="img-responsive img-rounded center-block" alt="">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal mixer image -->

</body>


</html>