import React from 'react';
import ReactDOM from 'react-dom';
import DayChart from './components/DayChart';
import MonthChart from './components/MonthChart';

const dayElement = document.getElementById('sensor-day-chart');
ReactDOM.render(<DayChart {...(dayElement.dataset)}/>, dayElement);

const monthElement = document.getElementById('sensor-month-chart');
ReactDOM.render(<MonthChart {...(monthElement.dataset)}/>, monthElement);
