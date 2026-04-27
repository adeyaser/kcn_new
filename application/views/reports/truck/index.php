<div class="row">
    <div class="col-xl-4 col-md-6 mx-auto">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-truck me-2 text-primary"></i>Truck Activity Report</h6>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-4">Generate a summary of all truck movements through the gate for a specific date range.</p>
                <form action="<?= site_url('reports/trt/print_report') ?>" target="_blank" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-01') ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="fas fa-file-pdf me-2"></i>Generate Truck Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
