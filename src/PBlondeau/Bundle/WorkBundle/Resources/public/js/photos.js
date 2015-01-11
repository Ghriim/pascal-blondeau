jQuery(document).ready(function () {
    initGallery();
});

function initGallery()
{
    var GammaSettings = {
        // order is important!
        viewport : [ {
            width : 1200,
            columns : 5
        }, {
            width : 900,
            columns : 5
        }, {
            width : 500,
            columns : 3
        }, {
            width : 320,
            columns : 2
        }, {
            width : 0,
            columns : 2
        } ]
    };

    Gamma.init(GammaSettings);
}