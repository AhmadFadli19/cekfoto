/* ============================================================
   CatinGuard React SPA — Full Application
   All 14 features, Gemini AI integration, Indonesia Map
   ============================================================ */

const { useState, useEffect, useRef, useCallback } = React;

// ==================== API HELPER ====================
const API_BASE = '/api';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

async function api(endpoint, options = {}) {
  const config = {
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    ...options,
  };
  const res = await fetch(`${API_BASE}${endpoint}`, config);
  return res.json();
}

function formatRupiah(num) {
  return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// ==================== INDONESIA GEOJSON (Simplified) ====================
const INDONESIA_PROVINCES = {
  "AC": { name: "Aceh", center: [4.695, 96.749] },
  "SU": { name: "Sumatera Utara", center: [2.116, 99.545] },
  "SB": { name: "Sumatera Barat", center: [-0.739, 100.800] },
  "RI": { name: "Riau", center: [1.589, 101.382] },
  "JA": { name: "Jambi", center: [-1.610, 103.613] },
  "SS": { name: "Sumatera Selatan", center: [-3.319, 104.914] },
  "BE": { name: "Bengkulu", center: [-3.793, 102.259] },
  "LA": { name: "Lampung", center: [-4.558, 105.406] },
  "BB": { name: "Kep. Bangka Belitung", center: [-2.741, 106.440] },
  "KR": { name: "Kep. Riau", center: [3.945, 108.143] },
  "JK": { name: "DKI Jakarta", center: [-6.211, 106.845] },
  "JB": { name: "Jawa Barat", center: [-6.889, 107.640] },
  "JT": { name: "Jawa Tengah", center: [-7.150, 110.140] },
  "JI": { name: "Jawa Timur", center: [-7.536, 112.238] },
  "YO": { name: "DI Yogyakarta", center: [-7.797, 110.369] },
  "BT": { name: "Banten", center: [-6.405, 106.064] },
  "BA": { name: "Bali", center: [-8.409, 115.189] },
  "NB": { name: "Nusa Tenggara Barat", center: [-8.652, 117.361] },
  "NT": { name: "Nusa Tenggara Timur", center: [-8.657, 121.079] },
  "KB": { name: "Kalimantan Barat", center: [-1.681, 109.330] },
  "KT": { name: "Kalimantan Tengah", center: [-1.681, 113.382] },
  "KS": { name: "Kalimantan Selatan", center: [-3.092, 115.283] },
  "KI": { name: "Kalimantan Timur", center: [1.693, 116.419] },
  "KU": { name: "Kalimantan Utara", center: [3.073, 116.041] },
  "SA": { name: "Sulawesi Utara", center: [0.624, 123.975] },
  "ST": { name: "Sulawesi Tengah", center: [-1.430, 121.445] },
  "SN": { name: "Sulawesi Selatan", center: [-3.670, 119.974] },
  "SG": { name: "Sulawesi Tenggara", center: [-4.145, 122.175] },
  "GO": { name: "Gorontalo", center: [0.690, 122.447] },
  "SR": { name: "Sulawesi Barat", center: [-2.844, 119.232] },
  "MA": { name: "Maluku", center: [-3.239, 130.145] },
  "MU": { name: "Maluku Utara", center: [1.571, 127.809] },
  "PB": { name: "Papua Barat", center: [-1.336, 133.174] },
  "PA": { name: "Papua", center: [-4.269, 138.080] },
};

// ==================== SIDEBAR NAV ====================
const NAV_ITEMS = [
  { section: 'Utama' },
  { id: 'home', icon: '🗺️', label: 'Peta Risiko Indonesia', badge: null },
  { id: 'dashboard', icon: '📊', label: 'Dashboard', badge: null },
  { section: 'Fitur Catin' },
  { id: 'literasi', icon: '📚', label: 'Literasi Kesehatan', badge: null },
  { id: 'labscan', icon: '🔬', label: 'AI Lab Scanner', badge: 'AI' },
  { id: 'genetic', icon: '🧬', label: 'Pohon Genetik', badge: null },
  { id: 'geotriage', icon: '🏥', label: 'Cari Faskes', badge: null },
  { id: 'referral', icon: '💳', label: 'Kartu Rujukan', badge: null },
  { id: 'readiness', icon: '🎯', label: 'Skor Kesiapan', badge: null },
  { id: 'cost', icon: '💰', label: 'Simulasi Biaya', badge: null },
  { id: 'certificate', icon: '📜', label: 'Sertifikat Digital', badge: null },
  { section: 'Fitur Nakes' },
  { id: 'tracker', icon: '⏱️', label: '72-Hour Tracker', badge: '!' },
  { id: 'actionplan', icon: '📋', label: 'Rencana Aksi AI', badge: 'AI' },
  { id: 'donor', icon: '🩸', label: 'Jaringan Donor', badge: null },
  { id: 'epidemiologi', icon: '📈', label: 'Dashboard Epidemiologi', badge: null },
];

// ==================== MAIN APP ====================
function App() {
  const [page, setPage] = useState('home');
  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <div className="app-layout">
      <button className="mobile-menu-btn" onClick={() => setSidebarOpen(!sidebarOpen)}>☰</button>
      {sidebarOpen && <div className="sidebar-overlay show" onClick={() => setSidebarOpen(false)} />}

      <aside className={`sidebar ${sidebarOpen ? 'open' : ''}`}>
        <div className="sidebar-logo">
          <div className="logo-icon">🛡️</div>
          <div>
            <h1>CatinGuard</h1>
            <div className="subtitle">Skrining Pranikah AI</div>
          </div>
        </div>
        <nav className="sidebar-nav">
          {NAV_ITEMS.map((item, i) =>
            item.section ? (
              <div key={i} className="nav-section-label">{item.section}</div>
            ) : (
              <div key={item.id}
                   className={`nav-item ${page === item.id ? 'active' : ''}`}
                   onClick={() => { setPage(item.id); setSidebarOpen(false); }}>
                <span className="nav-icon">{item.icon}</span>
                {item.label}
                {item.badge && <span className="nav-badge">{item.badge}</span>}
              </div>
            )
          )}
        </nav>
      </aside>

      <main className="main-content">
        {page === 'home' && <HomePage />}
        {page === 'dashboard' && <DashboardPage />}
        {page === 'literasi' && <LiterasiPage />}
        {page === 'labscan' && <LabScannerPage />}
        {page === 'genetic' && <GeneticTreePage />}
        {page === 'geotriage' && <GeoTriagePage />}
        {page === 'tracker' && <TrackerPage />}
        {page === 'actionplan' && <ActionPlanPage />}
        {page === 'referral' && <ReferralCardPage />}
        {page === 'donor' && <DonorNetworkPage />}
        {page === 'readiness' && <ReadinessScorePage />}
        {page === 'cost' && <CostSimulatorPage />}
        {page === 'certificate' && <CertificatePage />}
        {page === 'epidemiologi' && <EpidemiologiPage />}
      </main>
    </div>
  );
}

