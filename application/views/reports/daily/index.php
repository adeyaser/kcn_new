<div class="row">
    <div class="col-xl-4 col-md-6 mx-auto">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-calendar-day me-2 text-primary"></i>Daily Operations Report</h6>
            </div>
            <div class="card-body p-4 text-center">
                <p class="text-muted small mb-4">Select a date to generate the terminal activity summary report.</p>
                <form action="<?= site_url('reports/daily/print_report') ?>" target="_blank" method="GET">
                    <div class="mb-4">
                        <label class="form-label">Report Date</label>
                        <input type="date" name="date" class="form-control form-control-lg text-center" value="<?= date('Y-m-d') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="fas fa-file-pdf me-2"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
