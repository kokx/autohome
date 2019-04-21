import React, { useState } from 'react';
import ReactDOM from 'react-dom';

const TempControl = props => {
  const state = parseInt(props.state, 10);
  const [temp, setTemperature] = useState(state);
  return (
    <div className="input-group">
      <div className="input-group-prepend">
        <button
          type="button"
          className="btn btn-outline-secondary"
          onClick={() => setTemperature(temp - 0.5)}>-</button>
      </div>
      <input
        id="actuator_state"
        className="form-control"
        name="state"
        type="number"
        value={temp}
        onChange={(e) => setTemperature(e.target.value)}
        step="0.5"/>
      <div className="input-group-append">
        <button
          type="button"
          className="btn btn-outline-secondary"
          onClick={() => setTemperature(temp + 0.5)}>+</button>
      </div>
    </div>
  );
};

const controlElement = document.getElementById('tempcontrol');

ReactDOM.render(
  <TempControl {...(controlElement.dataset)}/>,
  controlElement
);
