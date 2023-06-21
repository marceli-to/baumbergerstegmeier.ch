(function () {

  const states = {
    hasChange: false,
  };

  const classes = {
    active: 'is-active',
    hidden: 'hidden'
  };

  const selectors = {
    btnToggleAll: '[data-btn-toggle-both]',
    btnInfo: '[data-btn-toggle-info]',
    btnCredits: '[data-btn-toggle-credits]',
    info: '[data-info]',
    credits: '[data-credits]',
  };

  const init = () => {

    const btnToggleAll = document.querySelector(selectors.btnToggleAll);
    if (btnToggleAll) {
      btnToggleAll.addEventListener("click", toggle, false);
    }

    const btnInfo = document.querySelector(selectors.btnInfo);
    if (btnInfo) {
      btnInfo.addEventListener("click", toggleInfo, false);
    }

    const btnCredits = document.querySelector(selectors.btnCredits);
    if (btnCredits) {
      btnCredits.addEventListener("click", toggleCredits, false);
    }
   
  };

  const toggle = function(){
    states.hasChange = true;

    const btnToggleAll = document.querySelector(selectors.btnToggleAll);
    btnToggleAll.classList.toggle(classes.active);

    const btnInfo = document.querySelector(selectors.btnInfo);
    btnInfo.classList.toggle(classes.active);

    const btnCredits = document.querySelector(selectors.btnCredits);
    btnCredits.classList.toggle(classes.active);

    const info = document.querySelector(selectors.info);
    info.classList.toggle(classes.hidden);

    const credits = document.querySelector(selectors.credits);
    credits.classList.toggle(classes.hidden);
  };

  const toggleInfo = function(){
    states.hasChange = true;

    const btnInfo = document.querySelector(selectors.btnInfo);
    btnInfo.classList.toggle(classes.active);

    const btnToggleAll = document.querySelector(selectors.btnToggleAll);
    btnToggleAll.classList.toggle(classes.active);

    const info = document.querySelector(selectors.info);
    info.classList.toggle(classes.hidden);
  };

  const toggleCredits = function(){
    states.hasChange = true;

    const btnCredits = document.querySelector(selectors.btnCredits);
    btnCredits.classList.toggle(classes.active);

    const credits = document.querySelector(selectors.credits);
    credits.classList.toggle(classes.hidden);
  };

  const reset = function(){
    states.hasChange = false;

    const btnToggleAll = document.querySelector(selectors.btnToggleAll);
    btnToggleAll.classList.remove(classes.active);

    const btnInfo = document.querySelector(selectors.btnInfo);
    btnInfo.classList.remove(classes.active);

    const info = document.querySelector(selectors.info);
    info.classList.add(classes.hidden);

    const credits = document.querySelector(selectors.credits);
    credits.classList.add(classes.hidden);
  };

  init();
  
})();