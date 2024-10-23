<div class="history-container">
    <h1>Riwayat Lamaran Saya</h1>
    <?php if (empty($lamaranList)): ?>
        <p>Belum ada lamaran.</p>
    <?php else: ?>
        <ul class="history-list">
            <?php foreach ($lamaranList as $lamaran): ?>
                <li class="history-item" onclick="viewLamaranDetails(<?= $lamaran['lowongan_id'] ?>)">
                    <div class="history-info">
                        <h2><?= htmlspecialchars($lamaran['posisi']) ?></h2>
                        <p>Status: <?= htmlspecialchars($lamaran['status']) ?></p>
                        <p>Tanggal: <?= htmlspecialchars($lamaran['created_at']) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
