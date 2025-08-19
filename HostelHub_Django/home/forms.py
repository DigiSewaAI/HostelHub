from django.utils.translation import gettext_lazy as _

class BookingForm(forms.ModelForm):
    class Meta:
        model = Booking
        fields = ['check_in', 'check_out', 'guests']
        labels = {
            'check_in': _('Check-in Date'),
            'check_out': _('Check-out Date'),
            'guests': _('Number of Guests'),
        }
        help_texts = {
            'check_in': _('Select your arrival date'),
            'check_out': _('Select your departure date'),
        }