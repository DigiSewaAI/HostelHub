import polib
from deep_translator import GoogleTranslator
import time
import re

def translate_po_file(input_file, output_file, source_lang='en', target_lang='ne'):
    # PO file खोल्नुहोस्
    po = polib.pofile(input_file)
    
    # प्रत्येक entry लाई process गर्नुहोस्
    for i, entry in enumerate(po):
        if not entry.msgstr and entry.msgid:  # यदि message translated छैन भने
            try:
                # Django template variables (जस्तै {{ variable }}, {% tag %}) लाई बचाउनुहोस्
                original_msg = entry.msgid
                placeholders = {}
                
                # सबै variables र tags लाई temporary placeholders सँग replace गर्नुहोस्
                placeholder_pattern = r'(\{\{.*?\}\}|\{\%.*?\%\})'
                matches = re.findall(placeholder_pattern, original_msg)
                
                for idx, match in enumerate(matches):
                    placeholder = f'__PLACEHOLDER_{idx}__'
                    original_msg = original_msg.replace(match, placeholder)
                    placeholders[placeholder] = match
                
                # Translation गर्नुहोस्
                if original_msg.strip():
                    translated = GoogleTranslator(source=source_lang, target=target_lang).translate(original_msg)
                    
                    # Placeholders लाई फेरी original values सँग replace गर्नुहोस्
                    translated_text = translated
                    for placeholder, original in placeholders.items():
                        translated_text = translated_text.replace(placeholder, original)
                    
                    # Ensure translated_text is not None
                    if translated_text is None:
                        translated_text = ""
                    
                    entry.msgstr = translated_text
                    print(f"Translated ({i+1}/{len(po)}): {entry.msgid} -> {entry.msgstr}")
                else:
                    entry.msgstr = ""
                
                # Rate limiting को लागि
                time.sleep(0.5)
                
            except Exception as e:
                print(f"Error translating '{entry.msgid}': {str(e)}")
                # Ensure msgstr is not None in case of error
                entry.msgstr = ""
                continue
    
    # Translated file लाई सेभ गर्नुहोस्
    po.save(output_file)
    print(f"Translation complete. Saved to {output_file}")

if __name__ == "__main__":
    input_file = "locale/ne/LC_MESSAGES/django.po"
    output_file = "locale/ne/LC_MESSAGES/django.po"
    
    translate_po_file(input_file, output_file)