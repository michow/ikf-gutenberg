# IKF Theme

Custom WordPress block theme for IKF (based on Twenty Twenty-Five).

## Requirements

- [Local](https://localwp.com/) (or another local WordPress environment)
- Node.js **24.13.0**
- npm **10.2.3+**

## Setup

1. Create or start the site in Local and confirm WordPress loads.
2. Activate the **IKF** theme in **Appearance → Themes**.
3. Open a terminal in the theme directory:

```bash
cd "/app/public/wp-content/themes/ikf"
```

4. Install dependencies:

```bash
npm install
```

## Local development

Watch SCSS and JS and rebuild expanded + minified assets on change:

```bash
npm run watch
```

Leave that process running while you edit files under `src/`.

### Source → output

| Edit | Compiles to |
|------|-------------|
| `src/scss/**` (entry: `main.scss`) | `assets/css/main.css` + `main.min.css` |
| `src/js/**` (entry: `main.js`) | `assets/js/main.js` + `main.min.js` |

Root `style.css` / `style.min.css` are the theme header + base stylesheet. Custom styles belong in `src/scss/`.

### One-off production build

```bash
npm run build
```

Runs CSS, JS, and `style.min.css` builds once (no watch).

### Useful scripts

| Command | What it does |
|---------|----------------|
| `npm run watch` | Watch SCSS + JS (expanded and minified) |
| `npm run build` | Full production build |
| `npm run build:css` | Compile + minify SCSS only |
| `npm run build:js` | Bundle + minify JS only |
| `npm run build:style` | Minify root `style.css` → `style.min.css` |

### Necessary Plugins

- ACF Pro
- Post Types Order
- Safe SVG

## Notes

- Compiled assets in `assets/` are enqueued by the theme. After changing SCSS/JS, refresh the site (with `npm run watch` running, or run `npm run build`).
- Prefer the Site Editor for layout/content. Use SCSS for custom theme styles that blocks can’t cover cleanly.
- Plugins used by this site (e.g. ACF Pro) should already be installed in Local; theme PHP expects ACF for instructor/member fields.
