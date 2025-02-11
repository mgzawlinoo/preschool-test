<div class="container-fluid p-4">
    <h2 class="mb-4">Settings</h2>

    <!-- Settings Tabs -->
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#notifications">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#security">Security</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#billing">Billing</a>
                </li>
            </ul>

            <div class="tab-content mt-4">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general">
                    <form>
                        <div class="mb-4">
                            <h5>School Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">School Name</label>
                                    <input type="text" class="form-control" value="Little Stars Preschool">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" value="info@littlestars.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" value="+1 234-567-8900">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" value="123 Education St, City">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>System Preferences</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Time Zone</label>
                                    <select class="form-select">
                                        <option>UTC-8 (Pacific Time)</option>
                                        <option>UTC-5 (Eastern Time)</option>
                                        <option>UTC+0 (GMT)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date Format</label>
                                    <select class="form-select">
                                        <option>MM/DD/YYYY</option>
                                        <option>DD/MM/YYYY</option>
                                        <option>YYYY-MM-DD</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notifications">
                    <form>
                        <div class="mb-4">
                            <h5>Email Notifications</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    New student enrollments
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Payment reminders
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Staff updates
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>System Notifications</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Desktop notifications
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Sound alerts
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="security">
                    <form>
                        <div class="mb-4">
                            <h5>Change Password</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Two-Factor Authentication</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Enable two-factor authentication
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <!-- Billing Settings -->
                <div class="tab-pane fade" id="billing">
                    <form>
                        <div class="mb-4">
                            <h5>Payment Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Default Currency</label>
                                    <select class="form-select">
                                        <option>USD ($)</option>
                                        <option>EUR (€)</option>
                                        <option>GBP (£)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tax Rate (%)</label>
                                    <input type="number" class="form-control" value="10">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Payment Methods</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Accept credit cards
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Accept bank transfers
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">
                                    Accept cash payments
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>