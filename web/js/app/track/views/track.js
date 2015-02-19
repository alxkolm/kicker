define(function(){
    var TrackView = Backbone.View.extend({
        events: {
            'click .do-goal': 'goal'
        },
        goal: function(e){
            var el = $(e.target);
            $.ajax('/index.php/game/goal', {
                method: 'post',
                data: {
                    id:       el.attr('game-id'),
                    user:     el.attr('user-id'),
                    autogoal: el.is('.autogoal') ? 1 : 0
                },
                success: function(reply){
                    // Добавляем гол
                    window.eventsView.goals.add(new Backbone.Model(reply));
                }
            });
        }
    });
    return TrackView;
});