import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Line } from 'react-chartjs-2';

const DayChart = props => {
    const [data, setData] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [hasData, setHasData] = useState(false);

    useEffect(() => {
        async function fetchData() {
            const response = await axios('/api/devices/' + props.device + '/sensors/' + props.sensor + '/day');

            setData(response.data);
            setHasData(true);
        }
        if (!isLoading) {
            setIsLoading(true);
            fetchData();
        }
    });

    if (hasData) {
        const d = new Date();
        d.setDate(d.getDate() - 1);

        const plotData = {
            datasets: [
                {
                    label: data.device.name,
                    data: data.log.map(item => ({
                        x: new Date(item.created_at),
                        y: item.state
                    })),
                    fill: false,
                    pointRadius: 1,
                    pointHitRadius: 4,
                    borderColor: 'rgba(20, 20, 250, 0.4)',
                    backgroundColor: 'rgba(20, 20, 250, 0.2)',
                }
            ],
        };

        const plotOptions = {
            scales: {
                xAxes: [{
                    type: 'linear',
                    position: 'bottom',
                    ticks: {
                        callback: (value, index, values) => (new Date(value)).toLocaleString('nl-NL'),
                        minRotation: 32,
                        suggestedMin: new Date(),
                        suggestedMax: 0
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    title: item => (new Date(item[0].xLabel)).toLocaleString('nl-NL')
                }
            }
        };

        return (
            <Line data={plotData} height={100} options={plotOptions}/>
        );
    }
    return <div>Loading day chart...</div>;
}

export default DayChart;
