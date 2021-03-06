var express = require('express')
const request = require('request');
var app = express();
const port = process.env.PORT || 3000

app.use(function(req, res, next) {
  res.header("Access-Control-Allow-Origin", "*"); // update to match the domain you will make the request from
  res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
  next();
});

app.get('/', (req, res) => {
  const { query, current_lat, current_lng } = req.query;
  const optionsPost = {
    url:'https://outpost.mapmyindia.com/api/security/oauth/token', 
    headers: {'content-type' : 'application/x-www-form-urlencoded'},
    form: {
      grant_type: 'client_credentials',
      client_id: 'R1COQ57r0_UddN-80_NLFt89BHEFeX7cTXmwVG2tdiT06OyXbOqgukHFpt3xfxOm-tpo5Y6JKN431cJTLTE6Zw==',
      client_secret: '9K_q_9Q2GHMAbyNdcof10gRcszQGZcpOfzv62cEwJ_Pqpd-_GsP_53Qy-hi6k1x-3RJohmyoMwOOZ0mR9Hh6KYmU695pGdj8'
    }
  }
  request.post( optionsPost, function postCallback(error, response, body) {
    if (!error && response.statusCode == 200) {
      const { access_token, token_type } = JSON.parse(body);
      const optionsGet = {
          url: "https://atlas.mapmyindia.com/api/places/nearby/json?keywords="+query+"&refLocation="+current_lat+","+current_lng,         
          headers: {
            "Authorization": token_type+" "+access_token,
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Credentials": true,
            "Access-Control-Allow-Methods": "*"
          }
      };
      request(optionsGet,  function getCallback(error, response, body) {
        if (!error && response.statusCode == 200) {
          const info = JSON.parse(body);
          res.send({ statusCode: 200, data: info })
        }
        else {
          res.sendStatus(500).json(error)
        }
      });
    }
    else {
      res.sendStatus(500).json(error)
    }
  });
})

const server = app.listen(port, () => {
  console.log('Listening on port %s', server.address().port)
});