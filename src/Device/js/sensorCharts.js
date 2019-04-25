import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { Scatter } from 'react-chartjs-2';

const Chart = props => {
    const [data, setData] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [hasData, setHasData] = useState(false);

    useEffect(() => {
        async function fetchData() {
            const response = await axios('/api/devices/' + props.device + '/sensors/' + props.sensor + '/log');

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
        console.log(d);

        const plotData = {
            datasets: [
                {
                    label: 'OpenTherm Gateway',
                    data: data.log.map(item => ({
                        x: Math.floor((new Date(item.created_at) - d) / 1000),
                        y: item.state
                    }))
                }
            ],
        };
        console.log(plotData);

        return (
            <Scatter data={plotData} height={50} options={{ showLines: true }}/>
        );
    }
    return <div>{props.device} - {props.sensor}</div>;
}

const controlElement = document.getElementById('sensor-chart');

ReactDOM.render(
    <Chart {...(controlElement.dataset)}/>,
    controlElement
)
