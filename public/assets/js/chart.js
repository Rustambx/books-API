$(document).ready(function() {

	// Bar Chart
	
	Morris.Bar({
		element: 'bar-charts',
		data: [
			{ y: '2013', a: 100, b: 90 },
			{ y: '2014', a: 75,  b: 65 },
			{ y: '2015', a: 50,  b: 40 },
			{ y: '2016', a: 75,  b: 65 },
			{ y: '2017', a: 50,  b: 40 },
			{ y: '2018', a: 75,  b: 65 },
			{ y: '2019', a: 100, b: 90 }
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Общая прибыль', 'Итоговый результат'],
		lineColors: ['#0f6fd8 ','#73ACEC '],
		lineWidth: '3px',
		barColors: ['#0f6fd8 ','#73ACEC '],
		resize: true,
		redraw: true
	});
	
	// Line Chart
	
	Morris.Line({
		element: 'line-charts',
		data: [
			{ y: '2013', a: 50, b: 90 },
			{ y: '2014', a: 75,  b: 65 },
			{ y: '2015', a: 50,  b: 40 },
			{ y: '2016', a: 75,  b: 65 },
			{ y: '2017', a: 50,  b: 40 },
			{ y: '2018', a: 75,  b: 65 },
			{ y: '2019', a: 100, b: 50 }
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Общая распродажа', 'Общий доход'],
		lineColors: ['#0f6fd8 ','#73ACEC '],
		lineWidth: '3px',
		resize: true,
		redraw: true
	});
		
});