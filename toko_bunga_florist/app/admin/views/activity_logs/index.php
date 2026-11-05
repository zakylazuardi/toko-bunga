<?php $title='Activity Log'; require __DIR__.'/../layouts/header.php'; require __DIR__.'/../layouts/sidebar.php'; ?>
<main class="main">
    <div class="topbar">
        <div><h1>Activity Log</h1><p>Riwayat tambah, edit, dan hapus data yang tercatat otomatis oleh sistem.</p></div>
        <a class="btn" href="<?=base_url('admin/dashboard')?>">Kembali ke Dashboard</a>
    </div>
    <section class="panel">
        <div class="panel-head"><h2>Riwayat Aktivitas</h2><span class="badge gray"><?=count($logs)?> aktivitas</span></div>
        <div class="filter-bar">
            <?php foreach (['' => 'Semua', 'tambah' => 'Tambah', 'edit' => 'Edit', 'hapus' => 'Hapus', 'akses' => 'Login/Logout'] as $k => $label): ?>
                <a class="chip <?=($type ?? '') === $k ? 'active' : ''?>" href="<?=base_url('admin/activity-logs' . ($k ? '?jenis=' . $k : ''))?>"><?=e($label)?></a>
            <?php endforeach; ?>
        </div>
        <div class="table-wrap"><table>
            <thead><tr><th>Waktu</th><th>Admin</th><th>Aktivitas</th><th>Deskripsi</th></tr></thead>
            <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?=e(log_time($log['created_at']))?></td>
                    <td><?=e($log['admin_name'] ?? 'Sistem / langsung dari database')?></td>
                    <td><span class="badge <?=log_badge($log['activity'])?>"><?=e($log['activity'])?></span></td>
                    <td><?=e($log['description'] ?? '-')?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$logs): ?><tr><td colspan="4" class="empty">Belum ada aktivitas yang tercatat.</td></tr><?php endif; ?>
            </tbody>
        </table></div>
    </section>
</main>
<?php require __DIR__.'/../layouts/footer.php'; ?>
