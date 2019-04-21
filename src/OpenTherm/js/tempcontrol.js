import React from 'react';
import ReactDOM from 'react-dom';

const TempControl = props => {
  console.log(props.state);
  return <div className="input-group">
            <div className="input-group-prepend">
              <button className="btn btn-outline-secondary">-</button>
            </div>
              <input id="actuator_state" className="form-control" name="state" type="number" value="32.00" step="0.5"/>
            <div className="input-group-append">
              <button className="btn btn-outline-secondary">+</button>
            </div>
          </div>;
};

const controlElement = document.getElementById('tempcontrol');

ReactDOM.render(
  <TempControl {...(controlElement.dataset)}/>,
  controlElement
);
