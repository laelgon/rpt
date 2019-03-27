import React, { Component } from 'react';
import './App.css';
import 'bootstrap/dist/css/bootstrap.css';


class App extends Component {

  state = {
    messages: [],
    socket: null
  }

  constructor(props) {
    super(props)
    this.handleClick = this.handleClick.bind(this)

    let state = this.state;

    state.socket = new WebSocket('ws://localhost:8080');

    this.state = state

    let self = this

    this.state.socket.onmessage = function(e) {

      let msg = e.data;

      let state = self.state

      state.messages.push(msg)

      if (state.messages.length > 17) {
        state.messages.shift()
      }

      self.setState(state)
    };
  }

  handleClick(e) {
    let input = document.getElementById("my-input")
    this.state.socket.send(input.value)
    input.value = ""
  }

  render() {

    return (
      <div className="App">

        <div className="terminal">
          {this.state.messages.map((message) => this.processMessage(message))}
        </div>

        <div className="control">
          <input id="my-input" type="text" className="form-control" />
          <button type="button" className="btn btn-primary" onClick={this.handleClick}>Submit</button>
        </div>

      </div>
    );
  }

  processMessage(message) {

    let decoded = JSON.parse(message)

    if (decoded.type === "select") {
      return (
        <div>
          <p>{decoded.prompt}</p>
          <ol>
            {decoded.options.map((option) => <li>{option}</li>)}
          </ol>
        </div>
      )
    }

  }

}

export default App;
