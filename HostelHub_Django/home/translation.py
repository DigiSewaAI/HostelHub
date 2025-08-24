# home/translation.py
from modeltranslation.translator import translator, TranslationOptions
from home.models import Booking  # Room, Facility हटाइयो

class BookingTranslationOptions(TranslationOptions):
    fields = ('guest_name', 'room_number',)  # translate गर्न चाहिने fields

translator.register(Booking, BookingTranslationOptions)
