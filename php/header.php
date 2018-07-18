

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
            </button>
            <a class="navbar-brand" href="/">Afeka Book</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><p class="navbar-text" id="userName"></p><span id="userIcon" class="glyphicon glyphicon-user"></span> </li>
                <li><a href="/php/logout.php">Logout</a></li>
            </ul>
            <form class="navbar-form">
                <div class="form-group wider" style="display:inline;">
                    <div class="input-group">
                        <select type="text" class="form-control" multiple="multiple" placeholder="Search" id="srch">
                        </select>
                        <button id="searchFriendsBtn" class="btn btn-default" style="" type="submit"><span class="glyphicon glyphicon-ok"></span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>