---

# Comprehensive Page Analysis Report — The Grand Bistro QR Menu

---

## 1. Page Structure — Layout, Sections, Navigation

**Overall Layout:** Single-page, long-scroll menu application with a fixed bottom bar and two sticky top bars.

**Top-level DOM structure** (in order, all as direct `<body>` children):

| Element | ID / Class | Role |
|---|---|---|
| `<header class="menu-header">` | — | Restaurant name, table number, language switcher |
| `<nav class="menu-cat-nav">` | — | Horizontal scrollable category tabs |
| `<div class="menu-filter-bar">` | — | Dietary filter chips |
| `<section class="menu-section" id="cat-1">` → `id="cat-8"` | cat-1 through cat-8 | One `<section>` per category |
| `<div class="menu-bottom-bar" id="menu-bottom-bar">` | — | Fixed bottom bar (table + waiter) |
| `<div class="modal fade" id="tableSelectModal">` | — | Table picker modal |
| `<div class="menu-waiter-toast" id="waiter-toast">` | — | Waiter call toast notification |
| `<div class="modal fade" id="itemModal">` | — | Product detail modal |

**Navigation Type:** Hybrid scroll + tab navigation.
- The `<nav class="menu-cat-nav">` contains a `<div class="menu-cat-nav-inner">` (CSS `display: flex; overflow-x: auto`) with 8 pill buttons. Each button has `data-target="cat-N"` and scrolls the page to the corresponding `<section id="cat-N">`.
- The category nav is **sticky** (`position: sticky; top: 64px; z-index: 90`).
- The header is **sticky** (`position: sticky; top: 0; z-index: 100`).
- The bottom bar is **fixed** (`position: fixed; bottom: 0`).
- The filter bar (dietary chips) sits just below the nav, **not sticky** (static position) — it scrolls away with the page.

**8 Categories in the menu** (with item counts):

| ID | Category | Items |
|---|---|---|
| cat-1 | Starters & Soups | 6 |
| cat-2 | Salads | 4 |
| cat-3 | Main Courses | 5 |
| cat-4 | Burgers & Sandwiches | 4 |
| cat-5 | Pizza & Pasta | 5 |
| cat-6 | Sushi & Asian | 4 |
| cat-7 | Desserts | 4 |
| cat-8 | Beverages | 4 |

**Total: 36 products** across 8 sections.

Each `<section class="menu-section" data-cat="cat-N">` contains:
- `<h2 class="menu-section-title">` — Category title
- `<p class="menu-section-desc">` — Category subtitle/description
- `<div class="menu-items-grid">` — CSS Grid container (3 columns at full width, ~484px each, gap: 12px)

---

## 2. Product Card Features

**Card element:** `<div class="menu-item-card" role="button" aria-haspopup="dialog">`

Each card has these **data attributes** baked in server-side:
- `data-name` — lowercase product name (for filtering)
- `data-desc` — lowercase description (for search, not currently used)
- `data-veg="1"|""` — vegetarian flag
- `data-vegan="1"|""` — vegan flag
- `data-spicy="1"|""` — spicy flag
- `data-gf="1"|""` — gluten-free flag
- `data-item` — full JSON payload (see below)

**Visual card layout** (left thumbnail + right body):

```
[img.menu-item-image 88×88px]   [div.menu-item-body]
                                  [div.menu-item-name]      ← Product name
                                  [div.menu-item-desc]      ← Short description
                                  [div.menu-item-badges]    ← Badge pills (optional)
                                    [span.menu-badge ...]   ← Individual badges
                                  [div.menu-item-meta]      ← Prep time with clock SVG
                                [div.menu-item-footer]
                                  [span.menu-item-price]    ← Current price (orange)
                                  [span.menu-item-compare-price] ← Strikethrough old price
                                  [span.menu-discount-badge] ← "-17%" discount pill
```

**When no product image is available:** A `<div class="menu-item-image-placeholder">` with a fork-and-knife SVG icon on a light orange background is displayed instead. 21 of 36 items have real images; 15 use the placeholder.

**Badge types** (all `<span class="menu-badge menu-badge-*">`):

