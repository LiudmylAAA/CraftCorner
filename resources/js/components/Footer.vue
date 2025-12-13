<template>
  <footer class="footer">
    <p>© 2025 CraftCorner. Всі права захищені.</p>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter'
};
</script>

<style scoped>
.footer {
  background: #cce0ff; 
  color: #1e3a8a;
  padding: 25px 20px;
  text-align: center;
  margin-top: 50px;
}
.footer-nav {
  margin-bottom: 15px;
}
.footer-link {
  margin: 0 12px;
  color: #1e3a8a;
  text-decoration: none;
  font-weight: 500;
}
.footer-link:hover {
  text-decoration: underline;
}
</style>
