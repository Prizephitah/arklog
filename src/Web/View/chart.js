module.exports = {
  days: {
    0: 'Sunday',
    1: 'Monday',
    2: 'Tuesday',
    3: 'Wednesday',
    4: 'Thursday',
    5: 'Friday',
    6: 'Saturday'
  },

  draw: function() {
    if (jQuery('#chart').length < 1) {
      return;
    }

    self = require('./chart.js');
    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn({ type: "string", id: "Name" });
    data.addColumn({ type: "string", id: "Day" });
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
        '',
        this.created,
        this.updated
      ]);
    });

    Array.prototype.push.apply(rows, this.getDays(this.getStart(), this.getEnd()));

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
    }) || { nickName: 'Unknown' };
  },

  getStart: function() {
    var jQuery = require('jquery');
    return new Date(jQuery("#chart").data('start'));
  },

  getEnd: function() {
    var jQuery = require('jquery');
    return new Date(jQuery("#chart").data('end'));
  },

  getDays: function(start, end) {
    var days = [],
      day = new Date(end.getTime());
    while (day >= start) {
      var dayStart = new Date(day.getTime()),
        dayEnd = new Date(day.getTime());

      dayStart.setHours(0, 0, 0, 0);
      dayEnd.setHours(23, 59, 59, 999);
      if (dayEnd > end) {
        dayEnd = new Date(end.getTime());
      }
      if (dayStart < start) {
        dayStart = new Date(start.getTime());
      }

      days.push([
        'Day',
        this.days[day.getDay()],
        dayStart,
        dayEnd
      ]);

      day.setDate(day.getDate() - 1);
    }
    return days;
  }
};