| Class | Label (TR) | Background Color |
|---|---|---|
| `menu-badge-featured` | Öne Çıkan (Featured) | `rgb(255, 243, 205)` / amber |
| `menu-badge-spicy` | Acılı (Spicy) | `rgb(255, 238, 232)` / light red |
| `menu-badge-vegetarian` | Vejetaryen | `rgb(230, 249, 240)` / mint green |
| `menu-badge-vegan` | Vegan | `rgb(237, 252, 231)` / light green |
| `menu-badge-gluten` | Glutensiz (Gluten-Free) | `rgb(255, 248, 224)` / cream |

**Discount display on card:** When a product has a discount, the footer shows three inline spans: current price (orange `menu-item-price`), original price with line-through grey (`menu-item-compare-price`, 12px), and an orange pill badge (`menu-discount-badge ms-1`) showing e.g. `-17%`.

**Pricing visible on card:** Yes — current price always shown. Compare price and discount badge shown only when applicable.

**Prep time:** Always shown below badges, formatted as `N dk` (dakika = minutes) with a clock SVG icon.

**Images:** Loaded `loading="lazy"`, displayed as 88×88px `object-fit: cover` squares. Images sourced from `themealdb.com`.

---

## 3. Product Detail Modal

**Modal element:** `<div class="modal fade" id="itemModal" tabindex="-1" role="dialog">`  
**Dialog class:** `modal-dialog modal-dialog-centered modal-item`  
**Content class:** `modal-content border-0 rounded-4 overflow-hidden position-relative`

**Modal is triggered** by clicking any `.menu-item-card`. There is no `data-bs-toggle="modal"` attribute on the cards — the modal is opened programmatically via vanilla JS in `menu.js` using a click event listener on the `.menu-items-grid` (event delegation pattern). The card's `data-item` JSON is parsed and injected into the modal's named elements.

**Full modal structure (with element IDs):**

```
#itemModal
  .modal-dialog.modal-item
    .modal-content.border-0.rounded-4
      [btn-close.item-modal-close]  ← data-bs-dismiss="modal"
      
      #itemModalCarousel.carousel.slide          ← Image carousel (Bootstrap)
        #itemModalImages.carousel-inner          ← <div.carousel-item> per image
          <img loading="lazy" object-fit:cover height:250px>
        .carousel-control-prev                   ← data-bs-slide="prev"
        .carousel-control-next                   ← data-bs-slide="next"
      
      #itemModalNoImage.item-modal-no-image.d-none  ← Shown when no image

      .modal-body.p-4
        #itemModalName  h5.item-modal-name       ← Product name
        #itemModalBadges                          ← Badge pills (same as card)
          .menu-item-badges.mb-2
            span.menu-badge.menu-badge-featured
            span.menu-badge.menu-badge-spicy / etc.
        #itemModalDesc  p.item-modal-desc         ← Full description paragraph
        
        #itemModalVariations                      ← Only shown when variations exist
          .menu-variations
            .menu-variations-label               ← "Boyut Seç" (Choose Size)
            .menu-variation-pills
              button.menu-variation-btn[data-price="12.90"]  ← .selected on active
                span.var-name + span.var-price
        
        #itemModalPrice .item-modal-price         ← Dynamic price display
          span.menu-item-price                   ← Active/current price
          span.menu-item-compare-price           ← Strikethrough (if discounted)
          span.menu-discount-badge               ← "-17%" pill (if discounted)
        
        #itemModalMeta                            ← Currently empty (reserved)
        
        #itemModalAllergens
          .menu-allergens
            .menu-allergens-content
              span.menu-allergens-label          ← "Alerjenler:" label
              [allergen list text]               ← e.g. "Shellfish, Gluten, Eggs"
            .menu-prep-time                      ← e.g. "10 dk" with clock SVG
```

**What IS shown in the modal:**
- Product name
- Dietary/featured badges (same badge set as card)
- Full description text
- Portion/size selection (if `variations` array has entries)
- Dynamic price (updates on variation selection via `data-price` attribute on buttons)
- Compare price + discount badge (if applicable)
- Allergens list (e.g. Shellfish, Gluten, Eggs, Dairy, Nuts, Fish, Soy, Peanuts, Mustard, Sesame — all 10 major EU allergen types are present in the dataset)
- Preparation time

