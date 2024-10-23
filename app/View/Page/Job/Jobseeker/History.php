<div class="history-container">
    <h1>My Application History</h1>
    <?php if (empty($lamaranList)): ?>
        <div class="empty-state">
            <i data-lucide="briefcase" class="empty-icon"></i>
            <p>No Application yet.</p>
        </div>
    <?php else: ?>
        <ul class="history-list">
            <?php foreach ($lamaranList as $lamaran): ?>
                <li class="history-item">
                    <a href="/job/<?= $lamaran['lowongan_id'] ?>" class="history-link">
                        <div class="history-content">
                            <div class="application-info">
                                <h2><?= htmlspecialchars($lamaran['posisi']) ?></h2>
                                <div class="meta">
                                    <div class="date">
                                        <i data-lucide="calendar" class="meta-icon"></i>
                                        <?= htmlspecialchars(date('d M Y', strtotime($lamaran['created_at']))) ?>
                                    </div>
                                    <div class="time">
                                        <i data-lucide="clock" class="meta-icon"></i>
                                        <?= htmlspecialchars(date('H:i', strtotime($lamaran['created_at']))) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="status-container">
                                <div class="status status-<?= $lamaran['status']?>">
                                    <span class="status-dot"></span>
                                    <span class="status-text"><?= htmlspecialchars($lamaran['status']) ?></span>
                                </div>
                                <div class="action-arrow">
                                    <i data-lucide="chevron-right" class="arrow-icon"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>