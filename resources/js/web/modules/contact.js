(function () {

  const classes = {
    hidden: 'hidden',
    block: 'block',
  };

  const selectors = {
    content: '[data-hidden]',
    btn: '[data-btn-toggle]',
  };

  const init = () => {
    const btns = document.querySelectorAll(selectors.btn);
    if (!btns.length) return;
    btns.forEach((btn) => {
      btn.addEventListener('click', toggle);
    });
  };

  const toggle = function(btn){

    // if the content is already visible, hide it
    if (!btn.target.nextElementSibling.classList.contains(classes.hidden)) {
      btn.target.nextElementSibling.classList.add(classes.hidden);
      return;
    }

    // hide all data-hidden
    const contents = document.querySelectorAll(selectors.content);
    contents.forEach((content) => {
      content.classList.add(classes.hidden);
    });

    // show the one next to the button if it's hidden
    const content = btn.target.nextElementSibling;
    content.classList.remove(classes.hidden);
  };

  init();
  
})();