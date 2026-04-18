# HKDEV Theme

**Version:** 1.0.0  
**Author:** hayatulla1  
**Requires WordPress:** 6.0+  
**Requires PHP:** 7.4+  
**Text Domain:** hkdev

---

## Overview

HKDEV is a professional, standalone, fully dynamic WordPress theme built for WooCommerce and Elementor.  
It is **not** a child theme — it has no parent theme dependency.

## Features

- ✅ Standalone theme (no parent required)
- ✅ Full **Elementor & Elementor Pro** support (header/footer override via Customizer)
- ✅ Deep **WooCommerce** integration
  - Ajax Add-to-Cart (loop & single)
  - Cart fragments for live mini-cart updates
  - Custom product gallery with thumbnails
  - Grid/List view toggle with price range filter
  - Custom shop toolbar (sorting + view switcher)
- ✅ **Mega Menu** (desktop) with custom Walker
- ✅ **Mobile-First** app-style navigation panel
- ✅ Slide-out **Ajax Mini Cart**
- ✅ **Swiper.js** carousels (banner, category, product)
- ✅ OOP architecture (`HKDEV_Theme` singleton + modular `inc/` classes)
- ✅ SEO optimised (semantic HTML5, proper heading hierarchy, schema-ready)
- ✅ Customizer integration (Elementor header/footer IDs, social links, contact info, top-bar text)
- ✅ Translation-ready (`hkdev` text domain)

## Directory Structure

```
HKDEV/
├── assets/
│   ├── js/
│   │   ├── main.js              # Core UI + Ajax cart
│   │   ├── navigation.js        # Keyboard-accessible mega menu
│   │   ├── carousels.js         # Swiper.js init
│   │   └── product-gallery.js   # Single product gallery
│   └── vendor/
│       └── swiper/              # Swiper.js (install separately – see README)
├── inc/
│   ├── class-hkdev-woocommerce.php
│   ├── class-hkdev-elementor.php
│   ├── class-hkdev-ajax.php
│   ├── class-hkdev-navigation.php
│   └── class-hkdev-mini-cart.php
├── languages/
├── template-parts/
│   ├── content.php
│   ├── content-single.php
│   ├── content-page.php
│   ├── content-search.php
│   └── content-none.php
├── woocommerce/
│   ├── archive/
│   │   └── product-shop-toolbar.php
│   └── single-product/
│       └── product-image.php
├── 404.php
├── archive.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── searchform.php
├── sidebar.php
├── single.php
├── style.css                    # Theme header + all CSS
└── woocommerce.php
```

## Installation

1. Upload the `HKDEV` folder to `/wp-content/themes/`
2. Activate the theme in **Appearance → Themes**
3. Install Swiper.js vendor files (see `assets/vendor/README.md`)
4. Install and activate **WooCommerce** and/or **Elementor** as desired
5. Go to **Appearance → Customize** to:
   - Set your Elementor header/footer page IDs
   - Add social media links
   - Set contact information and top-bar announcement text

## Elementor Header/Footer

1. Build your header/footer as an Elementor page (or use Elementor Pro's Theme Builder)
2. Note the page ID
3. Go to **Appearance → Customize → Elementor Theme Builder**
4. Enter the page ID in the Header or Footer field

## Swiper.js Setup

See `assets/vendor/README.md` for instructions on installing Swiper.

## License

GNU General Public License v2 or later  
https://www.gnu.org/licenses/gpl-2.0.html