@extends('layouts.frontend')
@section('title', 'सेवा सर्तहरू - HostelHub')
@section('content')
<div style="
  max-width: 1200px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
  font-family: 'Segoe UI', sans-serif;
  line-height: 1.7;
  color: #374151;
">

  <!-- Page Header - EXACT GALLERY CTA DESIGN -->
  <div style="
    text-align: center;
    margin-bottom: 3rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
  ">
    <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 1rem;">
      सेवा सर्तहरू
    </h1>
    <p style="font-size: 1.125rem; color: rgba(255, 255, 255, 0.9); max-width: 800px; margin: 0 auto;">
      HostelHub को सेवाहरू प्रयोग गर्दा लागू हुने कानूनी सर्तहरू।
    </p>
  </div>

  <!-- Last Updated -->
  <div style="text-align: center; margin-bottom: 2.5rem; color: #6b7280; font-size: 0.875rem;">
    अन्तिम अद्यावधिक: १० जेठ, २०८२
  </div>

  <!-- Content Sections -->
  <div style="max-width: 800px; margin: 0 auto;">

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      १. स्वीकृति
    </h2>
    <p>
      यी सेवा सर्तहरूमा उल्लेखित नियमहरूमा तपाइँको पहुँच, प्रयोग, र निर्भरताले तपाइँले यी सर्तहरूलाई पढेको, बुझेको र स्वीकार गरेको मानिनेछ।
    </p>

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      २. सेवा प्रयोग
    </h2>
    <p>
      तपाइँले HostelHub लाई निम्न उद्देश्यका लागि प्रयोग गर्न सक्नुहुन्छ:
    </p>
    <ul style="margin: 1rem 0 1rem 1.5rem; list-style: disc;">
      <li>होस्टल प्रबन्धन गर्न</li>
      <li>विद्यार्थी, कोठा, भुक्तानी, र भोजन व्यवस्थापन गर्न</li>
      <li>मोबाइल एप्प प्रयोग गर्न</li>
    </ul>
    <p>
      तपाइँले यसलाई अवैध, धोखाधडी, वा हानिकारक उद्देश्यका लागि प्रयोग गर्नु हुँदैन।
    </p>

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      ३. खाता दायित्व
    </h2>
    <p>
      तपाइँले आफ्नो खाताको लागि निम्न दायित्वहरू बहन गर्नुपर्छ:
    </p>
    <ul style="margin: 1rem 0 1rem 1.5rem; list-style: disc;">
      <li>सही र अद्यावधिक जानकारी प्रदान गर्ने</li>
      <li>पासवर्ड र लगइन विवरण सुरक्षित राख्ने</li>
      <li>अनधिकृत प्रयोग भएमा तुरुन्तै सूचना दिने</li>
    </ul>

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      ४. भुक्तानी र योजना
    </h2>
    <p>
      - योजना शुल्क मासिक वा वार्षिक आधारमा लिइन्छ।<br>
      - भुक्तानी असफल भएमा, सेवा ७ दिनपछि निलम्बित गरिन सक्छ।<br>
      - कुनै योजनाबाट रद्दीकरण गर्दा, पहिले भुक्तानी गरिएको रकम फिर्ता गरिँदैन।
    </p>

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      ५. बौद्धिक सम्पदा
    </h2>
    <p>
      HostelHub को सबै डिजाइन, कोड, लोगो, र ब्रान्डिङ नेपालमा दर्ता कापीराइट भएको छ। तपाइँले यसलाई बिना अनुमति प्रयोग, पुन:उत्पादन वा बिक्री गर्न सक्नुहुन्न।
    </p>

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      ६. सेवा नीति परिवर्तन
    </h2>
    <p>
      हामी कानूनी आवश्यकता, सुविधा विस्तार, वा सुरक्षा कारणले यी सर्तहरू परिवर्तन गर्न सक्छौं। परिवर्तन पछि यहाँ अद्यावधिक गरिनेछ र उल्लेखनीय परिवर्तनको लागि ईमेल सूचना पठाइनेछ।
    </p>

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      ७. जिम्मेवारी सीमित
    </h2>
    <p>
      - हामी तकनीकी त्रुटि, डाटा हराउने, वा अस्थायी सेवा बाधाको लागि सीमित जिम्मेवार छौं।<br>
      - तपाइँले आफ्नो डाटा नियमित रूपमा ब्याकअप गर्नुपर्छ।
    </p>

    <h2 style="font-size: 1.5rem; font-weight: 700; color: #001F5B; margin: 2rem 0 1rem;">
      ८. सम्पर्क गर्नुहोस्
    </h2>
    <p>
      यदि तपाइँसँग सेवा सर्तहरू बारे कुनै प्रश्न छ भने, हामीलाई सम्पर्क गर्नुहोस्:
    </p>
    <div style="margin: 1rem 0; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
      <strong>ईमेल:</strong> <a href="mailto:legal@hostelhub.com" style="color: #001F5B;">legal@hostelhub.com</a><br>
      <strong>ठेगाना:</strong> कमलपोखरी, काठमाडौं, नेपाल
    </div>

  </div>

  <!-- CTA Section - EXACT COPY FROM GALLERY PAGE -->
  <div style="
    text-align: center;
    margin-top: 4rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
  ">
    <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 1rem;">
      सुरक्षित र विश्वसनीय सेवा
    </h2>
    <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">
      हामी तपाइँको व्यवसायलाई नियम र पारदर्शिताका आधारमा सहयोग गर्छौं।
    </p>
    
    <!-- Buttons -->
    <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
      <a href="/register" style="
        background-color: white;
        color: #001F5B;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.transform='translateY(-2px)';"
         onmouseout="this.style.backgroundColor='white'; this.style.transform='none';">
        निःशुल्क साइन अप
      </a>
      <a href="/privacy" style="
        border: 2px solid white;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        background-color: transparent;
        transition: all 0.3s ease;
      " onmouseover="this.style.backgroundColor='white'; this.style.color='#001F5B'; this.style.transform='translateY(-2px)';"
         onmouseout="this.style.backgroundColor='transparent'; this.style.color='white'; this.style.transform='none';">
        गोपनीयता नीति हेर्नुहोस्
      </a>
    </div>
  </div>

</div>

<style>
  /* Responsive adjustments */
  @media (max-width: 640px) {
    [style*="font-size: 2.5rem"] { font-size: 2rem !important; }
    [style*="font-size: 1.875rem"] { font-size: 1.5rem !important; }
    [style*="font-size: 1.25rem"] { font-size: 1.125rem !important; }
    [style*="font-size: 1.125rem"] { font-size: 1rem !important; }
    [style*="font-size: 1.5rem"] { font-size: 1.25rem !important; }
  }
</style>
@endsection