from django.db import models
from django.utils.translation import gettext_lazy as _
from django.core.exceptions import ValidationError

class Booking(models.Model):
    guest_name = models.CharField(max_length=100, verbose_name=_("Guest Name"))
    room_number = models.CharField(max_length=10, verbose_name=_("Room Number"))
    check_in = models.DateField(verbose_name=_("Check-in Date"))
    check_out = models.DateField(verbose_name=_("Check-out Date"))
    created_at = models.DateTimeField(auto_now_add=True, verbose_name=_("Created At"))
    updated_at = models.DateTimeField(auto_now=True, verbose_name=_("Updated At"))

    class Meta:
        verbose_name = _("Booking")
        verbose_name_plural = _("Bookings")
        ordering = ["-check_in"]  # Recent bookings first

    def clean(self):
        """Validate that check-out date is after check-in date."""
        if self.check_out <= self.check_in:
            raise ValidationError(_('Check-out date must be after check-in date'))

    def __str__(self):
        return f"{self.guest_name} - Room {self.room_number}"
