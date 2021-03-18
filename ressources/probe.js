"use strict";
const onvif = require('node-onvif');

// Find the ONVIF network cameras
onvif.startProbe().then((device_list) => {
  // Show the information of the found devices
  console.log(JSON.stringify(device_list, null, '  '));
}).catch((error) => {
  console.error(error);
});