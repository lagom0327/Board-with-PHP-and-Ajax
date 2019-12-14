const dealNavActiveItem = () => {
  const path = window.location.pathname.replace('/board/', '');
  const navs = $('.nav-link');
  $(navs).each((i, nav) => {
    if ($(nav).attr('href').replace('./', '') === path) $(nav).addClass('active');
  });
};

dealNavActiveItem();
