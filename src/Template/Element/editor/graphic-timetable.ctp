<script>
class GraphicTimeTable {
    constructor(canvas) {
        this.canvas = canvas;
        this.width = this.canvas.width();
        this.height = this.canvas.height();

        this.stops = new Array();
        this.datasets = new Array();

        this._initChart();
    }

    _initChart() {
        this.chart = new Chart(this.canvas, {
            type: 'scatter',
            data: {
                labels: this.stops,
                datasets: this.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                dragData: true,
                dragX: false,
                onDragEnd: (e, datasetIndex, index, object) => {
                    if(object.ref !== undefined) {
                        $(object.ref).val(moment(object.y).format('HH:mm:00'));
                    }
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false
                },
                elements: {
                    line: {
                        tension: 0
                    }
                },
                scales: {
                    xAxes: [{
                        position: 'top',
                        ticks: {
                            stepSize: 1,
                            beginAtZero: true,
                            autoSkip: false,
                            callback: (value) => {
                                return this.stops[parseInt(value)];
                            }
                        }
                    }],
                    yAxes: [{
                        type: 'linear',
                        ticks: {
                            reverse: true,
                            min: moment('1970-02-01 00:00:00').valueOf(),
                            max: moment('1970-02-01 23:59:59').valueOf(),
                            stepSize: 0.9e+6,
                            beginAtZero: false,
                            autoSkip: false,
                            callback: (value) => {
                                var date = moment(value);
                                return date.format('HH:mm');
                            }
                        }
                    }]
                },
                animation: {
                    onComplete: function () {
                        let ctx = this.chart.ctx;
                        ctx.fillStyle = '#000';

                        if(this.chartArea === undefined) {
                            return false;
                        }

                        let offsetX = this.chartArea.left;
                        let factorX = (this.chartArea.right - offsetX) / (this.data.labels.length - 1).toFixed(1);

                        let offsetY = this.chartArea.top;
                        let factorY = (this.chartArea.bottom - offsetY) / 86399000.0;

                        this.data.datasets.forEach((dataset) => {
                            dataset.data.forEach((point) => {
                                let Xadd = 0, Yadd = 0;

                                if(point.type == 'arrival') {
                                    ctx.textBaseline = 'bottom';
                                    ctx.textAlign = 'right';
                                    Xadd -= 5;
                                    Yadd -= 7;
                                } else if(point.type == 'departure') {
                                    ctx.textBaseline = 'top';
                                    ctx.textAlign = 'left';
                                    Xadd += 5;
                                    Yadd += 7;
                                }

                                let y = (point.y - moment('1970-02-01 00:00:00').valueOf()).toFixed(1);
                                ctx.fillText(moment(point.y).format('mm'), point.x * factorX + offsetX + Xadd, y * factorY + offsetY + Yadd);
                            });
                        });
                    }
                }
            }
        });
    }

    addStop(name) {
        this.stops.push(name);
        return this.stops.length - 1;
    }

    removeStop(index) {
        this.stops.splice(index, 1);
        return this;
    }

    addTrip(name, color) {
        let dataset = {
            label: name,
            fill: false,
            showLine: true,
            borderColor: color,
            data: []
        };

        this.datasets.push(dataset);

        return this.datasets.length - 1;
    }

    addStopTime(tripIndex, stop, arrivalTime, departureTime, arrivalInputRef, departureInputRef) {
        let stopIndex = this.stops.indexOf(stop);

        let arrival = {
            x: stopIndex,
            y: moment('1970-02-01 ' + arrivalTime).valueOf(),
            type: 'arrival',
            ref: arrivalInputRef
        };

        let departure = {
            x: stopIndex,
            y: moment('1970-02-01 ' + departureTime).valueOf(),
            type: 'departure',
            ref: departureInputRef
        };

        this.datasets[tripIndex].data.push(arrival);
        this.datasets[tripIndex].data.push(departure);

        return [arrival, departure];
    }

    removeStopTime(tripIndex, stopIndex) {
        let dataIndices = new Array();

        $.each(this.datasets[tripIndex].data, function (index, object) {
            alert(object.x + ' ' + stopIndex);
            if(object.x == stopIndex) {
                dataIndices.push(index);
            }
        });

        $.each(dataIndices, function (index) {
            alert(dataIndices[index]);
            this.datasets[tripIndex].data.splice(dataIndices[index], 1);
        });
    }

    updateGraph() {
        this.chart.options.scales.xAxes[0].ticks.max = this.stops.length - 1;

        this.chart.update(true);
    }
}
</script>