<?php include 'layouts/header.php'; ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <?php include 'components/top-nav.php'; ?>

            <!-- Main Content -->
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Notifications</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                        <i class="bi bi-send"></i> Send Notification
                    </button>
                </div>

                <!-- Notification Filters -->
                <?php include 'components/notifications/filters.php'; ?>

                <!-- Notifications List -->
                <?php include 'components/notifications/list.php'; ?>
            </div>
        </div>
    </div>

    <!-- Send Notification Modal -->
    <div class="modal fade" id="sendNotificationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Notification Type</label>
                            <select class="form-select" required>
                                <option value="">Select Type</option>
                                <option>Announcement</option>
                                <option>Reminder</option>
                                <option>Alert</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Recipients</label>
                            <select class="form-select" required>
                                <option value="">Select Recipients</option>
                                <option>All Parents</option>
                                <option>All Teachers</option>
                                <option>All Staff</option>
                                <option>Specific Class</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendEmail">
                                <label class="form-check-label" for="sendEmail">
                                    Also send as email
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send Notification</button>
                </div>
            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>