// ==================== PAGE: HOME (Interactive Map) ====================
function HomePage() {
  const [provinces, setProvinces] = useState([]);
  const [activeLayer, setActiveLayer] = useState('risk');
  const mapRef = useRef(null);
  const mapInstanceRef = useRef(null);

  useEffect(() => {
    api('/geo-triage/provinces').then(setProvinces);
  }, []);

  useEffect(() => {
    if (!mapRef.current || mapInstanceRef.current) return;

    const map = L.map(mapRef.current, {
      center: [-2.5, 118],
      zoom: 5,
      minZoom: 4,
      maxZoom: 10,
      zoomControl: true,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; OpenStreetMap &copy; CARTO',
    }).addTo(map);

    mapInstanceRef.current = map;

    return () => { map.remove(); mapInstanceRef.current = null; };
  }, []);

  useEffect(() => {
    if (!mapInstanceRef.current || provinces.length === 0) return;
    const map = mapInstanceRef.current;

    // Clear existing markers
    map.eachLayer(l => { if (l instanceof L.CircleMarker) map.removeLayer(l); });

    provinces.forEach(p => {
      const geo = INDONESIA_PROVINCES[p.province_code];
      if (!geo) return;

      let color, radius, value;
      if (activeLayer === 'risk') {
        color = p.risk_color;
        radius = Math.max(8, Math.min(25, p.risk_score / 4));
        value = p.risk_score;
      } else if (activeLayer === 'aki') {
        const akiNorm = Math.min(p.aki / 300, 1);
        color = akiNorm > 0.6 ? '#dc2626' : akiNorm > 0.3 ? '#f97316' : '#22c55e';
        radius = Math.max(8, akiNorm * 25);
        value = p.aki;
      } else if (activeLayer === 'thalasemia') {
        const thalNorm = Math.min(p.kasus_thalasemia / 4255, 1);
        color = thalNorm > 0.5 ? '#dc2626' : thalNorm > 0.2 ? '#eab308' : '#22c55e';
        radius = Math.max(8, thalNorm * 25);
        value = p.kasus_thalasemia;
      } else {
        const litNorm = p.indeks_literasi / 5;
        color = litNorm < 0.4 ? '#dc2626' : litNorm < 0.7 ? '#eab308' : '#22c55e';
        radius = Math.max(8, (1 - litNorm) * 25);
        value = p.indeks_literasi;
      }

      const marker = L.circleMarker(geo.center, {
        radius: radius,
        fillColor: color,
        color: color,
        weight: 2,
        opacity: 0.8,
        fillOpacity: 0.4,
      }).addTo(map);

      marker.bindPopup(`
        <div class="province-popup">
          <h3>${p.province_name}</h3>
          <div class="popup-stat"><span>Skor Risiko</span><strong>${p.risk_score}</strong></div>
          <div class="popup-stat"><span>AKI (per 100rb)</span><strong>${p.aki}</strong></div>
          <div class="popup-stat"><span>Rh- Populasi</span><strong>${p.populasi_rh_negatif_persen}%</strong></div>
          <div class="popup-stat"><span>Defisit Stok Rh-</span><strong>${p.defisit_stok_rh_negatif}</strong></div>
          <div class="popup-stat"><span>Kasus Thalasemia</span><strong>${p.kasus_thalasemia.toLocaleString()}</strong></div>
          <div class="popup-stat"><span>Indeks Literasi</span><strong>${p.indeks_literasi}/5</strong></div>
          <div class="popup-stat"><span>Jumlah Faskes</span><strong>${p.jumlah_faskes}</strong></div>
          <div class="popup-stat"><span>Penduduk</span><strong>${p.jumlah_penduduk.toLocaleString()}</strong></div>
        </div>
      `);
    });
  }, [provinces, activeLayer]);

  const highRisk = provinces.filter(p => p.risk_score >= 50);

  return (
    <div>
      <div className="page-header">
        <h2>🗺️ Peta Risiko Kesehatan Pranikah Indonesia</h2>
        <p>Visualisasi data risiko Rhesus, Thalasemia & kematian ibu di 34 provinsi</p>
      </div>
      <div className="page-body">
        <div className="layer-toggles">
          {[
            { id: 'risk', label: '🎯 Skor Risiko Gabungan' },
            { id: 'aki', label: '💔 Kematian Ibu (AKI)' },
            { id: 'thalasemia', label: '🧬 Kasus Thalasemia' },
            { id: 'literasi', label: '📖 Indeks Literasi' },
          ].map(l => (
            <button key={l.id} className={`layer-toggle ${activeLayer === l.id ? 'active' : ''}`}
                    onClick={() => setActiveLayer(l.id)}>
              {l.label}
            </button>
          ))}
        </div>

        <div className="map-container" ref={mapRef}></div>

        <div className="map-legend" style={{marginTop: 12}}>
          <div className="map-legend-item"><div className="map-legend-color" style={{background:'#22c55e'}}></div> Risiko Rendah</div>
          <div className="map-legend-item"><div className="map-legend-color" style={{background:'#eab308'}}></div> Risiko Sedang</div>
          <div className="map-legend-item"><div className="map-legend-color" style={{background:'#f97316'}}></div> Risiko Tinggi</div>
          <div className="map-legend-item"><div className="map-legend-color" style={{background:'#dc2626'}}></div> Risiko Sangat Tinggi</div>
        </div>

        <div style={{marginTop: 24}}>
          <h3 className="card-title">⚠️ Provinsi Risiko Tinggi ({highRisk.length})</h3>
          <div className="stats-grid">
            {highRisk.slice(0, 8).map(p => (
              <div key={p.province_code} className="stat-card" style={{borderLeft: `3px solid ${p.risk_color}`}}>
                <div className="stat-label">{p.province_name}</div>
                <div className="stat-value" style={{color: p.risk_color}}>{p.risk_score}</div>
                <div style={{fontSize:'0.75rem',color:'var(--gray-400)'}}>AKI: {p.aki} · Thal: {p.kasus_thalasemia}</div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: DASHBOARD ====================
function DashboardPage() {
  const [data, setData] = useState(null);
  useEffect(() => { api('/epidemiologi/dashboard').then(setData); }, []);

  if (!data) return <div className="page-body"><div className="spinner"></div></div>;

  return (
    <div>
      <div className="page-header">
        <h2>📊 Dashboard CatinGuard</h2>
        <p>Ringkasan sistem deteksi dini risiko kesehatan pranikah</p>
      </div>
      <div className="page-body">
        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-icon" style={{background:'rgba(20,184,166,0.15)',color:'var(--primary-400)'}}>👥</div>
            <div className="stat-value">{data.total_catin}</div>
            <div className="stat-label">Total Catin Terdaftar</div>
          </div>
          <div className="stat-card">
            <div className="stat-icon" style={{background:'rgba(249,115,22,0.15)',color:'var(--risk-high)'}}>⏱️</div>
            <div className="stat-value">{data.active_cases}</div>
            <div className="stat-label">Kasus Aktif (72h)</div>
          </div>
          <div className="stat-card">
            <div className="stat-icon" style={{background:'rgba(34,197,94,0.15)',color:'var(--risk-low)'}}>✅</div>
            <div className="stat-value">{data.resolved_cases}</div>
            <div className="stat-label">Kasus Selesai</div>
          </div>
          <div className="stat-card">
            <div className="stat-icon" style={{background:'rgba(99,102,241,0.15)',color:'#818cf8'}}>🏥</div>
            <div className="stat-value">{data.total_faskes}</div>
            <div className="stat-label">Faskes Terdaftar</div>
          </div>
          <div className="stat-card">
            <div className="stat-icon" style={{background:'rgba(236,72,153,0.15)',color:'#ec4899'}}>💉</div>
            <div className="stat-value">{data.faskes_with_rhogam}</div>
            <div className="stat-label">Faskes + RhoGAM</div>
          </div>
        </div>
        <div className="medical-disclaimer">
          ⚕️ CatinGuard adalah alat bantu skrining, bukan pengganti konsultasi medis profesional. Semua keputusan medis harus dikonfirmasi oleh tenaga kesehatan.
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: LITERASI (Education + Chatbot) ====================
function LiterasiPage() {
  const [tab, setTab] = useState('artikel');
  const [articles, setArticles] = useState([]);
  const [messages, setMessages] = useState([{ role: 'model', text: 'Halo! Saya CatinGuard Edu-Bot 🤖 Tanyakan apa saja tentang kesehatan pranikah, Rhesus, atau Thalasemia. Saya di sini untuk membantu!' }]);
  const [input, setInput] = useState('');
  const [loading, setLoading] = useState(false);
  const [quizState, setQuizState] = useState({ active: null, answers: {}, submitted: false, result: null });

  useEffect(() => { api('/education').then(setArticles); }, []);

  const sendMessage = async () => {
    if (!input.trim() || loading) return;
    const userMsg = { role: 'user', text: input };
    const newMsgs = [...messages, userMsg];
    setMessages(newMsgs);
    setInput('');
    setLoading(true);

    const res = await api('/chat', {
      method: 'POST',
      body: JSON.stringify({ conversation: newMsgs.map(m => ({ role: m.role === 'model' ? 'model' : 'user', text: m.text })) }),
    });

    setMessages([...newMsgs, { role: 'model', text: res.output || 'Maaf, terjadi kesalahan.' }]);
    setLoading(false);
  };

  const startQuiz = (article) => {
    setQuizState({ active: article, answers: {}, submitted: false, result: null });
    setTab('kuis');
  };

  const submitQuiz = async () => {
    const res = await api('/education/quiz', {
      method: 'POST',
      body: JSON.stringify({ content_id: quizState.active.id, answers: Object.values(quizState.answers) }),
    });
    setQuizState(prev => ({ ...prev, submitted: true, result: res }));
  };

  const artikelList = articles.filter(a => a.tipe === 'artikel');
  const kuisList = articles.filter(a => a.tipe === 'kuis');

  return (
    <div>
      <div className="page-header">
        <h2>📚 Literasi Kesehatan Pranikah</h2>
        <p>Fitur 1 — Edukasi interaktif dan chatbot AI untuk calon pengantin</p>
      </div>
      <div className="page-body">
        <div className="tabs">
          <button className={`tab ${tab === 'artikel' ? 'active' : ''}`} onClick={() => setTab('artikel')}>📖 Artikel</button>
          <button className={`tab ${tab === 'chatbot' ? 'active' : ''}`} onClick={() => setTab('chatbot')}>🤖 Chatbot AI</button>
          <button className={`tab ${tab === 'kuis' ? 'active' : ''}`} onClick={() => setTab('kuis')}>✏️ Kuis</button>
        </div>

        {tab === 'artikel' && (
          <div className="grid-2">
            {artikelList.map(a => (
              <div key={a.id} className="card animate-fade-in">
                <h3 className="card-title">{a.judul}</h3>
                <p style={{fontSize:'0.85rem',color:'var(--gray-400)',marginBottom:12}}>{a.ringkasan}</p>
                <div style={{fontSize:'0.875rem',lineHeight:1.8,color:'var(--gray-300)'}}>{a.konten}</div>
                <span className="risk-badge rendah" style={{marginTop:12,display:'inline-block'}}>{a.kategori}</span>
              </div>
            ))}
            {kuisList.map(q => (
              <div key={q.id} className="card animate-fade-in" style={{cursor:'pointer'}} onClick={() => startQuiz(q)}>
                <h3 className="card-title">✏️ {q.judul}</h3>
                <p style={{fontSize:'0.85rem',color:'var(--gray-400)'}}>{q.ringkasan}</p>
                <button className="btn btn-primary btn-sm" style={{marginTop:12}}>Mulai Kuis</button>
              </div>
            ))}
          </div>
        )}

        {tab === 'chatbot' && (
          <div className="card">
            <div className="chat-container">
              <div className="chat-messages">
                {messages.map((m, i) => (
                  <div key={i} className={`chat-bubble ${m.role === 'user' ? 'user' : 'bot'}`}>{m.text}</div>
                ))}
                {loading && <div className="chat-bubble bot"><span className="animate-pulse">Sedang mengetik...</span></div>}
              </div>
              <div className="chat-input-area">
                <input className="chat-input" value={input} onChange={e => setInput(e.target.value)}
                       onKeyDown={e => e.key === 'Enter' && sendMessage()}
                       placeholder="Tanyakan tentang Rhesus, Thalasemia, atau kesehatan pranikah..." />
                <button className="btn btn-primary" onClick={sendMessage} disabled={loading}>Kirim</button>
              </div>
            </div>
          </div>
        )}

        {tab === 'kuis' && quizState.active && (
          <div className="card animate-fade-in">
            <h3 className="card-title">{quizState.active.judul}</h3>
            {(quizState.active.quiz_data || []).map((q, qi) => (
              <div key={qi} style={{marginBottom:24}}>
                <p style={{fontWeight:600,marginBottom:8,color:'var(--gray-200)'}}>{qi+1}. {q.question}</p>
                {q.options.map((opt, oi) => {
                  let cls = 'quiz-option';
                  if (quizState.submitted) {
                    if (oi === q.correct) cls += ' correct';
                    else if (quizState.answers[qi] === oi) cls += ' wrong';
                  } else if (quizState.answers[qi] === oi) cls += ' selected';
                  return (
                    <div key={oi} className={cls} onClick={() => !quizState.submitted && setQuizState(prev => ({...prev, answers: {...prev.answers, [qi]: oi}}))}>
                      <span style={{fontWeight:600,color:'var(--gray-400)'}}>{String.fromCharCode(65+oi)}.</span> {opt}
                    </div>
                  );
                })}
              </div>
            ))}
            {!quizState.submitted ? (
              <button className="btn btn-primary" onClick={submitQuiz}>Kirim Jawaban</button>
            ) : (
              <div className="card-glass" style={{marginTop:16}}>
                <h4 style={{color: quizState.result.passed ? 'var(--risk-low)' : 'var(--risk-high)'}}>
                  {quizState.result.passed ? '🎉 Selamat!' : '📖 Perlu Belajar Lagi'}
                </h4>
                <p>Skor: <strong>{quizState.result.score}</strong>/100 ({quizState.result.correct}/{quizState.result.total} benar)</p>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}

// ==================== PAGE: LAB SCANNER ====================
function LabScannerPage() {
  const [form, setForm] = useState({ golongan_darah: '', rhesus: '', hemoglobin: '', mcv: '', mch: '', mchc: '', hba2: '', hbf: '', milik: 'wanita' });
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const analyze = async () => {
    setLoading(true);
    const res = await api('/lab-scan/analyze', { method: 'POST', body: JSON.stringify(form) });
    setResult(res);
    setLoading(false);
  };

  return (
    <div>
      <div className="page-header">
        <h2>🔬 AI Lab Scanner</h2>
        <p>Fitur 2 — Analisis hasil laboratorium darah dengan kecerdasan buatan Gemini</p>
      </div>
      <div className="page-body">
        <div className="grid-2">
          <div className="card">
            <h3 className="card-title">📝 Input Hasil Lab</h3>
            <div className="form-group">
              <label className="form-label">Milik</label>
              <select className="form-select" value={form.milik} onChange={e => setForm({...form, milik: e.target.value})}>
                <option value="pria">Calon Suami</option>
                <option value="wanita">Calon Istri</option>
              </select>
            </div>
            <div className="grid-2">
              <div className="form-group">
                <label className="form-label">Golongan Darah</label>
                <select className="form-select" value={form.golongan_darah} onChange={e => setForm({...form, golongan_darah: e.target.value})}>
                  <option value="">Pilih</option>
                  <option>A</option><option>B</option><option>AB</option><option>O</option>
                </select>
              </div>
              <div className="form-group">
                <label className="form-label">Rhesus</label>
                <select className="form-select" value={form.rhesus} onChange={e => setForm({...form, rhesus: e.target.value})}>
                  <option value="">Pilih</option>
                  <option value="positif">Positif (+)</option>
                  <option value="negatif">Negatif (-)</option>
                </select>
              </div>
            </div>
            <div className="grid-2">
              <div className="form-group">
                <label className="form-label">Hemoglobin (g/dL)</label>
                <input className="form-input" type="number" step="0.1" placeholder="12.0" value={form.hemoglobin} onChange={e => setForm({...form, hemoglobin: e.target.value})} />
              </div>
              <div className="form-group">
                <label className="form-label">MCV (fL)</label>
                <input className="form-input" type="number" step="0.1" placeholder="82.0" value={form.mcv} onChange={e => setForm({...form, mcv: e.target.value})} />
              </div>
            </div>
            <div className="grid-2">
              <div className="form-group">
                <label className="form-label">MCH (pg)</label>
                <input className="form-input" type="number" step="0.1" placeholder="28.0" value={form.mch} onChange={e => setForm({...form, mch: e.target.value})} />
              </div>
              <div className="form-group">
                <label className="form-label">HbA2 (%)</label>
                <input className="form-input" type="number" step="0.1" placeholder="2.5" value={form.hba2} onChange={e => setForm({...form, hba2: e.target.value})} />
              </div>
            </div>
            <button className="btn btn-primary btn-lg" style={{width:'100%'}} onClick={analyze} disabled={loading}>
              {loading ? <span className="spinner"></span> : '🔍 Analisis dengan AI'}
            </button>
          </div>

          <div>
            {result && (
              <div className="card animate-fade-in">
                <h3 className="card-title">📊 Hasil Analisis</h3>
                <div style={{marginBottom:16}}>
                  <span className="form-label">Level Risiko</span>
                  <span className={`risk-badge ${result.risk_level}`} style={{marginLeft:8}}>
                    {result.risk_level === 'rendah' ? '✅ Rendah' : result.risk_level === 'sedang' ? '⚠️ Sedang' : result.risk_level === 'tinggi' ? '🔴 Tinggi' : '🚨 Sangat Tinggi'}
                  </span>
                </div>
                <div style={{marginBottom:16}}>
                  <span className="form-label">Interpretasi AI</span>
                  <div style={{marginTop:8, padding:12, background:'rgba(255,255,255,0.03)', borderRadius:8, fontSize:'0.875rem', lineHeight:1.8, color:'var(--gray-300)', whiteSpace:'pre-wrap'}}>
                    {result.ai_interpretation}
                  </div>
                </div>
                <div>
                  <span className="form-label">Rekomendasi</span>
                  <div style={{marginTop:8, padding:12, background:'rgba(20,184,166,0.05)', border:'1px solid rgba(20,184,166,0.1)', borderRadius:8, fontSize:'0.875rem', lineHeight:1.8, color:'var(--primary-300)', whiteSpace:'pre-wrap'}}>
                    {result.recommendations}
                  </div>
                </div>
                <div className="medical-disclaimer">
                  ⚕️ Disclaimer: Interpretasi ini dihasilkan oleh AI dan BUKAN diagnosis medis final. Selalu konsultasikan hasil lab Anda dengan dokter.
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: GENETIC TREE ====================
function GeneticTreePage() {
  const [form, setForm] = useState({ rhesus_pria: 'positif', rhesus_wanita: 'negatif', genotipe_pria: 'Dd', genotipe_wanita: 'dd', carrier_thalasemia_pria: true, carrier_thalasemia_wanita: true });
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const calculate = async () => {
    setLoading(true);
    const res = await api('/genetic-tree/calculate', { method: 'POST', body: JSON.stringify(form) });
    setResult(res);
    setLoading(false);
  };

  return (
    <div>
      <div className="page-header">
        <h2>🧬 Pohon Genetik Visual</h2>
        <p>Fitur 3 — Prediksi risiko keturunan berdasarkan genetika Mendel + penjelasan AI empatik</p>
      </div>
      <div className="page-body">
        <div className="grid-2">
          <div className="card">
            <h3 className="card-title">Input Data Pasangan</h3>
            <div className="grid-2">
              <div className="form-group">
                <label className="form-label">Rhesus Pria</label>
                <select className="form-select" value={form.rhesus_pria} onChange={e => setForm({...form, rhesus_pria: e.target.value})}>
                  <option value="positif">Positif (+)</option><option value="negatif">Negatif (-)</option>
                </select>
              </div>
              <div className="form-group">
                <label className="form-label">Rhesus Wanita</label>
                <select className="form-select" value={form.rhesus_wanita} onChange={e => setForm({...form, rhesus_wanita: e.target.value})}>
                  <option value="positif">Positif (+)</option><option value="negatif">Negatif (-)</option>
                </select>
              </div>
            </div>
            <div className="grid-2">
              <div className="form-group">
                <label className="form-label">Genotipe Pria</label>
                <select className="form-select" value={form.genotipe_pria} onChange={e => setForm({...form, genotipe_pria: e.target.value})}>
                  <option value="DD">DD (Homozigot +)</option><option value="Dd">Dd (Heterozigot +)</option><option value="dd">dd (Negatif)</option>
                </select>
              </div>
              <div className="form-group">
                <label className="form-label">Genotipe Wanita</label>
                <select className="form-select" value={form.genotipe_wanita} onChange={e => setForm({...form, genotipe_wanita: e.target.value})}>
                  <option value="DD">DD (Homozigot +)</option><option value="Dd">Dd (Heterozigot +)</option><option value="dd">dd (Negatif)</option>
                </select>
              </div>
            </div>
            <div className="grid-2">
              <div className="form-group">
                <label className="form-label" style={{display:'flex',alignItems:'center',gap:8}}>
                  <input type="checkbox" checked={form.carrier_thalasemia_pria} onChange={e => setForm({...form, carrier_thalasemia_pria: e.target.checked})} /> Pria Carrier Thalasemia
                </label>
              </div>
              <div className="form-group">
                <label className="form-label" style={{display:'flex',alignItems:'center',gap:8}}>
                  <input type="checkbox" checked={form.carrier_thalasemia_wanita} onChange={e => setForm({...form, carrier_thalasemia_wanita: e.target.checked})} /> Wanita Carrier Thalasemia
                </label>
              </div>
            </div>
            <button className="btn btn-primary btn-lg" style={{width:'100%'}} onClick={calculate} disabled={loading}>
              {loading ? <span className="spinner"></span> : '🧬 Hitung Probabilitas'}
            </button>
          </div>

          {result && (
            <div className="animate-fade-in">
              <div className="card" style={{marginBottom:16}}>
                <h3 className="card-title">🩸 Risiko Rhesus</h3>
                <div className="genetic-tree">
                  <div className="parent-row">
                    <div className="parent-card"><div className="gender-icon">👨</div><div className="name">Calon Suami</div><div className="blood-info">Genotipe: {result.genotipe_pria}</div></div>
                    <div className="connector-horizontal"></div>
                    <div className="parent-card"><div className="gender-icon">👩</div><div className="name">Calon Istri</div><div className="blood-info">Genotipe: {result.genotipe_wanita}</div></div>
                  </div>
                  <div className="connector-line"></div>
                  <div className="children-row">
                    {result.rhesus.probabilitas.rh_positif > 0 && (
                      <div className="child-card" style={{background:'rgba(34,197,94,0.1)',border:'1px solid rgba(34,197,94,0.3)'}}>
                        <div style={{fontSize:'1.5rem',fontWeight:800,color:'var(--risk-low)'}}>{result.rhesus.probabilitas.rh_positif}%</div>
                        <div>Rh Positif</div>
                      </div>
                    )}
                    {result.rhesus.probabilitas.rh_negatif > 0 && (
                      <div className="child-card" style={{background:'rgba(249,115,22,0.1)',border:'1px solid rgba(249,115,22,0.3)'}}>
                        <div style={{fontSize:'1.5rem',fontWeight:800,color:'var(--risk-high)'}}>{result.rhesus.probabilitas.rh_negatif}%</div>
                        <div>Rh Negatif</div>
                      </div>
                    )}
                  </div>
                </div>
                <div style={{textAlign:'center',marginTop:8}}>
                  <span className={`risk-badge ${result.rhesus.risk_level}`}>{result.rhesus.risk_level}</span>
                </div>
                <p style={{fontSize:'0.85rem',color:'var(--gray-300)',marginTop:12}}>{result.rhesus.penjelasan}</p>
              </div>

              <div className="card" style={{marginBottom:16}}>
                <h3 className="card-title">🧬 Risiko Thalasemia</h3>
                <div className="children-row" style={{justifyContent:'center',padding:16}}>
                  {Object.entries(result.thalasemia.probabilitas).map(([k, v]) => v > 0 && (
                    <div key={k} className="child-card" style={{
                      background: k === 'mayor' ? 'rgba(220,38,38,0.1)' : k === 'carrier' ? 'rgba(234,179,8,0.1)' : 'rgba(34,197,94,0.1)',
                      border: `1px solid ${k === 'mayor' ? 'rgba(220,38,38,0.3)' : k === 'carrier' ? 'rgba(234,179,8,0.3)' : 'rgba(34,197,94,0.3)'}`,
                    }}>
                      <div style={{fontSize:'1.5rem',fontWeight:800,color: k === 'mayor' ? 'var(--risk-critical)' : k === 'carrier' ? 'var(--risk-medium)' : 'var(--risk-low)'}}>{v}%</div>
                      <div style={{textTransform:'capitalize'}}>{k}</div>
                    </div>
                  ))}
                </div>
                <div style={{textAlign:'center'}}><span className={`risk-badge ${result.thalasemia.risk_level}`}>{result.thalasemia.risk_level}</span></div>
                <p style={{fontSize:'0.85rem',color:'var(--gray-300)',marginTop:12}}>{result.thalasemia.penjelasan}</p>
              </div>

              {result.ai_explanation && (
                <div className="card">
                  <h3 className="card-title">💬 Penjelasan AI (Edu-Bot)</h3>
                  <div style={{fontSize:'0.9rem',lineHeight:1.8,color:'var(--gray-300)',whiteSpace:'pre-wrap'}}>{result.ai_explanation}</div>
                  <div className="medical-disclaimer">⚕️ Ini bukan diagnosis medis final. Konsultasikan dengan dokter/konselor genetik.</div>
                </div>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: GEO-TRIAGE (Faskes Map) ====================
function GeoTriagePage() {
  const [faskes, setFaskes] = useState([]);
  const [filter, setFilter] = useState({ has_rhogam: false, has_darah_rh_negatif: false, has_transfusi_thalasemia: false });
  const mapRef = useRef(null);
  const mapInstanceRef = useRef(null);

  useEffect(() => {
    const params = new URLSearchParams();
    if (filter.has_rhogam) params.set('has_rhogam', '1');
    if (filter.has_darah_rh_negatif) params.set('has_darah_rh_negatif', '1');
    if (filter.has_transfusi_thalasemia) params.set('has_transfusi_thalasemia', '1');
    api(`/geo-triage/faskes?${params.toString()}`).then(setFaskes);
  }, [filter]);

  useEffect(() => {
    if (!mapRef.current || mapInstanceRef.current) return;
    const map = L.map(mapRef.current, { center: [-2.5, 118], zoom: 5 });
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OSM &copy; CARTO' }).addTo(map);
    mapInstanceRef.current = map;
    return () => { map.remove(); mapInstanceRef.current = null; };
  }, []);

  useEffect(() => {
    if (!mapInstanceRef.current) return;
    const map = mapInstanceRef.current;
    map.eachLayer(l => { if (l instanceof L.Marker) map.removeLayer(l); });
    faskes.forEach(f => {
      const iconColor = f.has_rhogam ? '🟢' : f.has_darah_rh_negatif ? '🟡' : '🔴';
      const icon = L.divIcon({ html: `<div style="font-size:20px;text-align:center">${iconColor}</div>`, iconSize: [24, 24], className: '' });
      L.marker([f.latitude, f.longitude], { icon }).addTo(map).bindPopup(`
        <div class="province-popup">
          <h3>${f.nama}</h3>
          <div class="popup-stat"><span>Tipe</span><strong>${f.tipe.toUpperCase()}</strong></div>
          <div class="popup-stat"><span>RhoGAM</span><strong>${f.has_rhogam ? '✅ Ada' : '❌ Tidak'}</strong></div>
          <div class="popup-stat"><span>Darah Rh-</span><strong>${f.has_darah_rh_negatif ? '✅ Ada' : '❌ Tidak'}</strong></div>
          <div class="popup-stat"><span>Transfusi Thal</span><strong>${f.has_transfusi_thalasemia ? '✅ Ada' : '❌ Tidak'}</strong></div>
          <div class="popup-stat"><span>Lokasi</span><strong>${f.city}, ${f.province}</strong></div>
        </div>
      `);
    });
  }, [faskes]);

  return (
    <div>
      <div className="page-header">
        <h2>🏥 AI Geo-Triage & Pemetaan Faskes</h2>
        <p>Fitur 4 — Temukan faskes dengan stok RhoGAM, darah Rh-, dan layanan thalasemia</p>
      </div>
      <div className="page-body">
        <div className="layer-toggles" style={{marginBottom:16}}>
          <button className={`layer-toggle ${filter.has_rhogam ? 'active' : ''}`} onClick={() => setFilter({...filter, has_rhogam: !filter.has_rhogam})}>💉 RhoGAM</button>
          <button className={`layer-toggle ${filter.has_darah_rh_negatif ? 'active' : ''}`} onClick={() => setFilter({...filter, has_darah_rh_negatif: !filter.has_darah_rh_negatif})}>🩸 Darah Rh-</button>
          <button className={`layer-toggle ${filter.has_transfusi_thalasemia ? 'active' : ''}`} onClick={() => setFilter({...filter, has_transfusi_thalasemia: !filter.has_transfusi_thalasemia})}>🧬 Transfusi Thalasemia</button>
        </div>
        <div className="map-container" ref={mapRef}></div>
        <div style={{marginTop:16}}>
          <h3 className="card-title">📋 Daftar Faskes ({faskes.length})</h3>
          <table className="data-table">
            <thead><tr><th>Nama</th><th>Tipe</th><th>Lokasi</th><th>RhoGAM</th><th>Darah Rh-</th><th>Thal</th></tr></thead>
            <tbody>
              {faskes.map(f => (
                <tr key={f.id}>
                  <td style={{fontWeight:600}}>{f.nama}</td>
                  <td><span className="risk-badge rendah">{f.tipe}</span></td>
                  <td>{f.city}, {f.province}</td>
                  <td>{f.has_rhogam ? '✅' : '❌'}</td>
                  <td>{f.has_darah_rh_negatif ? '✅' : '❌'}</td>
                  <td>{f.has_transfusi_thalasemia ? '✅' : '❌'}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: 72-HOUR TRACKER ====================
function TrackerPage() {
  const [cases, setCases] = useState([]);
  const [selectedCase, setSelectedCase] = useState(null);
  const [timer, setTimer] = useState(null);

  useEffect(() => { api('/tracker').then(setCases); }, []);

  const viewCase = async (id) => {
    const res = await api(`/tracker/${id}`);
    setSelectedCase(res.case);
    setTimer(res.timer);
  };

  const completeCase = async (id) => {
    await api(`/tracker/${id}/complete`, { method: 'POST' });
    viewCase(id);
    api('/tracker').then(setCases);
  };

  const timerColor = timer ? (timer.hours_remaining <= 12 ? 'var(--risk-critical)' : timer.hours_remaining <= 24 ? 'var(--risk-high)' : timer.hours_remaining <= 48 ? 'var(--risk-medium)' : 'var(--risk-low)') : 'white';

  return (
    <div>
      <div className="page-header">
        <h2>⏱️ 72-Hour Golden Tracker</h2>
        <p>Fitur 5 — Timer otomatis pemberian RhoGAM pasca-persalinan ibu Rh negatif</p>
      </div>
      <div className="page-body">
        <div className="grid-2">
          <div className="card">
            <h3 className="card-title">📋 Kasus Aktif</h3>
            {cases.length === 0 ? <p style={{color:'var(--gray-400)',fontSize:'0.9rem'}}>Belum ada kasus tracker aktif.</p> : (
              cases.map(c => (
                <div key={c.id} style={{padding:12,borderBottom:'1px solid rgba(255,255,255,0.06)',cursor:'pointer'}} onClick={() => viewCase(c.id)}>
                  <div style={{display:'flex',justifyContent:'space-between',alignItems:'center'}}>
                    <div>
                      <div style={{fontWeight:600}}>{c.catin_profile?.nama_wanita || 'Pasien'}</div>
                      <div style={{fontSize:'0.8rem',color:'var(--gray-400)'}}>Sisa: {c.hours_remaining}h</div>
                    </div>
                    <span className={`risk-badge ${c.status === 'selesai' ? 'rendah' : c.status === 'lewat_batas' ? 'sangat_tinggi' : 'tinggi'}`}>{c.status}</span>
                  </div>
                </div>
              ))
            )}
          </div>

          {selectedCase && timer && (
            <div className="card animate-fade-in">
              <div className="timer-display">
                <div className="timer-value" style={{color: timerColor}}>
                  {Math.floor(timer.hours_remaining)}h {Math.round((timer.hours_remaining % 1) * 60)}m
                </div>
                <div className="timer-label">sisa waktu dari 72 jam</div>
                <div className="progress-bar" style={{marginTop:16}}>
                  <div className="progress-bar-fill" style={{width:`${timer.percent_complete}%`, background: timerColor}}></div>
                </div>
                <div style={{fontSize:'0.8rem',color:'var(--gray-400)',marginTop:8}}>
                  {timer.hours_elapsed.toFixed(1)} jam telah berlalu
                </div>
              </div>

              <div className="escalation-timeline">
                <div className={`escalation-step ${timer.hours_elapsed >= 0 ? 'completed' : 'pending'}`}>🟢 Timer dimulai (0 jam)</div>
                <div className={`escalation-step ${timer.hours_elapsed >= 24 ? (timer.hours_elapsed >= 48 ? 'completed' : 'active') : 'pending'}`}>🟡 Jam ke-24: Notifikasi bidan</div>
                <div className={`escalation-step ${timer.hours_elapsed >= 48 ? (timer.hours_elapsed >= 60 ? 'completed' : 'active') : 'pending'}`}>🟠 Jam ke-48: Prioritas tinggi + Geo-Triage</div>
                <div className={`escalation-step ${timer.hours_elapsed >= 60 ? (timer.hours_elapsed >= 72 ? 'completed' : 'active') : 'pending'}`}>🔴 Jam ke-60: Eskalasi Dinkes + Code Red</div>
                <div className={`escalation-step ${timer.is_expired ? 'active' : 'pending'}`}>⛔ 72 jam: BATAS WAKTU TERLEWAT</div>
              </div>

              {selectedCase.status === 'menunggu' && (
                <button className="btn btn-primary" style={{width:'100%',marginTop:12}} onClick={() => completeCase(selectedCase.id)}>
                  ✅ RhoGAM Telah Diberikan — Selesaikan Kasus
                </button>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: ACTION PLAN ====================
function ActionPlanPage() {
  const [summary, setSummary] = useState('Ibu Rh negatif, ayah Rh positif heterozigot (Dd). Kehamilan pertama, usia kandungan 28 minggu. Belum pernah mendapat RhoGAM.');
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const generate = async () => {
    setLoading(true);
    const res = await api('/action-plan/generate', { method: 'POST', body: JSON.stringify({ catin_profile_id: 1, case_summary: summary }) });
    setResult(res);
    setLoading(false);
  };

  return (
    <div>
      <div className="page-header">
        <h2>📋 AI Action-Plan Generator</h2>
        <p>Fitur 6 — Draf rencana aksi medis dari AI untuk bidan/nakes (human-in-the-loop wajib)</p>
      </div>
      <div className="page-body">
        <div className="card" style={{marginBottom:16}}>
          <h3 className="card-title">📝 Ringkasan Kasus</h3>
          <textarea className="form-textarea" value={summary} onChange={e => setSummary(e.target.value)} rows={4}></textarea>
          <button className="btn btn-primary" style={{marginTop:12}} onClick={generate} disabled={loading}>
            {loading ? <><span className="spinner"></span> Menghasilkan...</> : '🤖 Generate Rencana Aksi'}
          </button>
        </div>

        {result && (
          <div className="card animate-fade-in">
            <h3 className="card-title">📋 Draf Rencana Aksi (AI)</h3>
            <div className="risk-badge tinggi" style={{marginBottom:12}}>⚠️ DRAF — Perlu Review Nakes</div>
            <div style={{padding:16, background:'rgba(255,255,255,0.03)', borderRadius:8, fontSize:'0.9rem', lineHeight:1.8, color:'var(--gray-300)', whiteSpace:'pre-wrap'}}>
              {result.ai_draft}
            </div>
            <div style={{display:'flex',gap:8,marginTop:16}}>
              <button className="btn btn-primary">✅ Setujui Rencana</button>
              <button className="btn btn-outline">✏️ Edit</button>
              <button className="btn btn-danger">❌ Tolak</button>
            </div>
            <div className="medical-disclaimer">⚕️ Rencana ini adalah DRAF AI. Keputusan medis final ada di tangan tenaga kesehatan.</div>
          </div>
        )}
      </div>
    </div>
  );
}

// ==================== PAGE: REFERRAL CARD ====================
function ReferralCardPage() {
  const [card, setCard] = useState(null);
  const [loading, setLoading] = useState(false);

  const generate = async () => {
    setLoading(true);
    const res = await api('/referral/generate', {
      method: 'POST',
      body: JSON.stringify({ catin_profile_id: 1, risk_level: 'tinggi', ringkasan_risiko: { rhesus: 'Ibu Rh-, Ayah Rh+', thalasemia: 'Kedua carrier' } }),
    });
    setCard(res);
    setLoading(false);
  };

  return (
    <div>
      <div className="page-header">
        <h2>💳 Kartu Rujukan Digital</h2>
        <p>Fitur 7 — Referral Card dengan QR code untuk rujukan cepat ke faskes</p>
      </div>
      <div className="page-body">
        {!card ? (
          <div className="card" style={{textAlign:'center',padding:40}}>
            <div style={{fontSize:'3rem',marginBottom:16}}>💳</div>
            <p style={{color:'var(--gray-400)',marginBottom:16}}>Buat kartu rujukan digital berisi ringkasan hasil skrining</p>
            <button className="btn btn-primary btn-lg" onClick={generate} disabled={loading}>
              {loading ? <span className="spinner"></span> : '🔄 Buat Kartu Rujukan'}
            </button>
          </div>
        ) : (
          <div className="card animate-fade-in" style={{maxWidth:500,margin:'0 auto'}}>
            <div style={{background:'linear-gradient(135deg, var(--primary-700), var(--primary-900))', borderRadius:'var(--radius-md)', padding:24, color:'white', marginBottom:16}}>
              <div style={{display:'flex',justifyContent:'space-between',alignItems:'center',marginBottom:16}}>
                <div><div style={{fontSize:'0.7rem',opacity:0.7,textTransform:'uppercase'}}>CatinGuard Referral Card</div><div style={{fontSize:'1.25rem',fontWeight:700}}>🛡️ CatinGuard</div></div>
                <div style={{background:'white',padding:8,borderRadius:8,fontSize:'0.65rem',color:'var(--gray-800)',fontWeight:700,textAlign:'center'}}>QR<br/>{card.kode_referral}</div>
              </div>
              <div style={{fontSize:'1.1rem',fontWeight:600}}>{card.kode_referral}</div>
              <div style={{display:'flex',gap:16,marginTop:12,fontSize:'0.8rem',opacity:0.8}}>
                <span className={`risk-badge ${card.risk_level}`}>{card.risk_level}</span>
                <span>Status: {card.status}</span>
              </div>
            </div>
            <h4 style={{marginBottom:8}}>Ringkasan Risiko:</h4>
            {Object.entries(card.ringkasan_risiko || {}).map(([k, v]) => (
              <div key={k} style={{display:'flex',justifyContent:'space-between',padding:'6px 0',borderBottom:'1px solid rgba(255,255,255,0.06)',fontSize:'0.875rem'}}>
                <span style={{color:'var(--gray-400)',textTransform:'capitalize'}}>{k}</span>
                <span>{v}</span>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

// ==================== PAGE: DONOR NETWORK ====================
function DonorNetworkPage() {
  const [donors, setDonors] = useState([]);
  useEffect(() => { api('/donors').then(setDonors); }, []);

  return (
    <div>
      <div className="page-header">
        <h2>🩸 Jaringan Donor Darurat Terverifikasi</h2>
        <p>Fitur 8 — Direktori donor darah langka Rh negatif, opt-in & terverifikasi</p>
      </div>
      <div className="page-body">
        <div className="stats-grid">
          <div className="stat-card"><div className="stat-icon" style={{background:'rgba(220,38,38,0.15)',color:'var(--risk-critical)'}}>🩸</div><div className="stat-value">{donors.length}</div><div className="stat-label">Donor Terdaftar</div></div>
          <div className="stat-card"><div className="stat-icon" style={{background:'rgba(34,197,94,0.15)',color:'var(--risk-low)'}}>✅</div><div className="stat-value">{donors.filter(d=>d.is_available).length}</div><div className="stat-label">Tersedia Saat Ini</div></div>
          <div className="stat-card"><div className="stat-icon" style={{background:'rgba(99,102,241,0.15)',color:'#818cf8'}}>🔬</div><div className="stat-value">{donors.filter(d=>d.is_verified).length}</div><div className="stat-label">Terverifikasi</div></div>
        </div>
        <div className="card">
          <h3 className="card-title">📋 Daftar Donor</h3>
          <table className="data-table">
            <thead><tr><th>Nama</th><th>Gol. Darah</th><th>Rhesus</th><th>Provinsi</th><th>Status</th></tr></thead>
            <tbody>
              {donors.map(d => (
                <tr key={d.id}>
                  <td style={{fontWeight:600}}>{d.user?.name || 'Anonim'}</td>
                  <td>{d.golongan_darah}</td>
                  <td><span className="risk-badge tinggi">Rh {d.rhesus}</span></td>
                  <td>{d.province}</td>
                  <td>{d.is_available ? <span className="risk-badge rendah">Tersedia</span> : <span className="risk-badge sedang">Tidak Tersedia</span>}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: READINESS SCORE ====================
function ReadinessScorePage() {
  const [score, setScore] = useState(null);
  useEffect(() => { api('/readiness-score/1').then(setScore).catch(()=>{}); }, []);

  const circumference = 2 * Math.PI * 80;
  const offset = score ? circumference - (score.total_score / 100) * circumference : circumference;
  const color = score ? (score.total_score >= 70 ? 'var(--risk-low)' : score.total_score >= 40 ? 'var(--risk-medium)' : 'var(--risk-high)') : 'var(--gray-600)';

  return (
    <div>
      <div className="page-header">
        <h2>🎯 Skor Kesiapsiagaan Kehamilan</h2>
        <p>Fitur 9 — Gamifikasi ringan untuk mendorong kelengkapan skrining pranikah</p>
      </div>
      <div className="page-body">
        <div className="grid-2">
          <div className="card" style={{textAlign:'center',padding:40}}>
            <div className="progress-ring">
              <svg width="200" height="200">
                <circle cx="100" cy="100" r="80" fill="none" stroke="rgba(255,255,255,0.06)" strokeWidth="12" />
                <circle cx="100" cy="100" r="80" fill="none" stroke={color} strokeWidth="12"
                        strokeDasharray={circumference} strokeDashoffset={offset}
                        strokeLinecap="round" style={{transition:'stroke-dashoffset 1s ease'}} />
              </svg>
              <div className="value" style={{fontSize:'2.5rem'}}>{score?.total_score || 0}</div>
            </div>
            <div style={{marginTop:16,fontSize:'1.1rem',fontWeight:600}}>dari 100 poin</div>
          </div>

          <div className="card">
            <h3 className="card-title">📊 Breakdown Skor</h3>
            {[
              { label: 'Skrining Lab', score: score?.skor_skrining || 0, max: 30, icon: '🔬' },
              { label: 'Kepatuhan Jadwal', score: score?.skor_kepatuhan || 0, max: 30, icon: '📅' },
              { label: 'Edukasi & Kuis', score: score?.skor_edukasi || 0, max: 40, icon: '📚' },
            ].map(s => (
              <div key={s.label} style={{marginBottom:16}}>
                <div style={{display:'flex',justifyContent:'space-between',marginBottom:4,fontSize:'0.85rem'}}>
                  <span>{s.icon} {s.label}</span>
                  <span style={{fontWeight:700}}>{s.score}/{s.max}</span>
                </div>
                <div className="progress-bar">
                  <div className="progress-bar-fill" style={{width:`${(s.score/s.max)*100}%`, background:'var(--primary-500)'}}></div>
                </div>
              </div>
            ))}

            <h3 className="card-title" style={{marginTop:24}}>✅ Checklist</h3>
            {score?.detail && Object.entries(score.detail).map(([k, v]) => (
              <div key={k} style={{display:'flex',alignItems:'center',gap:8,padding:'6px 0',fontSize:'0.875rem'}}>
                <span>{v ? '✅' : '⬜'}</span>
                <span style={{color: v ? 'var(--gray-200)' : 'var(--gray-500)',textTransform:'capitalize'}}>{k.replace(/_/g, ' ')}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: COST SIMULATOR ====================
function CostSimulatorPage() {
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [selected, setSelected] = useState(null);

  const costs = [
    { id: 'tes_golongan_darah', nama: 'Tes Golongan Darah & Rhesus', min: 50000, max: 150000, bpjs: 'covered' },
    { id: 'cbc', nama: 'Complete Blood Count (CBC)', min: 100000, max: 300000, bpjs: 'covered' },
    { id: 'elektroforesis_hb', nama: 'Elektroforesis Hemoglobin', min: 250000, max: 500000, bpjs: 'partial' },
    { id: 'rhogam', nama: 'Injeksi RhoGAM (Anti-D)', min: 1500000, max: 3000000, bpjs: 'partial' },
    { id: 'konseling_genetik', nama: 'Konseling Genetik', min: 200000, max: 500000, bpjs: 'not-covered' },
    { id: 'paket_pranikah', nama: 'Paket Pranikah Lengkap', min: 500000, max: 1500000, bpjs: 'partial' },
  ];

  const simulate = async (id) => {
    setSelected(id);
    setLoading(true);
    const res = await api('/cost-simulate', { method: 'POST', body: JSON.stringify({ jenis_pemeriksaan: id, province: 'Jawa Barat' }) });
    setResult(res);
    setLoading(false);
  };

  return (
    <div>
      <div className="page-header">
        <h2>💰 Simulasi Biaya & Estimasi BPJS</h2>
        <p>Fitur 11 — Estimasi biaya pemeriksaan kesehatan pranikah</p>
      </div>
      <div className="page-body">
        <div className="grid-2">
          <div>
            {costs.map(c => (
              <div key={c.id} className={`cost-card ${selected === c.id ? 'selected' : ''}`} onClick={() => simulate(c.id)}
                   style={selected === c.id ? {borderColor:'var(--primary-400)',background:'rgba(20,184,166,0.05)'} : {}}>
                <div className="cost-name">{c.nama}</div>
                <div className="cost-range">{formatRupiah(c.min)} – {formatRupiah(c.max)}</div>
                <span className={`bpjs-tag ${c.bpjs}`}>
                  {c.bpjs === 'covered' ? '✅ BPJS Covered' : c.bpjs === 'partial' ? '⚠️ Sebagian BPJS' : '❌ Non-BPJS'}
                </span>
              </div>
            ))}
          </div>
          <div>
            {loading && <div className="card"><div className="spinner" style={{margin:'40px auto'}}></div></div>}
            {result && !loading && (
              <div className="card animate-fade-in">
                <h3 className="card-title">💡 Penjelasan AI</h3>
                <div style={{fontSize:'0.9rem',lineHeight:1.8,color:'var(--gray-300)',whiteSpace:'pre-wrap'}}>{result.ai_explanation}</div>
                <div className="medical-disclaimer">💡 Biaya bisa bervariasi antar daerah dan fasilitas kesehatan.</div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

// ==================== PAGE: CERTIFICATE ====================
function CertificatePage() {
  const [cert, setCert] = useState(null);
  useEffect(() => { api('/certificate/1').then(setCert).catch(()=>{}); }, []);

  return (
    <div>
      <div className="page-header">
        <h2>📜 Sertifikat Skrining Pranikah Digital</h2>
        <p>Fitur 12 — Bukti skrining kesehatan yang bisa diekspor ke KUA</p>
      </div>
      <div className="page-body">
        {cert && (
          <div className="certificate animate-fade-in" style={{maxWidth:600,margin:'0 auto'}}>
            <div style={{fontSize:'0.7rem',color:'var(--gray-500)',marginBottom:8}}>🛡️ CATINGUARD</div>
            <h2>Sertifikat Skrining Kesehatan Pranikah</h2>
            <div className="cert-number">{cert.nomor_sertifikat}</div>
            <div style={{margin:'24px 0',fontSize:'0.9rem',color:'var(--gray-600)'}}>Menerangkan bahwa pasangan calon pengantin:</div>
            <div className="names">{cert.nama_pria} & {cert.nama_wanita}</div>
            <div style={{display:'flex',justifyContent:'center',gap:32,margin:'16px 0',fontSize:'0.875rem'}}>
              <div><div style={{color:'var(--gray-500)'}}>Gol. Darah Pria</div><strong>{cert.golongan_darah_pria}</strong></div>
              <div><div style={{color:'var(--gray-500)'}}>Gol. Darah Wanita</div><strong>{cert.golongan_darah_wanita}</strong></div>
            </div>
            <div style={{padding:12,background:'rgba(20,184,166,0.1)',borderRadius:8,margin:'16px 0'}}>
              <strong>Status Skrining: {cert.status_skrining}</strong><br/>
              <strong>Skor Kesiapan: {cert.readiness_score}/100</strong>
            </div>
            <div style={{fontSize:'0.8rem',color:'var(--gray-500)',marginTop:16}}>
              Diterbitkan pada {cert.tanggal_terbit}<br/>
              <em>Sertifikat ini bukan pengganti surat keterangan dokter</em>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

// ==================== PAGE: EPIDEMIOLOGI ====================
function EpidemiologiPage() {
  const [data, setData] = useState(null);
  useEffect(() => { api('/epidemiologi/stats').then(setData); }, []);

  if (!data) return <div className="page-body"><div className="spinner"></div></div>;

  return (
    <div>
      <div className="page-header">
        <h2>📈 Dashboard Epidemiologi</h2>
        <p>Fitur 14 — Data agregat anonim tren risiko per wilayah untuk Dinas Kesehatan</p>
      </div>
      <div className="page-body">
        <div className="stats-grid">
          <div className="stat-card"><div className="stat-icon" style={{background:'rgba(20,184,166,0.15)',color:'var(--primary-400)'}}>🗺️</div><div className="stat-value">{data.summary.total_provinces}</div><div className="stat-label">Provinsi Terpantau</div></div>
          <div className="stat-card"><div className="stat-icon" style={{background:'rgba(220,38,38,0.15)',color:'var(--risk-critical)'}}>⚠️</div><div className="stat-value">{data.summary.high_risk_provinces}</div><div className="stat-label">Provinsi Risiko Tinggi</div></div>
          <div className="stat-card"><div className="stat-icon" style={{background:'rgba(234,179,8,0.15)',color:'var(--risk-medium)'}}>💔</div><div className="stat-value">{data.summary.avg_aki}</div><div className="stat-label">Rata-rata AKI</div></div>
          <div className="stat-card"><div className="stat-icon" style={{background:'rgba(99,102,241,0.15)',color:'#818cf8'}}>🧬</div><div className="stat-value">{data.summary.total_thalasemia.toLocaleString()}</div><div className="stat-label">Total Kasus Thalasemia</div></div>
        </div>

        <div className="card">
          <h3 className="card-title">📊 Data Per Provinsi (Diurutkan Risiko Tertinggi)</h3>
          <div style={{overflowX:'auto'}}>
            <table className="data-table">
              <thead>
                <tr><th>Provinsi</th><th>Skor Risiko</th><th>AKI</th><th>Rh- %</th><th>Defisit</th><th>Literasi</th><th>Thal</th><th>Faskes</th></tr>
              </thead>
              <tbody>
                {data.provinces.map(p => (
                  <tr key={p.province_code}>
                    <td style={{fontWeight:600}}>{p.province_name}</td>
                    <td><span className={`risk-badge ${p.risk_score >= 50 ? 'sangat_tinggi' : p.risk_score >= 35 ? 'tinggi' : p.risk_score >= 20 ? 'sedang' : 'rendah'}`}>{p.risk_score}</span></td>
                    <td>{p.aki}</td>
                    <td>{p.populasi_rh_negatif_persen}%</td>
                    <td>{p.defisit_stok_rh_negatif}</td>
                    <td>{p.indeks_literasi}/5</td>
                    <td>{p.kasus_thalasemia.toLocaleString()}</td>
                    <td>{p.jumlah_faskes}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}

// ==================== RENDER ====================
const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<App />);
