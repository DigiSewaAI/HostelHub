from django.conf import settings
from django.utils.translation import activate, get_language
from django.shortcuts import render

def home(request):
    lang = request.GET.get('language', get_language())
    
    if lang and lang in [lang_code for lang_code, _ in settings.LANGUAGES]:
        activate(lang)
        request.session['django_language'] = lang
    
    return render(request, "home/home.html")