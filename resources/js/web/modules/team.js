(function () {

  const classes = {
    hidden: '!hidden',
    active: 'is-active',
  };

  const selectors = {
    content: '[data-hidden-former]',
    btn: '[data-btn-toggle-former]',
  };

  const init = () => {
    const btn = document.querySelector(selectors.btn);
    if (!btn) return;
    btn.addEventListener('click', toggle);
  };

  const toggle = () => {
    const btn = document.querySelector(selectors.btn);
    const content = document.querySelector(selectors.content);

    // if content has class !hidden, remove the class and add active class to the button
    console.log(content.classList.contains(classes.hidden));
    if (content.classList.contains(classes.hidden)) {
      content.classList.remove(classes.hidden);
      btn.classList.add(classes.active);
      return;
    }
    else {
      content.classList.add(classes.hidden);
      btn.classList.remove(classes.active);
    }
  };

  init();
  
})();