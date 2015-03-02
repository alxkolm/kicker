define([
    'text!templates/track.html',
    'text!templates/events.html',
    'text!templates/player-box.html'
    ],
    function(tpl, eventsTpl, playerTpl){
    var TrackView = Backbone.View.extend({
        template: _.template(tpl),
        eventsTpl: _.template(eventsTpl),
        playerTpl: _.template(playerTpl),
        events: {
            'click .button-goal': 'goal',
            'click .button-restart': 'restartGame',
            'click .button-undo': 'undoGoal'
        },
        players: null,
        game: null,
        initialize: function(){
            this.players = window.kickerTrack.players;
            this.game = window.kickerTrack.game;
            // Инициализируем модели
            this.goals = new Backbone.Collection();
            var that = this;
            $.each(window.kickerTrack.goals, function(index, value){
                that.goals.add(new Backbone.Model(value));
            });
            this.goals.on('add', this.render, this);
            this.goals.on('change', this.render, this);
        },
        render: function(){
            this.$el.html(this.template({
                players: this.players,
                goals: this.goals,
                view: this
            }));
            return this;
        },
        goal: function(e){
            e.stopPropagation();
            var el = $(e.currentTarget);
            var that = this;
            // Добавляем гол
            var userId = parseInt(el.attr('user-id'));
            var goal = that.goals.add(new Backbone.Model({user_id: userId, created: 'send...', autogoal: el.is('.autogoal') ? 1 : 0}));
            $.ajax('/index.php/game/goal', {
                method: 'post',
                data: {
                    id:       this.game.id,
                    user:     userId,
                    autogoal: el.is('.autogoal') ? 1 : 0
                },
                success: function(reply){
                    goal.set(reply);
                }
            });
        },
        /**
         * Отменить последний гол
         */
        undoGoal: function(){
            var lastGoal = this.goals.pop();
            this.render();
            $.post('/game/undo-goal', {id: lastGoal.id});
        },
        score: function(){
            var teamAScore = this.goals.filter(function(goal){
                return !goal.get('autogoal') && (goal.get('user_id') == this.players.a.id || goal.get('user_id') == this.players.b.id)
                    || goal.get('autogoal') && (goal.get('user_id') != this.players.a.id && goal.get('user_id') != this.players.b.id)
            }, this);
            var teamBScore = this.goals.filter(function(goal){
                return !goal.get('autogoal') && (goal.get('user_id') == this.players.c.id || goal.get('user_id') == this.players.d.id)
                    || goal.get('autogoal') && (goal.get('user_id') != this.players.c.id && goal.get('user_id') != this.players.d.id)
            }, this);
            return _.size(teamAScore) + ':' + _.size(teamBScore);
        },
        /**
         * Повтор игры с тем же составом
         */
        restartGame: function(){
            window.location = '/game/repeat?id=' + this.game.id;
        }
    });
    return TrackView;
});