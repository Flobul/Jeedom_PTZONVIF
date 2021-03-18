"use strict";
const onvif = require('node-onvif');
 var myArgs = process.argv.slice(2);

// Create an OnvifDevice object
let device = new onvif.OnvifDevice({
  xaddr: myArgs[0],
  user : myArgs[1],
  pass : myArgs[2]
});

// Initialize the OnvifDevice object
device.init().then((info) => {
  console.log(JSON.stringify(info, null, '  '));
}).catch((error) => {
  console.log('[ERROR] ' + error.message);
});