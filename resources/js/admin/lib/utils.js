import { confirm } from '@/lib/confirm';

/**
 * Asks before deleting (the confirm dialog); resolves true or false.
 */
export function confirmDelete(title = 'Eintrag löschen', options = {}) {
  return confirm({ title, ...options });
}

/**
 * Sets each item's order to its index and returns the list.
 */
export function withOrder(items) {
  items.forEach((item, index) => item.order = index);
  return items;
}

/**
 * Plain text of an HTML string (tags out, entities decoded), cut to length
 * (list labels). DOMParser doesn't run the HTML's scripts or handlers.
 */
export function truncate(html, length = 100) {
  // Line breaks and block ends become spaces, so words don't run together
  const spaced = (html ?? '').replace(/<br\s*\/?>/gi, ' ').replace(/<\/(p|h\d|li|div)>/gi, ' $&');
  const text = new DOMParser().parseFromString(spaced, 'text/html').body.textContent.replace(/\s+/g, ' ').trim();
  return text.length > length ? `${text.slice(0, length)}…` : text;
}

/**
 * An upload's name without the uniqid prefix the server adds:
 * "6a97cc421059b_01-situation.png" → "01-situation.png".
 */
export function displayName(name) {
  return (name ?? '').replace(/^[0-9a-f]{13}_/, '');
}

/**
 * { field: true } for each empty field; paths like 'title' or 'images'.
 */
export function requiredErrors(record, paths) {
  const errors = {};
  paths.forEach(path => {
    const value = path.split('.').reduce((object, key) => object?.[key], record);
    if (value === null || value === undefined || value === '' || (Array.isArray(value) && !value.length)) {
      errors[path] = true;
    }
  });
  return errors;
}

/**
 * [{ id, description }] (categories, states, teams) → [{ value, label }]
 * for a select.
 */
export function toOptions(items, label = 'description') {
  return (items ?? []).map(item => ({ value: item.id, label: item[label] }));
}
