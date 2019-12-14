<?php
  function isSuperAdmin() {

    if (isset($_SESSION['permission']) && $_SESSION['permission'] === 'super admin') return true;
    return null;
  }
  return isSuperAdmin();
?>