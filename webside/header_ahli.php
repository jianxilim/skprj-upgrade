<?php
// Navbar for member (ahli) pages. Included right after <body>.
$_navCur = basename($_SERVER['SCRIPT_NAME']);
function _navActiveAhli($cur, $pages) {
    return in_array($cur, $pages) ? ' active' : '';
}
?>
<nav class="navbar">
  <img src="game.png" alt="Logo" class="logo" onerror="this.style.display='none'">
  <div class="icon-container">
    <a href="main_ahli.php" class="icon-btn<?php echo _navActiveAhli($_navCur, array('main_ahli.php')); ?>" title="Aktiviti"><i class="fas fa-home"></i><span class="nav-label">Aktiviti</span></a>
    <a href="profile_ahli.php" class="icon-btn<?php echo _navActiveAhli($_navCur, array('profile_ahli.php')); ?>" title="Profil"><i class="fas fa-user"></i><span class="nav-label">Profil</span></a>
    <a href="laporan1_ahli.php" class="icon-btn<?php echo _navActiveAhli($_navCur, array('laporan1_ahli.php')); ?>" title="Laporan"><i class="fas fa-book"></i><span class="nav-label">Laporan</span></a>
  </div>
  <a href="login_ahli.php" class="icon-btn logout-btn" title="Log keluar"><i class="fas fa-sign-out-alt"></i><span class="nav-label">Keluar</span></a>
</nav>