**What is in the data but NOT displayed in the modal:**
- `calories` — field exists in JSON (range: 5 kcal for espresso → 920 kcal for BBQ burger) but is not rendered anywhere on the page
- `images` array (only `images[0]` is used; carousel code is in place but no product has >1 image currently)

**What is NOT present at all:**
- No "Add to Cart" button
- No quantity selector
- No ingredients list
- No ratings/reviews
- No nutrition table beyond what's in the data

**Variation system:** When a variation button is clicked, it gets the `.selected` class, and the `#itemModalPrice` text is updated to match the clicked button's `data-price` value. Price is formatted with the currency symbol pulled from the product's `currency` field (always `$` in this dataset).

---

## 4. Filtering & Search

**Search bar:** None. No `<input type="search">` or text search of any kind exists anywhere on the page.

**Category filter (top nav):** The `<nav class="menu-cat-nav">` contains 8 pill buttons (`button.menu-cat-pill`) with `data-target="cat-N"`. Clicking scrolls the viewport to the `<section id="cat-N">`. The `.active` class is applied to the pill that corresponds to the currently visible section (likely via `IntersectionObserver` in `menu.js`).

**Dietary filter bar:** `<div class="menu-filter-bar">` contains 5 filter chips:

| Button Class | `data-filter` value | Label |
|---|---|---|
| `menu-filter-chip active` | `all` | Tümü (All) |
| `menu-filter-chip` | `vegetarian` | Vejetaryen |
| `menu-filter-chip` | `vegan` | Vegan |
| `menu-filter-chip` | `spicy` | Acılı |
| `menu-filter-chip` | `gluten_free` | Glutensiz |

**How filtering works:** Each `.menu-item-card` has `data-veg`, `data-vegan`, `data-spicy`, and `data-gf` attributes set to `"1"` (truthy) or `""` (falsy). When a filter chip is clicked, the JS in `menu.js` iterates all `.menu-item-card` elements and toggles visibility by comparing the clicked `data-filter` value against the card's corresponding data attribute. The `.active` class moves to the clicked chip. No server round-trip — pure client-side DOM filtering.

**Sorting options:** None. No sort controls exist.

---

## 5. Language & Localization

**Language switcher component:** `<div class="menu-lang-switcher" id="menu-lang-switcher">` in the header, right-aligned.

**Structure:**
```html
<div id="menu-lang-switcher" class="menu-lang-switcher">
  <button id="menu-lang-btn" class="menu-lang-btn" aria-label="Language">
    <span>TR</span>  <!-- current language code -->
  </button>
  <div id="menu-lang-dropdown" class="menu-lang-dropdown">
    <a class="menu-lang-option" href="/tr/lang/en?from=...">EN</a>
    <a class="menu-lang-option active" href="/tr/lang/tr?from=...">TR</a>
  </div>
</div>
```

**How it works:** Clicking the `#menu-lang-btn` button toggles the `#menu-lang-dropdown` visibility. Clicking a language option navigates to `/tr/lang/{locale}?from={current-url}`, which is a Laravel server-side route that sets the locale in the session/cookie and redirects back to the page — all page strings (badge labels, UI labels like "Alerjenler:", "Boyut Seç", "Garson Çağır", "Masa") are translated on the server.

**Languages available:** 2 — Turkish (TR, currently active) and English (EN).

**Current state:** The `html[lang="tr"]` attribute confirms Turkish is active. All UI labels on screen are in Turkish. Product names and descriptions appear to be stored in English regardless of locale (food item names like "Crispy Calamari", "Honey BBQ Wings" remain in English).

---

## 6. Extra Features

**Table Number System:**
- The header shows `<div class="menu-header-table">Masa: <strong>3</strong></div>` — "Masa" is Turkish for "Table."
- The bottom bar has `<button id="table-select-btn" class="menu-table-btn has-table">` with `<span id="table-btn-label">Masa 3</span>`.
- The `.has-table` class is added when a table is selected.
- Clicking opens `#tableSelectModal` (Bootstrap modal, `data-bs-dismiss="modal"` on close button).

