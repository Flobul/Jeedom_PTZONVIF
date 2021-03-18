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




// Preset

 // Initialize the OnvifDevice object
 device.init().then(() => {
        let profile = device.getCurrentProfile();
        //console.log(profile['token']);


        var PresetToken = myArgs[3];
        console.log('Preset: ',PresetToken);

        let params = {
                'ProfileToken': profile['token'],
                'PresetToken' : PresetToken,
                'Speed'       : {'x': 1, 'y': 1, 'z': 1}
        };
        device.services.ptz.gotoPreset(params).then((result) => {
        console.log(JSON.stringify(result['data'], null, '  '));
        }).catch((error) => {
        console.error(error);
        });
 });




