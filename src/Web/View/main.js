global.jQuery = require('jquery');
var bootstrap = require('bootstrap'),
  material = require('bootstrap-material-design'),
  jQuery = require('jquery'),
  chart = require('./chart.js')
;

//jQuery.material.init();
jQuery(function() {
  jQuery.material.init();
})
google.charts.load('current', {'packages':['timeline']});
google.charts.setOnLoadCallback(chart.draw);
