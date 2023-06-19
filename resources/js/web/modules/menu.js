(function () {

  const classes = {
    visible: 'is-visible',
    active: 'is-active',
    hidden: 'is-hidden',
    current: 'is-current',
  };

  const selectors = {
    menu: '[data-menu]',
    btns: '[data-menu] a',
    btnMenu: '[data-menu-btn]',
    btnMenuParent: '[data-menu-parent]',
  };

  const init = () => {

    const btnMenu = document.querySelector(selectors.btnMenu);
    if (btnMenu) {
      btnMenu.addEventListener("click", toggle, false);
    }

    const btnMenuParent = document.querySelectorAll(selectors.btnMenuParent);
    if (btnMenuParent) {
      btnMenuParent.forEach(function (btn) {
        btn.addEventListener("click", function(){
          toggleItems(btn);
        }, false);
      });
    }
  };

  const toggle = function(){
    const btnMenu = document.querySelector(selectors.btnMenu);
    btnMenu.classList.toggle(classes.active);

    const menu = document.querySelector(selectors.menu);
    menu.classList.toggle(classes.visible);

    if (!menu.classList.contains(classes.visible)) {
      hideAllItems();
    }
  };

  const toggleItems = function(btn){

    if (btn.classList.contains(classes.active)) {
      btn.classList.remove(classes.active);
      btn.nextElementSibling.classList.remove(classes.current);
      return;
    }

    if (btn.hasAttribute('data-menu-item-state')) {
      hideParentStateItems(btn);
    }
    else if (btn.hasAttribute('data-menu-item-category')) {
      hideParentCategoryItems(btn);
    }
    else {
      hideAllItems();
    }

    const ul = btn.nextElementSibling;
    ul.classList.add(classes.current);

    // remove is-active from all a inside data-menu
    const btns = document.querySelectorAll(selectors.btns);
    btns.forEach(function (b) {
      b.classList.remove(classes.active);
    });

    btn.classList.add(classes.active);
  };

  const hideAllItems = function(){
    const uls = document.querySelectorAll(selectors.btnMenuParent + ' + ul');
    uls.forEach(function (ul) {
      ul.previousElementSibling.classList.remove(classes.active);
      ul.classList.remove(classes.current);
    });
  };

  const hideParentStateItems = function(btn){
    const items = document.querySelectorAll('[data-menu-item-state]');
    items.forEach(function (item) {
      item.nextElementSibling.classList.remove(classes.current);
      item.classList.remove(classes.active);
    });
  };

  const hideParentCategoryItems = function(btn){
    const items = document.querySelectorAll('[data-menu-item-category]');
    items.forEach(function (item) {
      item.nextElementSibling.classList.remove(classes.current);
      item.classList.remove(classes.active);
    });
  };

  init();
  
})();