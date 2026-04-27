<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentation extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Skip permission check so all logged in users can read the manual
        // Alternatively, you can enforce check_permission('documentation', 'can_view')
    }

    public function index() {
        $this->data['page_title'] = 'SOP & Panduan Aplikasi';
        $this->data['extra_css'] = [
            '<style>
                .doc-section-title {
                    color: var(--primary);
                    font-weight: 800;
                    text-transform: uppercase;
                    font-size: 16px;
                    letter-spacing: 0.8px;
                    border-bottom: 2px solid var(--border);
                    padding-bottom: 12px;
                    margin-top: 40px;
                    margin-bottom: 25px;
                }
                .nav-pills-custom .nav-link {
                    color: var(--text-muted);
                    background: #f8fafc;
                    border: 1px solid var(--border-light);
                    border-radius: var(--radius);
                    margin-bottom: 10px;
                    font-weight: 600;
                    padding: 12px 16px;
                    transition: all 0.3s ease;
                }
                .nav-pills-custom .nav-link:hover {
                    color: var(--primary);
                    background: #e0f2fe;
                    border-color: #bae6fd;
                    transform: translateX(3px);
                }
                .nav-pills-custom .nav-link.active {
                    color: #fff;
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: 0 4px 10px -2px rgba(59, 130, 246, 0.4);
                }
                .doc-card {
                    background: #fff;
                    border-radius: var(--radius-md);
                    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
                    border: 1px solid #f1f5f9;
                    padding: 40px 50px;
                    line-height: 1.8;
                    color: #334155;
                    font-size: 1.05rem;
                }
                .doc-card h3 { color: var(--primary); font-size: 1.8rem; font-weight: 800; margin-bottom: 1rem; }
                .doc-card h4 { color: var(--accent); font-size: 1.25rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 1rem; }
                .doc-card p { margin-bottom: 1.2rem; text-align: justify; }
                .doc-card ul, .doc-card ol { padding-left: 1.5rem; margin-bottom: 2rem; }
                .doc-card li { margin-bottom: 0.8rem; }
                .doc-card li strong { color: #0f172a; }
                .doc-card hr { border-color: #e2e8f0; margin: 3rem 0; }
                .doc-highlight {
                    background-color: #f0fdf4;
                    border-left: 5px solid #22c55e;
                    padding: 20px;
                    border-radius: 0 var(--radius) var(--radius) 0;
                    margin-bottom: 2rem;
                    color: #166534;
                    font-size: 1rem;
                }
            </style>'
        ];
        
        $this->render('documentation/index');
    }
}
