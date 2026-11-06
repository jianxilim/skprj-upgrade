<?php
// Navbar for teacher (guru) pages. Included right after <body>.
$_navCur = basename($_SERVER['SCRIPT_NAME']);
$_navMap = array(
    'main_guru.php'           => array('main_guru.php', 'kemaskini_aktiviti.php', 'sahkan_guru.php', 'tambah_guru.php'),
    'gurutambahaktiviti.php'  => array('gurutambahaktiviti.php'),
    'laporan1_guru.php'       => array('laporan1_guru.php', 'laporan2_guru.php'),
    'importdata_guru.php'     => array('importdata_guru.php'),
    'pilihtindakan2.php'      => array('pilihtindakan2.php', 'piliheditahli_guru.php', 'piliheditguru_guru.php', 'editahli_guru.php', 'editguru_guru.php'),
    'pilihtindakan_guru.php'  => array('pilihtindakan_guru.php', 'tambahahli_guru.php', 'tambahguru_guru.php'),
);
function _navActive($map, $cur, $key) {
    return (isset($map[$key]) && in_array($cur, $map[$key])) ? ' active' : '';
}
?>
<nav class="navbar">
  <img src="game.png" alt="Logo" class="logo" onerror="this.style.display='none'">
  <div class="icon-container">
    <a href="main_guru.php" class="icon-btn<?php echo _navActive($_navMap, $_navCur, 'main_guru.php'); ?>" title="Senarai Aktiviti"><i class="fas fa-home"></i><span class="nav-label">Aktiviti</span></a>
    <a href="gurutambahaktiviti.php" class="icon-btn<?php echo _navActive($_navMap, $_navCur, 'gurutambahaktiviti.php'); ?>" title="Tambah Aktiviti"><i class="fas fa-paint-brush"></i><span class="nav-label">Tambah Aktiviti</span></a>
    <a href="laporan1_guru.php" class="icon-btn<?php echo _navActive($_navMap, $_navCur, 'laporan1_guru.php'); ?>" title="Laporan"><i class="fas fa-book"></i><span class="nav-label">Laporan</span></a>
    <a href="importdata_guru.php" class="icon-btn<?php echo _navActive($_navMap, $_navCur, 'importdata_guru.php'); ?>" title="Import Data"><i class="fas fa-pen"></i><span class="nav-label">Import</span></a>
    <a href="pilihtindakan2.php" class="icon-btn<?php echo _navActive($_navMap, $_navCur, 'pilihtindakan2.php'); ?>" title="Urus Pengguna"><i class="fas fa-user"></i><span class="nav-label">Urus</span></a>
    <a href="pilihtindakan_guru.php" class="icon-btn<?php echo _navActive($_navMap, $_navCur, 'pilihtindakan_guru.php'); ?>" title="Tambah Pengguna"><i class="fas fa-plus"></i><span class="nav-label">Tambah Pengguna</span></a>
  </div>
  <a href="login_guru.php" class="icon-btn logout-btn" title="Log keluar"><i class="fas fa-sign-out-alt"></i><span class="nav-label">Keluar</span></a>
</nav>
