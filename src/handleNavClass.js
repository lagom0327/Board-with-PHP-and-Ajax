const dealNavActiveItem = () => {
  const path = location.pathname.replace('/board/', '');
  const navs = $('.nav-link');
  console.log('navs', navs)
  $(navs).each((i, nav) => {
    console.log('href', $(nav).attr('href').replace('./', ''))
    if ($(nav).attr('href').replace('./', '') === path) $(nav).addClass('active')
    console.log('nav', $(nav).attr('href').replace('./', '') === path)
  })
  console.log(path);
}

dealNavActiveItem();