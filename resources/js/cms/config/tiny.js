export default {
  license_key: 'gpl',
  skin_url: '/assets/js/cms/_tinymce/skins/custom',
  branding: false,
  menubar: false,
  statusbar: false,
  browser_spellcheck: true,
  // external_plugins: {
  //   link: '/assets/js/cms/tinymce/plugins/link/plugin.min.js',
  // },
  relative_urls: false,
  plugins: ['lists', 'code', 'link'],
  toolbar: 'undo redo | bold | bullist | link | superscript | removeformat | styles | code',
  paste_as_text: true,
  height: "320px",
  style_formats_merge: false,
  style_formats: [{
    title: 'Text',
    items: [
      { title: 'Überschrift 2', block : 'h2'},
      { title: 'Überschrift 3', block : 'h3'},
      { title: 'Überschrift 4', block : 'h4'},
      { title: 'Trennung verhindern', inline : 'span', classes : 'no-word-break'},
    ],
  }],

  link_class_list: [
    {title: 'None', value: ''},
    {title: 'Icon ↗', value: 'icon-arrow-right-up'},
  ],

  link_list: '/page-links',
};
