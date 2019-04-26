import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Line } from 'react-chartjs-2';

const PeriodChart = props => {
    const [data, setData] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [hasData, setHasData] = useState(false);

    useEffect(() => {
        async function fetchData() {
            const response = await axios('/api/devices/' + props.device + '/sensors/' + props.sensor + '/' + props.period);

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
            labels: data.log.map(item => new Date(item.created_date)),
            datasets: [
                {
                    label: data.device.name + " average",
                    data: data.log.map(item => ({
                        x: new Date(item.created_date),
                        y: item.average
                    })),
                    fill: false,
                    pointRadius: 1,
                    pointHitRadius: 4,
                    borderColor: 'rgba(20, 20, 250, 0.4)',
                    backgroundColor: 'rgba(20, 20, 250, 0.2)',
                },
                {
                    label: data.device.name + " minimum",
                    data: data.log.map(item => ({
                        x: new Date(item.created_date),
                        y: item.minimum
                    })),
                    fill: '+1',
                    pointRadius: 1,
                    pointHitRadius: 4,
                    borderColor: 'rgba(20, 20, 50, 0.4)',
                    backgroundColor: 'rgba(20, 20, 50, 0.2)',
                },
                {
                    label: data.device.name + " maximum",
                    data: data.log.map(item => ({
                        x: new Date(item.created_date),
                        y: item.maximum
                    })),
                    fill: false,
                    pointRadius: 1,
                    pointHitRadius: 4,
                    borderColor: 'rgba(20, 20, 50, 0.4)',
                    backgroundColor: 'rgba(20, 20, 50, 0.2)',
                }
            ],
        };

        const plotOptions = {
            scales: {
                xAxes: [{
                    position: 'bottom',
                    ticks: {
                        callback: (value, index, values) => (new Date(value)).toLocaleDateString('nl-NL'),
                        minRotation: '32'
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    title: item => (new Date(item[0].label)).toLocaleDateString('nl-NL')
                }
            }
        };

        return (
            <Line data={plotData} height={100} options={plotOptions}/>
        );
    }
    return <div>Loading {props.period} chart...</div>;
}

export default PeriodChart;
