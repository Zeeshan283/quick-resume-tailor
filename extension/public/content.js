/**
 * This content script runs on LinkedIn job pages to extract the job description.
 */
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === "EXTRACT_JOB_DETAILS") {
        try {
            // Logic for extracting LinkedIn job title and company
            const titleEl = document.querySelector('.job-details-jobs-unified-top-card__job-title');
            const companyEl = document.querySelector('.job-details-jobs-unified-top-card__company-name');
            // Logic for job description
            const descEl = document.querySelector('#job-details') || document.querySelector('.jobs-description');
            
            sendResponse({
                success: true,
                data: {
                    job_title: titleEl ? titleEl.innerText.trim() : null,
                    company: companyEl ? companyEl.innerText.trim() : null,
                    job_description: descEl ? descEl.innerText.trim() : null
                }
            });
        } catch (e) {
            sendResponse({ success: false, error: e.toString() });
        }
    }
    return true;
});
