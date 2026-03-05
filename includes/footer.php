<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$req = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($req, PHP_URL_PATH) ?? '';
$self = $_SERVER['PHP_SELF'] ?? '';
$isAdminRoute = (strpos($path, '/admin') === 0) || (strpos($self, '/admin') === 0);
$isLoginPage = (substr($path, -strlen('/admin/login.php')) === '/admin/login.php') || (substr($self, -strlen('/admin/login.php')) === '/admin/login.php');
if ($isAdminRoute && !$isLoginPage && !empty($_SESSION['admin_logged_in'])) {
    echo '</body></html>'; return;
}
require_once __DIR__ . '/visitors.php';
$stats = getVisitorStats();
?>
<footer class="site-footer">
  <div class="container footer-min">
    <div class="ft-left">
      <span class="ft-small"><?php echo htmlspecialchars($data['nama'] ?? ''); ?></span>
    </div>
    <div class="ft-center">
      <?php
        $ig = $data['kontak']['instagram'] ?? '';
        $igUrl = '#';
        if (is_string($ig) && $ig !== '') {
          $igUrl = (strpos($ig, 'http') === 0) ? $ig : ('https://instagram.com/' . ltrim($ig, '@/'));
        }
      ?>
      <a class="ft-cta" href="<?php echo htmlspecialchars($igUrl); ?>" target="_blank" rel="noopener">Instagram</a>
    </div>
    <div class="ft-right">
      <a href="#" class="to-top">Scroll to Top ↑</a>
    </div>
  </div>
</footer>
<script>
(function(){
  var header = document.querySelector('.site-header.overlay');
  var toTop = document.querySelector('.to-top');
  function onScroll(){
    if (header) {
      if (window.scrollY > 20) { header.classList.add('scrolled'); }
      else { header.classList.remove('scrolled'); }
    }
  }
  window.addEventListener('scroll', onScroll);
  onScroll();
  if (toTop) {
    toTop.addEventListener('click', function(e){
      e.preventDefault();
      window.scrollTo({top:0, behavior:'smooth'});
    });
  }
  var wraps = document.querySelectorAll('.ev-wrap');
  wraps.forEach(function(w){
    var list = w.querySelector('.ev-list');
    var prev = w.querySelector('.ev-prev');
    var next = w.querySelector('.ev-next');
    function amt(){ return list ? list.clientWidth : 300; }
    if (list) { list.style.scrollBehavior = 'smooth'; }
    if (prev) prev.addEventListener('click', function(){ if (list) list.scrollBy({left:-amt(), behavior:'smooth'}); });
    if (next) next.addEventListener('click', function(){ if (list) list.scrollBy({left:amt(), behavior:'smooth'}); });
  });
})();
</script>
</body>
</html>
