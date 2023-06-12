export default {

  methods: {

    moneyFormat(amount) {
      return amount.toFixed(2);
    },

    shorten(str, length = 10, suffix = "...") {
      return str.substring(0, length) + suffix;
    },

    randomString() {
      return Math.random().toString(36).slice(2);
    },

    getImageSrc(image, template, size = 1600) {
      let coords = '0,0,0,0';
      if (image.coords_w && image.coords_h) {
        coords = `${image.coords_w},${image.coords_h},${image.coords_x},${image.coords_y}`;
      }
      return `/img/${template}/${image.name}/${size}/${coords}/3x2`;
    },

    validUrl(url) {
      if (!url) return false;
      const pattern = /^https?:\/\/((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/i;
      return pattern.test(url);
    }

  }
};