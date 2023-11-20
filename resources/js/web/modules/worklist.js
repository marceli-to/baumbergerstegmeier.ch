(function () {

  const selectors = {
    wrapper: '[data-worklist]',
    items: '[data-worklist-items]',
    btn: '[data-btn-worklist-filter]',
    btnCurrent: '[data-btn-worklist-current]'
  };

  let hasUserInteraction = false;

  const init = () => {
    const btns = document.querySelectorAll(selectors.btn);
    if (!btns.length) return;
    btns.forEach((btn) => {
      btn.addEventListener('click', toggle);
    });

    // on click outside of the wrapper, hide all data-hidden and set active class to 'btnCurrent'
    const wrapper = document.querySelector(selectors.wrapper);
    document.addEventListener('click', function(event) {
      const isClickInside = wrapper.contains(event.target);
      if (!isClickInside) {
        reset();
      }
    }, false);

    // handle scrolling up and down
    // if the user starts scrolling up, add class 'is-sticky' to the wrapper
    // if the user starts scrolling down and scrolls for more than 500px, remove class 'is-sticky' from the wrapper
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
      const st = window.pageYOffset || document.documentElement.scrollTop;

      if (st > lastScrollTop && st > 300 && !hasUserInteraction) {
        wrapper.classList.remove('is-sticky');
      } 
      else {
        wrapper.classList.add('is-sticky');
      }
    
      lastScrollTop = st <= 0 ? 0 : st; // Update lastScrollTop
    });
  };

  const toggle = function(btn){

    const worklist = document.querySelector(selectors.wrapper);
    const isSticky = worklist.classList.contains('is-sticky');
    hasUserInteraction = true;        
    // if the content is already visible, hide it
    if (!btn.target.nextElementSibling.classList.contains('hidden')) {
      btn.target.nextElementSibling.classList.add('hidden');
      btn.target.classList.remove('is-active');
      worklist.classList.remove('has-selection');
      setCurrent();
      return;
    }

    // hide all data-hidden
    hideAll();

    // show the one next to the button if it's hidden
    const content = btn.target.nextElementSibling;
    content.classList.remove('hidden');

    // add has-selection class on [data-worklist]
    worklist.classList.add('has-selection');

    // remove active class from all buttons in data-worklist
    resetButtons();
    
    // add active class to the button
    btn.target.classList.add('is-active');

    // add is-sticky class to the wrapper if isSticky is true
    // and screen size is smaller than 768px
    if (isSticky && window.innerWidth < 768) {
      worklist.classList.add('is-sticky');
      // scroll wrapper into view
      worklist.scrollIntoView();
      hasUserInteraction = false;
    }

  };

  const hideAll = () => {
    const contents = document.querySelectorAll(selectors.items);
    contents.forEach((content) => {
      content.classList.add('hidden');
    });
  };

  setCurrent = () => {
    resetButtons();
    const btnCurrent = document.querySelector(selectors.btnCurrent);
    btnCurrent.classList.add('is-active');
  };
  
  resetButtons = () => {
    const btns = document.querySelectorAll(selectors.wrapper + ' a');
    btns.forEach((btn) => {
      btn.classList.remove('is-active');
    });
  };

  reset = () => {
    hideAll();
    setCurrent();
    const worklist = document.querySelector(selectors.wrapper);
    worklist.classList.remove('has-selection');
  };

  init();
  
})();