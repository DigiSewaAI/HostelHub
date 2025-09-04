@extends('layouts.frontend')
@section('title', 'प्रशंसापत्रहरू - HostelHub')
@section('content')
<div style="
  max-width: 1200px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
  font-family: 'Segoe UI', sans-serif;
">

  <!-- Page Header -->
  <div style="
    text-align: center;
    margin-bottom: 3rem;
  ">
    <h1 style="
      font-size: 2.5rem;
      font-weight: 800;
      color: #1f2937;
      margin-bottom: 1rem;
    ">
      हाम्रा ग्राहकहरूको प्रशंसापत्र
      
    </h1>
    <p style="
      font-size: 1.125rem;
      color: #4b5563;
      max-width: 700px;
      margin: 0 auto;
      line-height: 1.6;
    ">
      HostelHub प्रयोग गर्ने होस्टल प्रबन्धक र मालिकहरूले के भन्छन् —<br>
      वास्तविक अनुभव, वास्तविक परिणाम।
    </p>
  </div>

  <!-- Testimonials Grid -->
  <div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
  ">

    <!-- Testimonial 1 -->
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0;
      ">
        “HostelHub ले हाम्रो होस्टल पूर्ण रूपमा streamline गर्यो। सबै कामहरू अब एउटै प्लेटफर्ममा छन्।”
      </p>
      <div style="
        margin-top: 1rem;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
      ">
        — राम श्रेष्ठ, काठमाडौं होस्टल
      </div>
    </div>

    <!-- Testimonial 2 -->
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0;
      ">
        “भुक्तानी ट्र्याकिंग सजिलो र विश्वसनीय छ। अब बक्यौता बारे चिन्ता छैन।”
      </p>
      <div style="
        margin-top: 1rem;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
      ">
        — सुनिता पौडेल, पोखरा स्टुडेन्ट होम
      </div>
    </div>

    <!-- Testimonial 3 -->
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0;
      ">
        “विश्लेषण र रिपोर्टले मलाई आगामी निर्णयहरू लिन ठूलो मद्दत गर्यो।”
      </p>
      <div style="
        margin-top: 1rem;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
      ">
        — अमित कुमार, बिराटनगर कलेज होस्टल
      </div>
    </div>

    <!-- Testimonial 4 -->
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0;
      ">
        “मोबाइल एप्पले विद्यार्थीहरूलाई पनि भुक्तानी र कोठा स्थिति हेर्न सजिलो बनायो।”
      </p>
      <div style="
        margin-top: 1rem;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
      ">
        — निरु श्रेष्ठ, भक्तपुर बोर्डिङ
      </div>
    </div>

    <!-- Testimonial 5 -->
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0;
      ">
        “७ दिनको निःशुल्क परीक्षणले नै हामीले सही योजना छान्न सजिलो भयो।”
      </p>
      <div style="
        margin-top: 1rem;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
      ">
        — रबिन थापा, चितवन होस्टल
      </div>
    </div>

    <!-- Testimonial 6 -->
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0;
      ">
        “नेपालीमा सपोर्ट र सुविधाहरूले धेरै मद्दत गर्यो। स्थानीय समाधानको लागि उत्तम।”
      </p>
      <div style="
        margin-top: 1rem;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
      ">
        — अनुराधा जोशी, धुलिखेल स्टुडेन्ट होम
      </div>
    </div>

  </div>

  <!-- CTA Section -->
  <div style="
    text-align: center;
    margin-top: 4rem;
    background: linear-gradient(90deg, #0a3a6a, #001F5B, #0a3a6a);
    color: white;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
  ">
    <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 1rem;">
      आफैंले अनुभव गर्नुहोस्
    </h2>
    <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">
      ७ दिनको निःशुल्क परीक्षणमा साइन अप गरेर तपाइँको होस्टललाई आधुनिक बनाउनुहोस्।
    </p>
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
      <a href="/demo" style="
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
        डेमो हेर्नुहोस्
      </a>
    </div>
  </div>

</div>
@endsection