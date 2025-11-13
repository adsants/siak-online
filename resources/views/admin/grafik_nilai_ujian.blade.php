@extends('layouts.app')
 
@section('content')

            <div class="card">
                <div class="card-body">
                    <div id="top_x_div" style="width: 100%; height: 400px;"></div>
                </div>
            </div>


        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            var data = new google.visualization.arrayToDataTable([
                ['', 'Total Peserta'],
                {!!$dataChart!!}
            ]);

            var options = {
                legend: { position: 'none' },
                axes: {
                    x: {
                        0: { side: 'top', label: 'Nilai'} // Top x-axis.
                    }
                },
                bar: { groupWidth: "80%" }
            };

            var chart = new google.charts.Bar(document.getElementById('top_x_div'));
            // Convert the Classic options to Material options.
            chart.draw(data, google.charts.Bar.convertOptions(options));
        };
        </script>
@endsection