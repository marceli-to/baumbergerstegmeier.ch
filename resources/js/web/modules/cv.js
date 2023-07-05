(function () {

  const selectors = {
    content: '[data-cv]',
    btn: '[data-btn-cv]',
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
      if (!btn.target.nextElementSibling.classList.contains('hidden')) {
        btn.target.nextElementSibling.classList.add('hidden');
        btn.target.classList.remove('is-active');
        return;
      }
  
      // hide all data-hidden
      const contents = document.querySelectorAll(selectors.content);
      contents.forEach((content) => {
        content.classList.add('hidden');
      });
  
      // show the one next to the button if it's hidden
      const content = btn.target.nextElementSibling;
      content.classList.remove('hidden');
  
      // add active class to the button
      btn.target.classList.add('is-active');
  };

  init();
  
})();