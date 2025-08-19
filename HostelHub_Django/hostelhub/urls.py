from django.contrib import admin
from django.urls import path, include
from django.conf.urls.i18n import i18n_patterns

urlpatterns = [
    # Language setting change को लागि path
    path('i18n/', include('django.conf.urls.i18n')),
]

# Multilingual URLs
urlpatterns += i18n_patterns(
    path('admin/', admin.site.urls),
    path('', include('home.urls')),  # homepage / home app urls
    prefix_default_language=False,   # default language बिना prefix
)
