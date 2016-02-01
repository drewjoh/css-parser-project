<!DOCTYPE html>
<html>
    <head>
        <title>CSS Parser</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
        <link href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>

        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">

          // Load the Visualization API and the piechart package.
          google.load('visualization', '1.0', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.setOnLoadCallback(drawChart);

          // Callback that creates and populates a data table,
          // instantiates the pie chart, passes in the data and
          // draws it.
          function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            data.addRows([
              ['Data - bytes', {{ $data->data_size }}],
              ['Whitespace - bytes', {{ $data->whitespace_size }}]
            ]);

            // Set chart options
            var options = {'title':'CSS File Data',
                           'width':400,
                           'height':300};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
          }
        </script>
        
    </head>
    <body>

        <div class="container">
    
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
    
            <div class="jumbotron">
                <h1>CSS Stats</h1>
            </div>

            <div class="row">
                
                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr>
                            <td>File Size</td>
                            <td><?= number_format($data->full_size); ?> bytes</td>
                        </tr>
                        <tr>
                            <td>Data</td>
                            <td><?= number_format($data->data_size); ?> bytes</td>
                        </tr>
                        <tr>
                            <td>Whitespace</td>
                            <td><?= number_format($data->whitespace_size); ?> bytes</td>
                        </tr>
                        <tr>
                            <td>Unique Selectors</td>
                            <td><?= number_format($data->unique_selector_count); ?></td>
                        </tr>
                        <tr>
                            <td># of Properties</td>
                            <td><?= number_format($data->property_count); ?></td>
                        </tr>
                        <tr>
                            <td># CSS Blocks</td>
                            <td><?= number_format($data->block_count); ?></td>
                        </tr>
                        <tr>
                            <td>Unique Colors</td>
                            <td><?= number_format($data->color_count); ?></td>
                        </tr>
                        <tr>
                            <td>Unique Background Colors</td>
                            <td><?= number_format($data->background_color_count); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="text-center">Colors Used</div>
                                <?php foreach($data->colors as $color): ?>
                                    <span style="background-color: <?= $color; ?>; height: 10px; width: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <?php endforeach; ?>
                                <?php foreach($data->background_colors as $color): ?>
                                    <span style="background-color: <?= $color; ?>; height: 10px; width: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <div id="chart_div"></div>
                    <table class="table table-striped">
                        <tr>
                            <td># of Fonts</td>
                            <td><?= number_format($data->font_count); ?></td>
                        </tr>
                        <tr>
                            <td>Fonts</td>
                            <td>
                                <?php foreach($data->fonts as $font): ?>
                                <div style="font-family: <?= $font; ?>"><?= $font; ?></div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div> <!-- /container -->
    
        
        
    </body>
</html>
