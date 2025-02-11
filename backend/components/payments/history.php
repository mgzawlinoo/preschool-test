<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Payment History</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" placeholder="Search payments...">
                <select class="form-select" style="width: 150px;">
                    <option value="">All Status</option>
                    <option>Paid</option>
                    <option>Pending</option>
                    <option>Overdue</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Student</th>
                        <th>Parent</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#INV-001</td>
                        <td>Emily Johnson</td>
                        <td>Sarah Johnson</td>
                        <td>$350.00</td>
                        <td>2024-02-15</td>
                        <td><span class="badge bg-success">Paid</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1"><i
                                    class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i
                                    class="bi bi-download"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-002</td>
                        <td>Michael Smith</td>
                        <td>John Smith</td>
                        <td>$350.00</td>
                        <td>2024-02-20</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1"><i
                                    class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i
                                    class="bi bi-download"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-003</td>
                        <td>Sophie Davis</td>
                        <td>Emma Davis</td>
                        <td>$350.00</td>
                        <td>2024-02-01</td>
                        <td><span class="badge bg-danger">Overdue</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1"><i
                                    class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i
                                    class="bi bi-download"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav class="mt-3">
            <ul class="pagination justify-content-end">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>