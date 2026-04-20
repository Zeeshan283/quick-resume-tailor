<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Quick Resume Tailor — AI-Powered Resume Services</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        // Etheric Slate Color Scheme
                        "primary": "#38BDF8",
                        "on-primary": "#0F172A",
                        "primary-container": "#0284c7",
                        "on-primary-container": "#bae6fd",
                        "secondary": "#94A3B8",
                        "on-secondary": "#0F172A",
                        "secondary-container": "#334155",
                        "on-secondary-container": "#f1f5f9",
                        "tertiary": "#FDE047",
                        "on-tertiary": "#0F172A",
                        "tertiary-container": "#ca8a04",
                        "on-tertiary-container": "#fef08a",
                        // Neutral & Surface mappings to #0F172A (Slate)
                        "background": "#0F172A",
                        "on-background": "#f8fafc",
                        "surface": "#0F172A",
                        "on-surface": "#f8fafc",
                        "surface-variant": "#1e293b",
                        "on-surface-variant": "#cbd5e1",
                        "outline": "#64748b",
                        "outline-variant": "#334155",
                        "surface-container-lowest": "#020617",
                        "surface-container-low": "#0F172A",
                        "surface-container": "#1e293b",
                        "surface-container-high": "#334155",
                        "surface-container-highest": "#475569",
                        "surface-tint": "#38BDF8",
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Manrope", "sans-serif"],
                        "label": ["Manrope", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0F172A;
            color: #f8fafc;
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(148, 163, 184, 0.15);
        }

        .glass-input {
            background: #020617;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.15);
        }

        .glass-input:focus {
            border-color: rgba(56, 189, 248, 0.5);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #38BDF8, #0ea5e9);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 10px 30px -5px rgba(56, 189, 248, 0.2);
        }

        .ambient-shadow {
            box-shadow: 0 30px 60px -10px rgba(56, 189, 248, 0.05);
        }

        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body
    class="bg-[#0F172A] text-white font-body antialiased flex flex-col min-h-screen pt-32 pb-24 md:pb-0 overflow-x-hidden selection:bg-primary/30 selection:text-white">
    <!-- TopAppBar -->
    <header
        class="fixed top-0 w-full z-50 bg-[#0F172A]/70 backdrop-blur-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.36)] border-b border-outline-variant/30">
        <div class="flex justify-between items-center px-8 py-6 w-full max-w-7xl mx-auto">
            <a class="text-primary hover:text-white transition-all duration-300 flex items-center justify-center p-2 rounded-full"
                href="#">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">blur_on</span>
            </a>
            <h1 class="font-headline font-black tracking-tighter text-2xl text-white">QUICK RESUME</h1>
            <a class="text-primary hover:text-white transition-all duration-300 flex items-center justify-center p-2 rounded-full"
                href="#contact">
                <span class="material-symbols-outlined">menu</span>
            </a>
        </div>
    </header>
    <main class="flex-grow flex flex-col gap-32">
        <!-- Hero Section -->
        <section
            class="relative w-full max-w-7xl mx-auto px-8 pt-16 flex flex-col items-center justify-center min-h-[530px] text-center">
            <!-- Decorative abstract element -->
            <div aria-hidden="true"
                class="absolute inset-0 pointer-events-none overflow-hidden flex items-center justify-center opacity-30 mix-blend-screen z-[-1]">
                <div
                    class="w-[800px] h-[800px] rounded-full bg-gradient-to-tr from-primary/20 to-tertiary/10 blur-[100px] transform -translate-y-1/4">
                </div>
            </div>
            <span class="font-label text-[12px] uppercase tracking-[0.2em] text-tertiary mb-6 font-semibold">Your
                Career. Our Craft.</span>
            <h2
                class="font-headline text-5xl md:text-7xl font-black text-white tracking-tighter leading-[1.1] mb-8 max-w-4xl drop-shadow-2xl">
                Get Hired <br /><span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Faster.</span>
            </h2>
            <p class="font-body text-lg text-on-surface-variant max-w-2xl leading-relaxed mb-12">
                Most resumes never get seen by a human. We fix that. We tailor your resume with precision AI so it
                passes every filter and lands in the hands of the people who matter.
                We help you create professional, optimized resumes that pass automated screening systems and capture the
                attention of hiring managers.
            </p>
        </section>
        <!-- Services Section -->
        <section class="w-full max-w-7xl mx-auto px-8 relative z-10" id="services">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div>
                    <h3 class="font-headline text-3xl font-bold text-white mb-2">What We Do</h3>
                    <p class="font-body text-secondary">Simple, affordable services that get real results.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service Card 1 -->
                <div
                    class="glass-panel rounded-xl p-8 flex flex-col gap-6 ambient-shadow group hover:scale-[1.02] transition-transform duration-500">
                    <div
                        class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center border border-primary/30 text-primary group-hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-3xl">description</span>
                    </div>
                    <div>
                        <h4 class="font-headline text-xl font-bold text-white mb-3">Resume Rewrite</h4>
                        <p class="font-body text-sm text-on-surface-variant leading-relaxed">
                            We don't just edit — we rewrite. Your experience, your story, told in a way that makes
                            recruiters stop scrolling and start reading.
                        </p>
                    </div>
                </div>
                <!-- Service Card 2 -->
                <div
                    class="glass-panel rounded-xl p-8 flex flex-col gap-6 ambient-shadow group hover:scale-[1.02] transition-transform duration-500">
                    <div
                        class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center border border-primary/30 text-primary group-hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-3xl">verified</span>
                    </div>
                    <div>
                        <h4 class="font-headline text-xl font-bold text-white mb-3">ATS Optimization</h4>
                        <p class="font-body text-sm text-on-surface-variant leading-relaxed">
                            90% of resumes are rejected by software before a human reads them. We make sure yours isn't
                            one of them, every single time.
                        </p>
                    </div>
                </div>
                <!-- Service Card 3 -->
                <div
                    class="glass-panel rounded-xl p-8 flex flex-col gap-6 ambient-shadow group hover:scale-[1.02] transition-transform duration-500">
                    <div
                        class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center border border-tertiary/30 text-tertiary group-hover:bg-tertiary/20 transition-colors">
                        <span class="material-symbols-outlined text-3xl">support_agent</span>
                    </div>
                    <div>
                        <h4 class="font-headline text-xl font-bold text-white mb-3">1-on-1 Career Support</h4>
                        <p class="font-body text-sm text-on-surface-variant leading-relaxed">
                            Stuck on something? Not sure where to start? We'll talk through your situation and map out
                            exactly what to do next.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact Section -->
        <section class="w-full max-w-7xl mx-auto px-8 relative z-10" id="contact">
            <div class="glass-panel rounded-xl p-8 md:p-16 flex flex-col lg:flex-row gap-16 ambient-shadow">
                <div class="lg:w-1/2 flex flex-col justify-center">
                    <span class="font-label text-[11px] uppercase tracking-widest text-tertiary mb-4">Let's Talk</span>
                    <h3 class="font-headline text-4xl font-bold text-white mb-6">Ready to<br />Get Started?</h3>
                    <p class="font-body text-on-surface-variant mb-12 max-w-md">
                        Whether you need a full resume overhaul or just want quick advice, drop us a message. We reply
                        fast, and the first consultation is always free.
                    </p>
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-4 text-on-surface">
                            <span class="material-symbols-outlined text-primary">schedule</span>
                            <span class="font-label text-sm uppercase tracking-wider text-on-surface-variant">Response
                                within 24 hours</span>
                        </div>
                    </div>
                </div>
                <div
                    class="lg:w-1/2 bg-[#020617]/50 rounded-lg p-8 border border-outline-variant/30 relative overflow-hidden">
                    <!-- Subtle glow behind form -->
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl pointer-events-none">
                    </div>
                    <form id="contact-form" class="flex flex-col gap-6 relative z-10">
                        @csrf
                        <div class="flex flex-col gap-2">
                            <label class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant ml-2" for="name">Your Name</label>
                            <input class="glass-input rounded-lg w-full px-4 py-3 text-white bg-[#020617] font-body text-sm transition-all placeholder:text-outline/50 focus:text-white" id="name" name="name" placeholder="John Smith" type="text" required />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant ml-2" for="email">Email Address</label>
                            <input class="glass-input rounded-lg w-full px-4 py-3 text-white bg-[#020617] font-body text-sm transition-all placeholder:text-outline/50 focus:text-white" id="email" name="email" placeholder="you@email.com" type="email" required />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant ml-2" for="message">How Can We Help?</label>
                            <textarea class="glass-input rounded-lg w-full px-4 py-3 text-white bg-[#020617] font-body text-sm transition-all resize-none placeholder:text-outline/50 focus:text-white" id="message" name="message" placeholder="Tell us about your situation — role you're targeting, what's not working, or anything else..." rows="4" required></textarea>
                        </div>
                        
                        <div id="form-status" class="hidden text-sm font-label uppercase tracking-widest px-4 py-2 rounded-lg"></div>

                        <button type="submit" id="submit-btn" class="btn-primary text-slate-900 rounded-xl py-4 px-8 mt-4 font-headline font-bold tracking-wide flex items-center justify-center gap-2 hover:brightness-110 transition-all active:scale-[0.98]">
                            <span id="btn-text">Send Message</span>
                            <span id="btn-icon" class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <!-- BottomNavBar (Mobile Only) -->
    <nav
        class="md:hidden fixed bottom-6 left-0 right-0 z-50 flex justify-around items-center w-[90%] max-w-md mx-auto rounded-full px-6 py-3 bg-[#1e293b]/80 backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-outline-variant/30">
        <a class="flex flex-col items-center justify-center text-secondary hover:text-white transition-all duration-300"
            href="#">
            <span class="material-symbols-outlined">home</span>
            <span class="font-manrope uppercase tracking-widest text-[9px] font-bold mt-1">Home</span>
        </a>
        <a class="flex flex-col items-center justify-center text-primary scale-110 hover:text-white transition-all duration-300"
            href="#services">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">layers</span>
            <span class="font-manrope uppercase tracking-widest text-[9px] font-bold mt-1">Services</span>
        </a>
        <a class="flex flex-col items-center justify-center text-secondary hover:text-white transition-all duration-300"
            href="#contact">
            <span class="material-symbols-outlined">mail</span>
            <span class="font-manrope uppercase tracking-widest text-[9px] font-bold mt-1">Contact</span>
        </a>
        <a class="flex flex-col items-center justify-center text-secondary hover:text-white transition-all duration-300"
            href="#">
            <span class="material-symbols-outlined">more_horiz</span>
            <span class="font-manrope uppercase tracking-widest text-[9px] font-bold mt-1">More</span>
        </a>
    </nav>
    <!-- Footer -->
    <footer class="w-full py-16 px-8 mt-auto bg-[#020617] relative z-0 border-t border-outline-variant/20">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 w-full max-w-7xl mx-auto">
            <div class="font-headline font-bold text-white text-xl">
                QUICK RESUME TAILOR
            </div>
            <div class="flex gap-6">
                <a class="font-manrope text-[11px] tracking-widest uppercase text-secondary hover:text-primary transition-colors duration-300"
                    href="#">Privacy Policy</a>
                <a class="font-manrope text-[11px] tracking-widest uppercase text-secondary hover:text-primary transition-colors duration-300"
                    href="#">Terms of Service</a>
                <a class="font-manrope text-[11px] tracking-widest uppercase text-secondary hover:text-primary transition-colors duration-300"
                    href="#">LinkedIn</a>
            </div>
            <div class="font-manrope text-[11px] tracking-widest uppercase text-outline">
                © 2025 QUICK RESUME TAILOR. ALL RIGHTS RESERVED.
            </div>
        </div>
    </footer>
<script>
    document.getElementById('contact-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');
        const statusDiv = document.getElementById('form-status');
        
        // Reset state
        statusDiv.classList.add('hidden');
        statusDiv.className = 'hidden text-sm font-label uppercase tracking-widest px-4 py-2 rounded-lg';
        
        // Loading state
        btn.disabled = true;
        btnText.innerText = 'Transmitting...';
        btnIcon.innerText = 'sync';
        btnIcon.classList.add('animate-spin');
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch('/api/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            statusDiv.classList.remove('hidden');
            if (response.ok) {
                statusDiv.innerText = result.message;
                statusDiv.classList.add('bg-primary/20', 'text-primary');
                form.reset();
            } else {
                statusDiv.innerText = result.message || 'Validation failed. Check your inputs.';
                statusDiv.classList.add('bg-error/20', 'text-error');
            }
        } catch (error) {
            statusDiv.classList.remove('hidden');
            statusDiv.innerText = 'Network error. Please try again.';
            statusDiv.classList.add('bg-error/20', 'text-error');
        } finally {
            btn.disabled = false;
            btnText.innerText = 'Send Message';
            btnIcon.innerText = 'arrow_forward';
            btnIcon.classList.remove('animate-spin');
        }
    });
</script>
</body>

</html>