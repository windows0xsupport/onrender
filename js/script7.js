
function addEvent(obj, evt, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(evt, fn, false);
    } else if (obj.attachEvent) {
        obj.attachEvent("on" + evt, fn);
    }
}

addEvent(document, 'mouseout', function(evt) {
    if (evt.toElement == null && evt.relatedTarget == null) {
        $('.vislnb').slideDown();
    };
});

$('a.close').click(function() {
    $('.vislnb').slideUp();
});
$('body').click(function() {
    $('.vislnb').slideUp();
});

  