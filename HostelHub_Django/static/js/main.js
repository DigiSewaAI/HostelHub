// Django JS catalog को लागि
document.addEventListener('DOMContentLoaded', function() {
    const gettext = function(msgid) {
        return window.django.gettext ? window.django.gettext(msgid) : msgid;
    };
    
    // उदाहरण: प्रयोग
    alert(gettext('Booking confirmed successfully'));
});