import { useState, useEffect } from 'react'
import './App.css'

function App() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);
  const [manualMode, setManualMode] = useState(() => localStorage.getItem('manualMode') === 'true');
  const [manualJD, setManualJD] = useState(() => localStorage.getItem('manualJD') || '');
  const [resumeFile, setResumeFile] = useState<File | null>(null);
  const [selectedTemplate, setSelectedTemplate] = useState(() => localStorage.getItem('selectedTemplate') || 'classic');
  const [downloadName, setDownloadName] = useState(() => localStorage.getItem('downloadName') || '');
  const [showSettings, setShowSettings] = useState(false);
  const [resumeData, setResumeData] = useState<any>(null);
  const [showApiKey, setShowApiKey] = useState(false);

  // BYOK Settings (Temp values for editing)
  const [tempApiKey, setTempApiKey] = useState(() => localStorage.getItem('user_api_key') || '');
  const [tempBaseUrl, setTempBaseUrl] = useState(() => localStorage.getItem('user_base_url') || 'https://api.x.ai/v1');
  const [tempModel, setTempModel] = useState(() => localStorage.getItem('user_model') || 'grok-4-1-fast-non-reasoning');

  // Committed BYOK Settings
  const [userApiKey, setUserApiKey] = useState(() => localStorage.getItem('user_api_key') || '');
  const [userBaseUrl, setUserBaseUrl] = useState(() => localStorage.getItem('user_base_url') || 'https://api.x.ai/v1');
  const [userModel, setUserModel] = useState(() => localStorage.getItem('user_model') || 'grok-4-1-fast-non-reasoning');

  const [result, setResult] = useState(() => {
    const saved = localStorage.getItem('result');
    return saved ? JSON.parse(saved) : null;
  });

  // MASKED API BASE: Pointing to your final Cloudflare Proxy
  // The domain is fragmented so it cannot be searched as a single string
  const _0x1a2b = (a: any[]) => a.join('');
  const API_BASE = _0x1a2b([
    atob("aHR0cHM6Ly8="), // "https://"
    "dark-wind-",
    "4f93",
    ".",
    "quickresumetailor",
    ".workers.dev/",
    "api"
  ]);

  const saveSettings = () => {
    // Clean up inputs (remove spaces and redundant Bearer prefix)
    const cleanKey = tempApiKey.trim().replace(/^Bearer\s+/i, '');
    const cleanUrl = tempBaseUrl.trim();
    const cleanModel = tempModel.trim();

    setUserApiKey(cleanKey);
    setUserBaseUrl(cleanUrl);
    setUserModel(cleanModel);
    
    localStorage.setItem('user_api_key', cleanKey);
    localStorage.setItem('user_base_url', cleanUrl);
    localStorage.setItem('user_model', cleanModel);
    
    setSuccess("AI Configuration saved successfully!");
    setTimeout(() => setSuccess(null), 3000);
  };

  // Persist state to local storage when it changes
  useEffect(() => { localStorage.setItem('manualMode', String(manualMode)); }, [manualMode]);
  useEffect(() => { localStorage.setItem('manualJD', manualJD); }, [manualJD]);
  useEffect(() => { localStorage.setItem('selectedTemplate', selectedTemplate); }, [selectedTemplate]);
  useEffect(() => { localStorage.setItem('downloadName', downloadName); }, [downloadName]);
  useEffect(() => {
    if (result) localStorage.setItem('result', JSON.stringify(result));
    else localStorage.removeItem('result');
  }, [result]);

  const getHeaders = () => {
    const headers: any = {
      "Accept": "application/json",
    };
    if (userApiKey) headers["X-AI-API-KEY"] = userApiKey;
    if (userBaseUrl) headers["X-AI-API-URL"] = userBaseUrl;
    if (userModel) headers["X-AI-MODEL"] = userModel;
    return headers;
  };

  useEffect(() => {
    // Check if the user already has a base resume on load
    fetch(`${API_BASE}/resume/status`, {
      headers: getHeaders()
    })
      .then(res => res.json())
      .then(data => {
        if (data.has_resume) {
          setSuccess("Base resume is already uploaded and ready for tailoring.");
          setResumeData(data.resume_data);
        }
      })
      .catch(() => {
        // Silent fail if backend is unreachable on first load
      });
  }, []);

  const handleFileUpload = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!resumeFile) return;

    setLoading(true);
    setError(null);
    setSuccess(null);

    const formData = new FormData();
    formData.append('resume_pdf', resumeFile);

    try {
      const res = await fetch(`${API_BASE}/resume/upload`, {
        method: 'POST',
        headers: getHeaders(),
        body: formData,
      });

      if (!res.ok) {
        const errorData = await res.json();
        throw new Error(errorData.error || "Failed to upload resume");
      }

      const data = await res.json();
      setSuccess("Base resume uploaded and structured!");
      setResumeData(data.data.content);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const tailorResume = async () => {
    setLoading(true);
    setError(null);
    setResult(null);

    const processTailor = async (jd: string, title?: string, company?: string) => {
      try {
        const res = await fetch(`${API_BASE}/resume/tailor`, {
          method: "POST",
          headers: {
            ...getHeaders(),
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            job_title: title || "Manual Job",
            company: company || "Manual Company",
            job_description: jd
          })
        });

        if (!res.ok) {
          const errData = await res.json();
          throw new Error(errData.error || "Tailoring failed");
        }

        const data = await res.json();
        setResult(data);
      } catch (err: any) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    if (manualMode) {
      if (!manualJD.trim()) {
        setError("Please enter a job description.");
        setLoading(false);
        return;
      }
      await processTailor(manualJD);
    } else {
      // @ts-ignore
      chrome.tabs.query({ active: true, currentWindow: true }, function (tabs: any) {
        if (!tabs[0]?.id) {
          setError("No active tab found.");
          setLoading(false);
          return;
        }

        // @ts-ignore
        chrome.tabs.sendMessage(tabs[0].id, { action: "EXTRACT_JOB_DETAILS" }, async function (response: any) {
          if (!response || !response.success || !response.data?.job_description) {
            setError("Could not auto-extract. Try manual paste mode.");
            setLoading(false);
            return;
          }
          await processTailor(response.data.job_description, response.data.job_title, response.data.company);
        });
      });
    }
  };

  return (
    <div className="popup-container">
      <header className="header">
        <h1>Quick Resume Tailor</h1>
      </header>

      <div className="card settings-card">
        <div className="card-header">
          <h3>AI Configuration (BYOK)</h3>
          <button className="text-btn" onClick={() => setShowSettings(!showSettings)}>
            {showSettings ? 'Hide' : 'Setup'}
          </button>
        </div>

        {showSettings && (
          <div className="settings-fields fade-in">
            <div className="input-group">
              <label>API Key</label>
              <div className="password-input-wrapper">
                <input 
                  type={showApiKey ? "text" : "password"} 
                  placeholder="Enter AI API Key" 
                  value={tempApiKey}
                  onChange={(e) => setTempApiKey(e.target.value)}
                />
                <button 
                  type="button" 
                  className="eye-toggle" 
                  onClick={() => setShowApiKey(!showApiKey)}
                  title={showApiKey ? "Hide API Key" : "Show API Key"}
                >
                  {showApiKey ? '👁️' : '👁️‍🗨️'}
                </button>
              </div>
            </div>
            <div className="input-group">
              <label>API URL (Full Endpoint)</label>
              <input 
                type="text" 
                placeholder="e.g. https://api.openai.com/v1/chat/completions" 
                value={tempBaseUrl}
                onChange={(e) => setTempBaseUrl(e.target.value)}
              />
            </div>
            <div className="input-group">
              <label>Model Name</label>
              <input 
                type="text" 
                placeholder="Enter AI Model Name (required)" 
                value={tempModel}
                onChange={(e) => setTempModel(e.target.value)}
              />
            </div>
            <button className="btn primary small" onClick={saveSettings} style={{ marginTop: '8px' }}>
              Save Configuration
            </button>
            <p className="hint">Supports any AI provider with an OpenAI-compatible API structure.</p>
          </div>
        )}
      </div>

      <div className="card">
        <h3>1. Setup Base Resume</h3>
        <form onSubmit={handleFileUpload} className="upload-section">
          <input
            type="file"
            accept=".pdf"
            onChange={(e) => setResumeFile(e.target.files?.[0] || null)}
            className="file-input"
          />
          <button
            type="submit"
            disabled={loading || !resumeFile}
            className="btn secondary"
            style={{ width: '100%' }}
          >
            {loading ? 'Processing...' : 'Upload PDF'}
          </button>
        </form>

        {resumeData && (
          <div className="resume-preview fade-in">
            <div className="preview-header">
              <span className="badge">Active Resume</span>
              <span className="name">{resumeData.personal_details?.name}</span>
            </div>
            <div className="preview-details">
              <span>{resumeData.personal_details?.email}</span>
              {resumeData.skills && (
                <div className="preview-skills">
                  {resumeData.skills.slice(0, 5).map((s: string, i: number) => (
                    <span key={i} className="skill-tag">{s}</span>
                  ))}
                  {resumeData.skills.length > 5 && <span className="skill-tag">+{resumeData.skills.length - 5} more</span>}
                </div>
              )}
            </div>
          </div>
        )}

        {success && <p className="success-msg">{success}</p>}
      </div>

      <div className="card">
        <div className="card-header">
          <h3>2. Tailor for Job</h3>
          <button className="text-btn" onClick={() => setManualMode(!manualMode)}>
            {manualMode ? 'Switch to Auto' : 'Paste Manually'}
          </button>
        </div>

        {manualMode && (
          <textarea
            className="jd-textarea"
            placeholder="Paste the job description here..."
            value={manualJD}
            onChange={(e) => setManualJD(e.target.value)}
          />
        )}

        <button className="btn primary" onClick={tailorResume} disabled={loading}>
          {loading && !success ? 'Working...' : '✨ Tailor Resume'}
        </button>
      </div>

      {error && <div className="error-box">{error}</div>}

      {result && (
        <div className="result-card fade-in">
          <div className="score-badge">
            <span className="label">ATS Score</span>
            <span className="value">{result.ats_score}%</span>
          </div>

          <div style={{ marginTop: '15px', marginBottom: '8px' }}>
            <label style={{ display: 'block', fontSize: '12px', marginBottom: '4px', color: '#64748b' }}>Select Template:</label>
            <select
              value={selectedTemplate}
              onChange={(e) => setSelectedTemplate(e.target.value)}
              className="template-select"
              style={{ width: '100%', padding: '8px', borderRadius: '6px', border: '1px solid #cbd5e1' }}
            >
              <option value="classic">Classic (Overleaf Standard)</option>
              <option value="modern">Modern (Sleek & Clean)</option>
              <option value="compact">Compact (Ultra Dense)</option>
              <option value="creative">Creative (Bold & Stylish)</option>
            </select>
          </div>

          <div style={{ marginBottom: '15px' }}>
            <label style={{ display: 'block', fontSize: '12px', marginBottom: '4px', color: '#64748b' }}>Your Name (shown in PDF header & file name):</label>
            <input
              type="text"
              placeholder="e.g. John Smith"
              value={downloadName}
              onChange={(e) => setDownloadName(e.target.value)}
              style={{ width: '100%', padding: '8px', borderRadius: '6px', border: '1px solid #cbd5e1', boxSizing: 'border-box', fontSize: '13px' }}
            />
          </div>

          <a
            href={`${API_BASE}/resume/download/${result.generated_id}?template=${selectedTemplate}${downloadName ? `&filename=${encodeURIComponent(downloadName)}&custom_name=${encodeURIComponent(downloadName)}` : ''}`}
            target="_blank"
            rel="noreferrer"
            className="btn download"
          >
            📥 Download PDF
          </a>
        </div>
      )}
    </div>
  )
}

export default App
