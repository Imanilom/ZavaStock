document.addEventListener('DOMContentLoaded', function() {
  // Toggle sidebar
  const sidebarToggle = document.querySelector('.sidebar-toggle');
  const sidebar = document.querySelector('.sidebar');
  const mainContent = document.querySelector('.main-content');
  
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('collapsed');
      
      // Save state to localStorage
      const isCollapsed = sidebar.classList.contains('collapsed');
      localStorage.setItem('sidebarCollapsed', isCollapsed);
      
      // Change icon
      const icon = this.querySelector('i');
      if (isCollapsed) {
        icon.classList.remove('fa-chevron-left');
        icon.classList.add('fa-chevron-right');
      } else {
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-left');
      }
    });
    
    // Load saved state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      sidebar.classList.add('collapsed');
      mainContent.classList.add('collapsed');
      const icon = sidebarToggle.querySelector('i');
      icon.classList.remove('fa-chevron-left');
      icon.classList.add('fa-chevron-right');
    }
  }
  
  // Mobile sidebar toggle
  const mobileSidebarToggle = document.querySelector('[data-bs-target="#sidebarCollapse"]');
  if (mobileSidebarToggle) {
    mobileSidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('show');
    });
  }
});