var express = require('express')
const request = require('request');
var app = express()

app.get('/', (req, res) => {

    const options = {
        url: 'https://atlas.mapmyindia.com/api/places/nearby/json?keywords=coffee&refLocation=12.927162532132044,77.69798034515283',  
        headers: {
          "Authorization": "bearer 79c9f6ed-5b05-40e2-a815-52bf2ccd4eb2",
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Credentials": true,
          "Access-Control-Allow-Methods": "*"
        }
    };

    function callback(error, response, body) {
        if (!error && response.statusCode == 200) {
          const info = JSON.parse(body);
          res.send(info);
        }
    }

    request(options, callback);
})

const server = app.listen(8000, () => {
    console.log('Listening on port %s', server.address().port)
});