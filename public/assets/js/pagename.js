
  const pageTitle = document.getElementById('pagetitle');

  
  const path = window.location.pathname;
  const parts = path.split('/').filter(Boolean); 

  const routeMap = {
    'dashboard': 'Dashboard',
    'products': 'Products',
    'customers': 'Customers',
    'invoices': 'Invoices',
    'users': 'Users'
    'profile': 'Profile'
  };

  let title = 'Biovet Technology Ltd'; 

  if(parts[0] === 'admin') {
    const mainRoute = parts[1]; 
    const subRoute = parts[2] || '';

    if(mainRoute in routeMap) {
      title = `Biovet Technology Ltd | ${routeMap[mainRoute]}`;
      if(subRoute) {
        const subTitle = subRoute.charAt(0).toUpperCase() + subRoute.slice(1);
        title += ` | ${subTitle}`;
      }
    }
  }

  pageTitle.innerHTML = title;

