require.config({
    baseUrl: '/js/app/track'
});
define(['views/track'], function(TrackView){
    // bind view to dom
    new TrackView({
        el: $('#container-track')
    });
});