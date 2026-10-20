// Full-screen gallery for [data-lightbox] links. Links with the same value
// are one gallery, in document order (hidden ones count, as with fancyBox).
// Replaces fancyBox 5 with the same look; from cra.ch.

const selectors = {
  item: '[data-lightbox]',
};

const classes = {
  open: 'is-open',
  current: 'is-current',
  locked: 'has-lightbox',
};

// Keep in sync with the transitions in components/_lightbox.scss
const fadeDuration = 250;
const slideDuration = 400;
const swipeThreshold = 50;

let items = [];
let index = 0;
let el = null;
let lastFocus = null;
let swiped = false;
let closing = null;

export function init() {
  document.addEventListener('click', (event) => {
    const link = event.target.closest(selectors.item);
    if (!link) return;
    event.preventDefault();
    items = [...document.querySelectorAll(selectors.item)].filter((item) => item.dataset.lightbox === link.dataset.lightbox);
    open(items.indexOf(link));
  });
}

const build = () => {
  el = document.createElement('div');
  el.className = 'lightbox';
  el.hidden = true;
  el.setAttribute('role', 'dialog');
  el.setAttribute('aria-modal', 'true');
  el.setAttribute('aria-label', 'Bildergalerie');
  el.innerHTML = `
    <div class="lightbox__stage"></div>
    <button type="button" class="lightbox__prev" aria-label="Vorheriges Bild"></button>
    <button type="button" class="lightbox__next" aria-label="Nächstes Bild"></button>
    <div class="lightbox__caption" aria-live="polite"></div>
    <button type="button" class="lightbox__close" aria-label="Schliessen"></button>`;
  document.body.appendChild(el);

  el.querySelector('.lightbox__close').addEventListener('click', close);
  el.querySelector('.lightbox__prev').addEventListener('click', () => show(index - 1));
  el.querySelector('.lightbox__next').addEventListener('click', () => show(index + 1));

  // A click beside the image closes
  el.addEventListener('click', (event) => {
    const beside = event.target === el || event.target.matches('.lightbox__stage');
    if (beside && !swiped) close();
  });

  let start = null;
  el.addEventListener('pointerdown', (event) => {
    swiped = false;
    start = { x: event.clientX, y: event.clientY };
  });
  el.addEventListener('pointerup', (event) => {
    if (!start) return;
    const dx = event.clientX - start.x;
    const dy = event.clientY - start.y;
    start = null;
    if (Math.abs(dx) > swipeThreshold && Math.abs(dx) > Math.abs(dy)) {
      swiped = true;
      show(index + (dx < 0 ? 1 : -1));
    }
  });
  el.addEventListener('pointercancel', () => { start = null; });
};

const open = (i) => {
  if (!el) build();
  clearTimeout(closing);
  lastFocus = document.activeElement;
  el.hidden = false;
  document.documentElement.classList.add(classes.locked);
  document.addEventListener('keydown', onKeydown);
  show(i, true);
  // Next frame, so the opacity transition runs
  requestAnimationFrame(() => el.classList.add(classes.open));
  el.querySelector('.lightbox__close').focus({ preventScroll: true });
};

const close = () => {
  el.classList.remove(classes.open);
  document.removeEventListener('keydown', onKeydown);
  closing = setTimeout(() => {
    el.hidden = true;
    el.querySelector('.lightbox__stage').replaceChildren();
    document.documentElement.classList.remove(classes.locked);
    lastFocus?.focus({ preventScroll: true });
  }, fadeDuration);
};

// The counter, then the link's hidden <legend> (caption / credits) or
// else its data-caption, side by side as fancyBox showed them
const caption = (link, i) => {
  const box = el.querySelector('.lightbox__caption');
  const counter = document.createElement('div');
  counter.textContent = `${i + 1}/${items.length}`;
  box.replaceChildren(counter);

  const legend = link.querySelector('legend');
  if (legend) {
    box.append(...[...legend.childNodes].map((node) => node.cloneNode(true)));
  }
  else if (link.dataset.caption?.trim()) {
    const text = document.createElement('div');
    text.className = 'ml-12x';
    text.textContent = link.dataset.caption.trim();
    box.append(text);
  }
};

// Loops at both ends, as fancyBox did
const show = (to, immediate = false) => {
  const i = (to + items.length) % items.length;
  if (i === index && !immediate) return;
  index = i;
  const link = items[i];

  const stage = el.querySelector('.lightbox__stage');
  const previous = [...stage.children];
  const img = document.createElement('img');
  img.alt = (link.querySelector('legend')?.textContent || link.dataset.caption || '').trim();
  img.draggable = false;
  img.src = link.href;
  stage.appendChild(img);

  const reveal = () => {
    if (index !== i) return;
    img.classList.add(classes.current);
    previous.forEach((p) => p.classList.remove(classes.current));
    setTimeout(() => previous.forEach((p) => p.remove()), immediate ? 0 : slideDuration);
  };
  img.decode().then(reveal, reveal);

  caption(link, i);
  el.querySelectorAll('.lightbox__prev, .lightbox__next').forEach((button) => { button.hidden = items.length < 2; });

  // Preload the neighbours
  [items[(i + items.length - 1) % items.length], items[(i + 1) % items.length]].forEach((n) => { new Image().src = n.href; });
};

const onKeydown = (event) => {
  if (event.key === 'Escape') close();
  else if (event.key === 'ArrowLeft') show(index - 1);
  else if (event.key === 'ArrowRight') show(index + 1);
  else if (event.key === 'Tab') {
    // Keep focus on the three buttons
    const buttons = [...el.querySelectorAll('button')];
    const at = buttons.indexOf(document.activeElement);
    const next = (at + (event.shiftKey ? -1 : 1) + buttons.length) % buttons.length;
    buttons[next].focus();
    event.preventDefault();
  }
};
