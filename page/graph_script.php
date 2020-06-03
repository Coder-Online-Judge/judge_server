<?php 
    $verdictData = $JudgeScript->getCountSubmissionVerdict();
    $languageData = $JudgeScript->getCountSubmissionLanguage();

    $languagDataPoints = array();
    foreach ($languageData as $key => $value) {
        $tmpArray = array();
        $tmpArray["label"]=$value['languageName'];
        $tmpArray['y'] = $value['total'];
        array_push($languagDataPoints, $tmpArray);
    }

    $verdictDataPoints = array();
    foreach ($verdictData as $key => $value) {
        $tmpArray = array();
        $tmpArray["label"]=$value['verdictDescription'];
        $tmpArray['y'] = $value['total'];
        array_push($verdictDataPoints, $tmpArray);
    }

?>


<script type="text/javascript">
	CanvasJS.addColorSet("greenShades",
                [//colorSet Array

                "#8e44ad",
                "#4185F3",
                "#c0392b",
                "#2980b9",
                "#c0392b"                
                ]);


var chart = new CanvasJS.Chart("chartContainer2", {
    animationEnabled: true,
    title:{
        text: "Language Submission"
    },

    data: [{
        type: "pie",
        showInLegend: "true",
        legendText: "{label}",
        indexLabelFontSize: 16,
        indexLabel: "{label} - #percent%",
        yValueFormatString: "#,##0",
        dataPoints: <?php echo json_encode($languagDataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});

chart.render();


var chart = new CanvasJS.Chart("chartContainer1", {
    animationEnabled: true,
    title:{
        text: "Language Submission"
    },

    data: [{
        type: "pie",
        showInLegend: "true",
        legendText: "{label}",
        indexLabelFontSize: 16,
        indexLabel: "{label} - #percent%",
        yValueFormatString: "#,##0",
        dataPoints: <?php echo json_encode($verdictDataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();

var chart = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Simple Line Chart"
	},
	axisY:{
		includeZero: false
	},
	data: [{        
		type: "line",
      	indexLabelFontSize: 16,
		dataPoints: [
			{ y: 450 },
			{ y: 414},
			{ y: 520, indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },
			{ y: 460 },
			{ y: 450 },
			{ y: 500 },
			{ y: 480 },
			{ y: 480 },
			{ y: 410 , indexLabel: "\u2193 lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
			{ y: 500 },
			{ y: 480 },
			{ y: 510 }
		]
	}]
});
chart.render();

</script>