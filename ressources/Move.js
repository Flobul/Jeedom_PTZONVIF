"use strict";

const onvif = require('node-onvif');
 var myArgs = process.argv.slice(2);
//console.log('myArgs: ', myArgs);


// Create an OnvifDevice object
let device = new onvif.OnvifDevice({
  xaddr: myArgs[0],
  user : myArgs[1],
  pass : myArgs[2]
  
});

let params = {
  'speed': {x: parseFloat(myArgs[3]), y: parseFloat(myArgs[4]), z: parseFloat(myArgs[5])},
  'timeout': 60 // seconds
};


 // Initialize the OnvifDevice object
 device.init().then(() => {
          // Move the camera
        device.ptzMove(params).then(() => {
                setTimeout(() => {
                device.ptzStop().then(() => {
                //console.log('Succeeded to stop.');
                }).catch((error) => 
				{
					console.error(error);
                });
                }, parseInt(myArgs[6]));
        });
        }).then(() => { }).catch((error) => {
			console.error(error);
		}
 );










