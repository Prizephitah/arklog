global.jQuery = require('jquery');
var bootstrap = require('bootstrap'),
  jQuery = require('jquery'),
  chart = require('./chart.js')
;

//jQuery.material.init();
google.charts.load('current', {'packages':['timeline']});
google.charts.setOnLoadCallback(chart.draw);
