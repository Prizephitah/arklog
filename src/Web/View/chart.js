module.exports = {
  draw: function() {
    self = require('./chart.js');
    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn({ type: "string", id: "Name" });
    data.addColumn({ type: "date", id: "Start" });
    data.addColumn({ type: "date", id: "End" });
    data.addRows(self.fetchRows());

    // Set chart options
    var options = {
      hAxis: {
        viewWindow: {
          minValue: self.getStart(),
          maxValue: self.getEnd()
        },
        gridLines: {
          units: {
            days: { format: ['EEEE'] },
            hours: { format: ['HH:mm'] }
          }
        },
        minorGridLines: {
          units: {
            hours: { format: ['HH'] },
            minutes: { format: ['HH:mm', ':mm'] }
          }
        }
      }
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.Timeline(document.getElementById('chart'));
    chart.draw(data, options);
  },

  fetchRows: function() {
    var jQuery = require('jquery'),
      players = this.fetchPlayers(),
      sessions = this.fetchSessions(),
      self = this,
      rows = [];

    jQuery.each(sessions, function() {
      rows.push([
        self.getPlayer(this.playerId, players).nickName,
        this.created,
        this.updated
      ]);
    });

    return rows;
  },

  fetchPlayers: function() {
    var jQuery = require('jquery'),
      playersRaw = jQuery('#chart').data('players')
    ;

    return jQuery.map(playersRaw, function(player) {
      player.created = new Date(player.created);
      player.updated = player.updated ? new Date(player.updated) : null
      return player;
    });
  },

  fetchSessions: function() {
    var jQuery = require('jquery'),
      sessionsRaw = jQuery('#chart').data('sessions')
    ;

    return jQuery.map(sessionsRaw, function(session) {
      session.created = new Date(session.created);
      session.updated = new Date(session.updated);
      return session;
    });
  },

  getPlayer: function(id, players) {
    return players.find(function(player) {
      return player.id == id;
    }) || 'Unknown';
  },

  getStart: function() {
    var jQuery = require('jquery');
    return new Date(jQuery("#chart").data('start'));
  },

  getEnd: function() {
    var jQuery = require('jquery');
    return new Date(jQuery("#chart").data('end'));
  }
};