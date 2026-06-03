BesiBau WordPress Theme (multi-page, Elementor pre-loaded)
=========================================================

INSTALL / UPDATE
1. WordPress > Appearance > Themes > Add New > Upload Theme.
2. Choose the theme .zip, Install Now (choose "Replace current" if it already exists).
3. Activate.

ON ACTIVATION the theme automatically:
- Creates the pages: Home, Über Uns, Dienstleistungen, Unser Team, Unsere Arbeit, Kontakt
- Pre-loads each page with its full design as EDITABLE Elementor blocks (bundled local images)
- Sets Home as the front page and turns on pretty permalinks

EDIT EVERYTHING WITH ELEMENTOR
Open any page > "Edit with Elementor". The whole design appears as editable sections,
columns and widgets. Keep the page template on "Default" so the BesiBau header and footer
stay around it (do NOT use "Elementor Canvas").

If you already had an older version installed: the pre-load only fills pages that are still
empty. Re-activate the theme (switch to another theme and back) to load the design into
empty pages, or visit wp-admin once (it also runs then). If styling looks off on first
load, Elementor > Tools > Regenerate CSS.

HEADER & FOOTER
The theme provides the header and footer (template-parts/header-default.php and
footer-default.php). If you assign an Elementor Pro Theme Builder header/footer location,
or pick a saved template under Customize > BesiBau Elementor, that is used instead.

EDIT YOUR DETAILS (one place)
functions.php > besibau_info(): phone, email, address, social links. (Switzerland only.)

YOUR LOGO
Appearance > Customize > Site Identity > Logo. Otherwise a text logo "BesiBau" is shown.

SWAP THE PHOTOS
assets/img/ holds all images. Replace a file with your own using the SAME name:
hero.jpg, about1.jpg, about2.jpg, why.jpg, trio.jpg, cta.jpg, proj1..proj6.jpg.

CONTACT FORM
The built-in form sends via WordPress. The Elementor contact page has a placeholder for a
form plugin shortcode (Contact Form 7 / WPForms). If mail does not send, install WP Mail SMTP.

COLOURS
Gold #BA9056, dark #20232E. Edit the :root variables at the top of style.css.

DEVELOPER NOTES
- inc/elementor/*.json : the per-page Elementor designs loaded on activation (images use the
  THEME_URI token, replaced with the live theme URL at load time).
- inc/github-theme-updater.php : GitHub-based theme updates (Update URI in style.css).
- .github/workflows/theme-release.yml : packages besibau-theme.zip on push to main.