**Table Select Modal:**
- Title: "Masa Seç" (Select Table)
- Contains a `<div class="table-modal-grid">` with 6 numbered buttons (`button.table-grid-btn`, each with `data-table-id="N"` and `data-table-name="N"`).
- Currently selected table (3) gets the `.selected` CSS class.
- Selecting a different table updates the `#table-btn-label` text and sets the session/localStorage table number.

**Waiter Call Button:**
- `<button id="call-waiter-btn" class="menu-call-btn">Garson Çağır</button>` (Call Waiter) — full-width orange button in the bottom bar.
- On click, it triggers the `#waiter-toast` notification: `<div class="menu-waiter-toast" id="waiter-toast">🔔 Garson geliyor!</div>` ("Waiter is coming!").
- The toast appears as a fixed black pill (`background: rgb(26,26,26); border-radius: 24px; color: white; position: fixed`) and fades out after a brief delay (animation via CSS/JS, opacity transitions from 1 to 0).

**Cart/Order System:** None. This is a **view-only digital menu** — there is no cart, no order submission, no checkout. The modal has no "Add to Cart" button.

**QR Code:** No QR code element is visible or present in the DOM. The application is accessed via QR code scan in a real deployment (the URL `/tr/themes/classic/preview` suggests this is a preview mode of a QR menu SaaS platform).

**Social Links:** None present.

**Ratings/Reviews:** None present.

**Currency:** US Dollar (`$`) — defined per product in the `data-item` JSON as `"currency": "$"` and prepended inline in the template. All 36 products use `$`.

**Price range:** $3.90 (Double Espresso) to $42.90 (Slow-Cooked Beef Tenderloin).

**Logo/Branding:** No uploaded logo image. The `<div class="menu-header-logo-placeholder">T</div>` shows the first letter of the restaurant name in an orange rounded square — a fallback placeholder.

**Body classes:** `menu-body has-bottom-bar` — the `has-bottom-bar` class likely adjusts the page's bottom padding to prevent the fixed bar from covering content.

---

## 7. Technical Stack

**Backend/Framework:**
- **Laravel (PHP)** — confirmed by: `qrmenu.test` domain, URL pattern `/tr/themes/classic/preview`, `XSRF-TOKEN` cookie, route structure `/tr/lang/{locale}?from=`, and the fact that all 36 product cards are fully server-rendered with data baked into HTML attributes (Blade template rendering).

**Build Tool:**
- **Vite** — confirmed by `http://[::1]:5173/@vite/client` script tag (Vite dev server on localhost:5173) and `http://[::1]:5173/resources/js/menu.js` / `resources/css/menu.css` paths. This is Laravel's standard Vite integration.

**Frontend JavaScript:**
- **Vanilla JS (no framework)** — No Vue, React, Alpine.js, or any other JS framework detected. `window.Vue`, `window.React`, `window.Alpine`, and all framework-specific DOM markers are absent. The single JS file is `resources/js/menu.js`.
- The JS uses vanilla DOM event listeners for: modal opening (click on `.menu-item-card`), variation selection, filter chips, language dropdown toggle, category nav scroll, waiter toast, and table selection.

**CSS Framework:**
- **Bootstrap 5.3.3** — loaded from `cdn.jsdelivr.net/npm/bootstrap@5.3.3`. Bootstrap's modal system is used for both `#itemModal` and `#tableSelectModal`. Bootstrap carousel (`#itemModalCarousel`) is used for product images. Bootstrap utility classes (`border-0`, `rounded-4`, `overflow-hidden`, `d-none`, `fw-bold`, `mb-0`, `p-4`, `px-4`, `py-3`, `ms-1`) are present throughout.

**Custom CSS:**
- `resources/css/menu.css` — all `.menu-*` prefixed classes are custom (BEM-influenced, flat naming convention, not nested component structure).

**Icons:**
- All icons (clock for prep time, fork-and-knife for placeholder, bell for waiter, globe for language) are inline SVGs. No icon library (FontAwesome, Bootstrap Icons, Material Icons) is used.

