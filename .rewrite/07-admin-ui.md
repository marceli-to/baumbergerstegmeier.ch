# Admin UI refresh — light, as in cra

Decided 2026-10-20 (D7): a light refresh, like cra (`08-admin-ui.md` on
`rework/laravel-13-vue-3`), not more. It rides along with the port,
because every component gets rewritten anyway.

cra took oxid's `4dfc989`, `5f77c53`, `8c0b175`, `d787c01`, `adfd57a` and
`2457ad4`, **without** the font-weight change and the shared card. Same here:

- Phosphor icons (light), already in the package table: `vue-feather-icons`
  is Vue 2 only. Own SVG icon components stay.
- 1px lines; calmer menu with sections, active page underlined, close
  button.
- Yes/no fields as toggles (`components/ui/Toggle.vue`).
- Login: random published image as background. Comes with the own login
  (D5).
- Land on the most-used list instead of an empty dashboard. Which list
  that is here: ask (projects, probably).
- Lightbox pickers and the confirm dialog as in cra (border, no shadow;
  `confirmDelete()`).

Not included: font-weight change, shared image/file card, any change to
the admin's information architecture.

About 0.5–0.75 day on top of the port (cra's number).
