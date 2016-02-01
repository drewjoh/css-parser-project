<!DOCTYPE html>
<html>
    <head>
        <title>CSS Parser</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
        <link href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>
        
    </head>
    <body>

        <div class="container">
            
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            
            <div class="jumbotron">
                <h1>CSS Stats</h1>
                <p class="lead">Get cool stats on about your CSS file.</p>
                <?= Illuminate\Support\Facades\Input::get('error'); ?>
                <p>
                    <form action="{{ url('upload') }}" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <input type="file" name="css_file" style="display: inline; width: 180px;">
                        </div>
                        <button type="submit" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload CSS File</button>
                    </form>
                </p>
            </div>
    
        </div> <!-- /container -->
    
    </body>
</html>
