define(['text!templates/events.html', 'views/event'], function(tpl, EventView){
    var EventsView = Backbone.View.extend({
        template: _.template(tpl),
        goals: null,
        initialize: function(){
            // Инициализируем модели
            this.goals = new Backbone.Collection();
            this.goals.on('add', this.addOne, this);
            var that = this;
            $.each(window.kickerTrack.goals, function(index, value){
                that.goals.add(new Backbone.Model(value));
            });

        },
        render: function(){
            return this;
        },
        addOne: function(item){
            var view = new EventView({model: item});
            this.$el.prepend(view.render().el);
        }
    });
    return EventsView;
});