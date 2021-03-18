const onvif = require('node-onvif');
var myArgs = process.argv.slice(2);

// Create an OnvifDevice object
let device = new onvif.OnvifDevice({
  xaddr: myArgs[0],
  user : myArgs[1],
  pass : myArgs[2]
});

 device.init().then(() => {
	let profile = device.getCurrentProfile();
	//console.log(profile['token']);


 	let params = {
  		'ProfileToken': profile['token'],
   	};
        // Pour visualiser les Presets
	
	device.services.ptz.getPresets(params).then((result) => {
  	console.log(JSON.stringify(result['data'], null, '  '));
		}).catch((error) => {
  		console.error(error);
	});


 
 });