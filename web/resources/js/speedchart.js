const gaugeOptions = {
    chart: {
        type: 'solidgauge'
    },

    title: null,

    pane: {
        center: ['50%', '75%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    exporting: {
        enabled: false
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#DF5353'], // red
            [0.4, '#DDDF0D'], // yellow
            [0.6, '#55BF3B'] // green
        ],
        lineWidth: 0,
        tickWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -50
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 16,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

const maxLimit = 42000;

// The speed gauge
const chartSpeed = Highcharts.chart('container-speed', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: maxLimit,
        title: {
            text: 'Speed'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Speed',
        data: [0],
        dataLabels: {
            format:
                '<div style="text-align:center; margin-top: -85px">' +
                '<span style="font-size:25px">{y}</span><br/>' +
                '<span style="font-size:12px;opacity:0.4">msg/sec</span>' +
                '</div>'
        },
        tooltip: {
            valueSuffix: ' msg/sec'
        }
    }]

}));

var resetSpeed = true;

// Bring life to the dials
export const updateSpeed = (speed) => {
    // Speed
    let point,
        newVal = speed;

    if (!chartSpeed) {
        return;
    }

    point = chartSpeed.series[0].points[0];

    if (newVal < 0) newVal = 0;
    if (newVal > maxLimit) newVal = maxLimit;

    point.update(newVal);
    resetSpeed = false;
};

// Reset speed by timer
setInterval(() => {
    if (resetSpeed) {
        updateSpeed(0);
    } else {
        resetSpeed = true;
    }
}, 2000);
