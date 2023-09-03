<?php

/**@var $model \app\models\Crypto */

\app\assets\ChartJsAsset::register($this);



?>


<canvas id="myChart" width="400" height="400"></canvas>


<?php

//$currentDateTime = new DateTime();
//$currentDateTime->setTime(0, 0, 0);
//$unixTimestamp = $currentDateTime->getTimestamp();
//$start = $unixTimestamp * 1000;
//
//$current_time = date("H:i:s");
//$datetime = new DateTime($current_time);
//$unix_timestamp = $datetime->getTimestamp();
//$end = $unix_timestamp *1000;

$urlDataChart = "https://api.coincap.io/v2/assets/{$model->assets->asset_id}/history?interval=d1";
$script = <<<JS

    function getChart() {
        console.log('chart1234');
        
        let myPromise = new Promise(function(myResolve, myReject) {
            $.ajax({
                type: "GET",
                url: "{$urlDataChart}",
                headers: {"Authorization": "Bearer f96bf173-4bc7-47ee-b356-f22ce5566ad1"},
                success: function(response) {
                    myResolve(response);
                },
                error: function(error) {
                    myReject(error);
                }
            });
        });
        
        myPromise.then(
            function(value) {
                console.log(value);                
                const currentDate = new Date();
                
                const data = value?.data;                
                let _labels = [];
                let _data = [];
                
                data.map((obj) => {
                    let dt = new Date(obj?.time);
                    let timeDifference = currentDate - dt; 
                    let daysDifference = timeDifference / (1000 * 60 * 60 * 24);
                    
                    if (daysDifference <= 30 ) {
                        let label = dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        _labels.push(label);
                        _data.push(parseFloat(obj?.priceUsd));
                    }
                })
                
                const dataChart = {
                    labels: _labels,
                    datasets: [{
                        label: "Price USD #Last 30 Days",
                        data: _data,
                        borderColor: "rgba(75, 192, 192, 1)", // Line color
                        borderWidth: 2, // Line width
                        fill: true, // Fill the area under the line
                        backgroundColor: "rgba(75, 192, 192, 0.2)" 
                    }]
                };
        
                // Configuration options
                const chartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display:false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display:true
                            }   
                        }]
                    }
                };
        
                // Get the canvas element and create the line chart
                const ctx = document.getElementById('myChart').getContext('2d');
                const myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: dataChart,
                    options: chartOptions
                });
          },
            function(error) {
                console.log(error);
            }
        );
    }

    $(document).ready(function() {
        getChart();
        
        
        
        
    })
    
JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>
