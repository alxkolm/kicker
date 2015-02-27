require.config({
    baseUrl: '/js/app/track'
});
define(['views/track', 'views/events'], function(TrackView, EventsView){
    // bind view to dom
    var trackView = new TrackView({
        el: $('#track-split-screen')
    });
    trackView.render();



    //window.eventsView = new EventsView();
    //$('#container-events').append(eventsView.render().el);

});