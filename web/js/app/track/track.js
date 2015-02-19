require.config({
    baseUrl: '/js/app/track'
});
define(['views/track', 'views/events'], function(TrackView, EventsView){
    // bind view to dom
    new TrackView({
        el: $('#container-track')
    });



    var eventsView = new EventsView();
    $('#container-events').append(eventsView.render().el);

});