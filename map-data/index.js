const fs = require('fs');
const parse = require('csv-parse');

// Relevant Variables:
const weedMapsApi = "https://api-g.weedmaps.com/discovery/v1/location?include[]=regions.listings&latlng=37.78,-122.42";
const mockApi = './data/mockApiResponse.json';

const geoFile = './data/ca-geo-data.csv';


let caArray = [];
let finalArray = [];
const parseSettings = {columns: false, trim: true};

// const makeApiCall = new Promise(function(resolve, reject) {
// }

const setupCaArray = new Promise(function(resolve, reject) {
  fs.readFile(geoFile, function (err, fileData) {
    parse(fileData, parseSettings, function(err, rows) {
      let rowsLen = rows.length;
      rows.forEach(row => {
        let secRemaing = rowsLen * 15;
        console.log("Estimated time remaining: "+ (secRemaing/60) + " minutes");

        let city  = row[1];
        let state = row[2];
        let latS  = row[3];
        let longS = row[4];

        let lat = parseFloat(latS).toFixed(2);
        let long = parseFloat(longS).toFixed(2);

        // setTimeout(function () {
        //   let apiURL = `https://api-g.weedmaps.com/discovery/v1/location?include[]=regions.listings&latlng=${lat},${long}`;
        //   console.log("Crawling: "+ apiURL);
        // }, 15000);


        rowsLen--;
      });
      resolve();
    })
  })
});
