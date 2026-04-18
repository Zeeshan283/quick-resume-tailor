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
  const [result, setResult] = useState(() => {
    const saved = localStorage.getItem('result');
    return saved ? JSON.parse(saved) : null;
  });

  const API_BASE = "http://localhost:8000/api";

  // Persist state to local storage when it changes
  useEffect(() => { localStorage.setItem('manualMode', String(manualMode)); }, [manualMode]);
  useEffect(() => { localStorage.setItem('manualJD', manualJD); }, [manualJD]);
  useEffect(() => { localStorage.setItem('selectedTemplate', selectedTemplate); }, [selectedTemplate]);
  useEffect(() => {
    if (result) localStorage.setItem('result', JSON.stringify(result));
    else localStorage.removeItem('result');
  }, [result]);

  useEffect(() => {
    // Check if the user already has a base resume on load
    fetch(`${API_BASE}/resume/status`)
      .then(res => res.json())
      .then(data => {
        if (data.has_resume) {
          setSuccess("Base resume is already uploaded and ready for tailoring.");
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
        body: formData,
      });

      if (!res.ok) {
        const errorData = await res.json();
        throw new Error(errorData.error || "Failed to upload resume");
      }
      
      setSuccess("Base resume uploaded and structured!");
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
            "Content-Type": "application/json",
            "Accept": "application/json",
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

      <div className="card">
        <h3>1. Setup Base Resume</h3>
        <form onSubmit={handleFileUpload} className="upload-section">
          <input 
            type="file" 
            accept=".pdf" 
            onChange={(e) => setResumeFile(e.target.files?.[0] || null)}
            className="file-input"
          />
          <button type="submit" className="btn secondary" disabled={!resumeFile || loading}>
            {loading && !result ? 'Uploading...' : 'Upload PDF'}
          </button>
        </form>
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

          <div style={{ marginTop: '15px', marginBottom: '15px' }}>
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

          <a href={`${API_BASE}/resume/download/${result.generated_id}?template=${selectedTemplate}`} target="_blank" rel="noreferrer" className="btn download">
            📥 Download PDF
          </a>
        </div>
      )}
    </div>
  )
}

export default App
