define(['text!templates/event.html'], function(tpl){
    var EventView = Backbone.View.extend({
        template: _.template(tpl),
        player: null,
        initialize: function(){
            this.player = window.kicker_players[this.model.get('user_id')];
            this.$el.addClass('event team-'+this.player.team);
        },
        render: function(){
            this.$el.html(
                this.template(
                    $.extend(
                        this.model.attributes,
                        {player: this.player}
                    )
                )
            );
            return this;
        }
    });
    return EventView;
});