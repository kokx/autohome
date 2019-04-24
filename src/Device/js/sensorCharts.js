import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { LineChart, Line, CartesianGrid, XAxis, YAxis } from 'recharts';

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
        return (
            <LineChart width={1000} height={400} data={data.log}>
                <Line type="monotone" dataKey="state" stroke="#8884d8" />
                <CartesianGrid stroke="#ccc" />
                <XAxis dataKey="createdAt" interval="preserveStartEnd" />
                <YAxis />
            </LineChart>
        );
    }
    return <div>{props.device} - {props.sensor}</div>;
}

const controlElement = document.getElementById('sensor-chart');

ReactDOM.render(
    <Chart {...(controlElement.dataset)}/>,
    controlElement
)
