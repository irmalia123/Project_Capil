<?php
/**
 * Fungsi-fungsi untuk mengelola notifikasi
 */

/**
 * Membuat notifikasi baru
 */
function create_notification($judul, $pesan, $untuk_admin = 0) {
    global $conn;
    
    try {
        $sql = "INSERT INTO notifikasi (judul, pesan, untuk_admin) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $judul, $pesan, $untuk_admin);
        
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error create_notification: " . $e->getMessage());
        return false;
    }
}

/**
 * Mendapatkan notifikasi berdasarkan tipe
 */
function get_notifications($untuk_admin = 0, $limit = 50) {
    global $conn;
    
    try {
        $sql = "SELECT * FROM notifikasi 
                WHERE untuk_admin = ? 
                ORDER BY created_at DESC 
                LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $untuk_admin, $limit);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $notifications = [];
        
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        
        return $notifications;
    } catch (Exception $e) {
        error_log("Error get_notifications: " . $e->getMessage());
        return [];
    }
}

/**
 * Mendapatkan statistik notifikasi
 */
function get_notification_stats($untuk_admin = 0) {
    global $conn;
    
    $stats = [
        'total' => 0,
        'unread' => 0,
        'today' => 0
    ];
    
    try {
        // Total notifikasi
        $sql_total = "SELECT COUNT(*) as total FROM notifikasi WHERE untuk_admin = ?";
        $stmt = $conn->prepare($sql_total);
        $stmt->bind_param("i", $untuk_admin);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Notifikasi belum dibaca
        $sql_unread = "SELECT COUNT(*) as unread FROM notifikasi 
                      WHERE untuk_admin = ? AND dibaca = 0";
        $stmt = $conn->prepare($sql_unread);
        $stmt->bind_param("i", $untuk_admin);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['unread'] = $result->fetch_assoc()['unread'];
        
        // Notifikasi hari ini
        $sql_today = "SELECT COUNT(*) as today FROM notifikasi 
                     WHERE untuk_admin = ? AND DATE(created_at) = CURDATE()";
        $stmt = $conn->prepare($sql_today);
        $stmt->bind_param("i", $untuk_admin);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['today'] = $result->fetch_assoc()['today'];
        
    } catch (Exception $e) {
        error_log("Error get_notification_stats: " . $e->getMessage());
    }
    
    return $stats;
}

/**
 * Menandai notifikasi sebagai dibaca
 */
function mark_as_read($id) {
    global $conn;
    
    try {
        $sql = "UPDATE notifikasi SET dibaca = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error mark_as_read: " . $e->getMessage());
        return false;
    }
}

/**
 * Menandai semua notifikasi sebagai dibaca
 */
function mark_all_as_read($untuk_admin = 0) {
    global $conn;
    
    try {
        $sql = "UPDATE notifikasi SET dibaca = 1 
                WHERE untuk_admin = ? AND dibaca = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $untuk_admin);
        
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error mark_all_as_read: " . $e->getMessage());
        return false;
    }
}

/**
 * Hapus notifikasi
 */
function delete_notification($id) {
    global $conn;
    
    try {
        $sql = "DELETE FROM notifikasi WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error delete_notification: " . $e->getMessage());
        return false;
    }
}

/**
 * Fungsi helper untuk notifikasi pengajuan baru
 */
function notify_pengajuan_baru($jenis_pengajuan, $nama_pengaju) {
    $judul = "Pengajuan Baru: " . $jenis_pengajuan;
    $pesan = "User " . $nama_pengaju . " mengajukan " . $jenis_pengajuan . ". Silakan verifikasi pengajuan.";
    
    return create_notification($judul, $pesan, 1); // 1 = untuk admin
}

/**
 * Fungsi helper untuk notifikasi status pengajuan
 */
function notify_status_pengajuan($jenis_pengajuan, $status, $catatan = "") {
    if ($status == 'disetujui') {
        $judul = "Pengajuan Disetujui";
        $pesan = "Pengajuan " . $jenis_pengajuan . " Anda telah disetujui.";
    } else {
        $judul = "Pengajuan Ditolak";
        $pesan = "Pengajuan " . $jenis_pengajuan . " Anda ditolak.";
    }
    
    if (!empty($catatan)) {
        $pesan .= " Catatan: " . $catatan;
    }
    
    return create_notification($judul, $pesan, 0); // 0 = untuk user
}
?>