**Fonts:**
- System font stack: `-apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif` — no custom web font loaded.

**Data Loading:**
- **Static server-side rendering** — all product data is embedded directly in the HTML as `data-item='{"name":...}'` JSON attributes on each `.menu-item-card`. No AJAX/fetch calls are made for product data. The filtering, modal population, and price updates are entirely client-side operations on pre-rendered DOM data.

**Image Source:**
- Product images are hosted externally at `www.themealdb.com` (a public food database API used for demo data). Images are loaded lazily.

---

## 8. Code Quality & Patterns

**Component Structure:** There are no JS components in the framework sense — the application is a single-page Blade template with one companion `menu.js` file. The structure is a traditional MPA (Multi-Page App) with a single interactive page. All HTML is server-rendered; JS only handles UI interactions post-load.

**Class Naming Convention:** A consistent `menu-` prefix namespace is used for all custom classes, with a flat (non-nested) BEM-like pattern:
- Block: `menu-header`, `menu-cat-nav`, `menu-filter-bar`, `menu-section`, `menu-item-card`
- Element: `menu-header-info`, `menu-header-name`, `menu-header-table`, `menu-item-body`, `menu-item-name`, `menu-item-desc`, `menu-item-badges`, `menu-item-footer`, `menu-item-price`, `menu-item-image`, `menu-item-meta`
- Modifier (state): `active`, `selected`, `has-table`, `has-bottom-bar`

Bootstrap utility classes (`border-0`, `rounded-4`, `d-none`, `p-4`, etc.) are mixed in alongside custom classes, a common and pragmatic pattern in Laravel+Bootstrap projects.

**Modal Trigger Pattern:** Product detail modal uses **programmatic Bootstrap Modal API** triggered via vanilla JS event delegation. Cards have `role="button"` and `aria-haspopup="dialog"` for accessibility but no `data-bs-toggle` attribute. The JS listens for clicks on `.menu-items-grid`, identifies the clicked `.menu-item-card` via `event.target.closest('.menu-item-card')`, parses its `data-item` JSON attribute, then injects data into the modal's named elements (`#itemModalName`, `#itemModalDesc`, `#itemModalPrice`, etc.) before calling Bootstrap's `Modal.show()`.

**How Filtering Works (inferred):** A single click handler on `.menu-filter-bar` uses event delegation. When a `.menu-filter-chip` is clicked, its `data-filter` value is read. Then `document.querySelectorAll('.menu-item-card')` is iterated — each card's visibility is toggled (likely `display: none` or a CSS class toggle) based on whether its `data-veg`, `data-vegan`, `data-spicy`, or `data-gf` attribute is truthy. The `all` filter shows everything. Empty sections (all items hidden) would still show their headings (this edge case may or may not be handled).

**How Category Nav Highlighting Works (inferred):** Likely uses the `IntersectionObserver` API to detect which `.menu-section` is in the viewport, then updates the `.active` class on the corresponding `.menu-cat-pill[data-target]` button. Clicking a pill calls `document.getElementById(target).scrollIntoView({behavior: 'smooth'})`.

**Variation Selection Pattern:** Variation buttons (`.menu-variation-btn`) each carry a `data-price` attribute. A click handler on `#itemModalVariations` toggles the `.selected` class to the clicked button and updates `#itemModalPrice`'s `span.menu-item-price` text with the new formatted price string.

**Data Architecture:** Embedding the full product JSON in `data-item` is a deliberate and pragmatic choice — it avoids any AJAX calls and makes the JS completely stateless and dependency-free. The trade-off is larger initial HTML payload, but for a menu of 36 items this is entirely acceptable (~5–10KB of JSON data total).

**Notable Missing Items (potential improvements):**
- Calories (`calories` field) is in every product's JSON but never displayed — likely a planned feature not yet implemented in this theme
- The `#itemModalMeta` div is always empty — appears to be a reserved slot
- `#itemModalCarousel` carousel controls are always rendered even for single-image products (prev/next arrows would be dead for most items)
- No `<form>` elements, no `<input>` fields, no cart state — confirms this is a pure browse-only menu with waiter-assisted ordering