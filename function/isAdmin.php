<?php  
  function isAdmin() {
    if (isset($_SESSION['permission']) && $_SESSION['permission'] === 'admin') return true;
    return null;
  }
  return isAdmin();
?>