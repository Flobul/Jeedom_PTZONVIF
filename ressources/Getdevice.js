const onvif = require('node-onvif');
var myArgs = process.argv.slice(2);

// Create an OnvifDevice object
let device = new onvif.OnvifDevice({
  xaddr: myArgs[0],
  user : myArgs[1],
  pass : myArgs[2]
});

// Initialize the OnvifDevice object
device.init().then(() => {

let profile = device.getCurrentProfile();
console.log(JSON.stringify(profile, null, '  '));


}).then(() => {
  
}).catch((error) => {
  console.error(error);
});