import React from 'react';
import ReactDOM from 'react-dom';
import DayChart from './components/DayChart';
import PeriodChart from './components/PeriodChart';

const dayElement = document.getElementById('sensor-day-chart');
ReactDOM.render(<DayChart {...(dayElement.dataset)}/>, dayElement);

const monthElement = document.getElementById('sensor-month-chart');
ReactDOM.render(<PeriodChart period="month" {...(monthElement.dataset)}/>, monthElement);

const yearElement = document.getElementById('sensor-year-chart');
ReactDOM.render(<PeriodChart period="year" {...(yearElement.dataset)}/>, yearElement);
